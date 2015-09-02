<?php
/*
 * This file is part of Totara LMS
 *
 * Copyright (C) 2010 onwards Totara Learning Solutions LTD
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author David Curry <david.curry@totaralms.com>
 * @package totara
 * @subpackage program
 */

defined('MOODLE_INTERNAL') || die();

class totara_program_observer {

    /**
     * Handler function called when a program_unassigned event is triggered
     *
     * @param \totara_program\event\program_unassigned $event
     * @return bool Success status
     */
    public static function unassigned(\totara_program\event\program_unassigned $event) {
        global $DB;

        $programid = $event->objectid;
        $userid = $event->userid;

        try {
            $messagesmanager = prog_messages_manager::get_program_messages_manager($programid);
            $program = new program($programid);
            $user = $DB->get_record('user', array('id' => $userid));
            $isviewable = $program->is_viewable($user);
            $messages = $messagesmanager->get_messages();
        } catch (ProgramException $e) {
            return true;
        }

        // Send notifications to user and (optionally) the user's manager.
        foreach ($messages as $message) {
            if ($message->messagetype == MESSAGETYPE_UNENROLMENT) {
                if ($user && $isviewable) {
                    $message->send_message($user);
                }
            }
        }

        return true;
    }

    /**
     * Handler function called when a program_completed event is triggered
     *
     * @param \totara_program\event\program_completed $event
     * @return bool Success status
     */
    public static function completed(\totara_program\event\program_completed $event) {
        global $CFG, $DB;
        require_once($CFG->dirroot.'/totara/plan/lib.php');

        $programid = $event->objectid;
        $userid = $event->userid;

        try {
            $messagesmanager = prog_messages_manager::get_program_messages_manager($programid);
            $program = new program($programid);
            $user = $DB->get_record('user', array('id' => $userid));
            $isviewable = $program->is_viewable($user);
            $messages = $messagesmanager->get_messages();
        } catch (ProgramException $e) {
            return true;
        }

        // Send notification to user.
        foreach ($messages as $message) {
            if ($message->messagetype == MESSAGETYPE_PROGRAM_COMPLETED) {
                if ($user && $isviewable) {
                    $message->send_message($user);
                }
            }
        }

        // Auto plan completion hook.
        dp_plan_item_updated($userid, 'program', $programid);

        return true;
    }

    /**
     * Handler function called when a courseset_completed event is triggered
     *
     * @param \totara_program\event\program_courseset_completed $event
     * @return bool Success status
     */
    public static function courseset_completed(\totara_program\event\program_courseset_completed $event) {
        global $DB;

        $programid = $event->objectid;
        $userid = $event->userid;
        $coursesetid = $event->other['coursesetid'];

        try {
            $messagesmanager = prog_messages_manager::get_program_messages_manager($programid);
            $messages = $messagesmanager->get_messages();
        } catch (ProgramException $e) {
            return true;
        }

        // Send notification to user.
        foreach ($messages as $message) {
            if ($message->messagetype == MESSAGETYPE_COURSESET_COMPLETED) {
                if ($user = $DB->get_record('user', array('id' => $userid))) {
                    $message->send_message($user, null, array('coursesetid' => $coursesetid));
                }
            }
        }

        return true;
    }

    /**
     * Event that is triggered when a user is deleted.
     *
     * Cancels a user from any programs they are associated with, tables to clear are
     * prog_assignment
     * prog_future_user_assignment
     * prog_user_assignment
     * prog_exception
     * prog_extension
     * prog_messagelog
     *
     * @param \core\event\user_deleted $event
     *
     */
    public static function user_deleted(\core\event\user_deleted $event) {
        global $DB;

        $userid = $event->objectid;

        // We don't want to send messages or anything so just wipe the records from the DB.
        $transaction = $DB->start_delegated_transaction();

        // Delete all the individual assignments for the user.
        $DB->delete_records('prog_assignment', array('assignmenttype' => ASSIGNTYPE_INDIVIDUAL, 'assignmenttypeid' => $userid));

        // Delete any future assignments for the user.
        $DB->delete_records('prog_future_user_assignment', array('userid' => $userid));

        // Delete all the program user assignments for the user.
        $DB->delete_records('prog_user_assignment', array('userid' => $userid));

        // Delete all the program exceptions for the user.
        $DB->delete_records('prog_exception', array('userid' => $userid));

        // Delete all the program extensions for the user.
        $DB->delete_records('prog_extension', array('userid' => $userid));

        // Delete all the program message logs for the user.
        $DB->delete_records('prog_messagelog', array('userid' => $userid));

        $transaction->allow_commit();
    }

    /*
     * This function is to cope with program assignments set up
     * with completion deadlines 'from first login' where the
     * user had not yet logged in.
     *
     * @param \core\event\user_loggedin $event
     * @return boolean True if all the update_learner_assignments() succeeded or there was nothing to do
     */
    public static function assignments_firstlogin(\core\event\user_loggedin $event) {
        global $CFG, $DB, $USER;

        if ($USER->firstaccess != $USER->currentlogin) {
            // This is not the first login.
            return true;
        }

        require_once($CFG->dirroot . '/totara/program/lib.php');

        prog_assignments_firstlogin($DB->get_record('user', array('id' => $event->objectid)));

        return true;
    }

    /**
     * This function is to clean up any references to courses within
     * programs when they are deleted. Any coursesets that become empty
     * due to this are also deleted as programs does not allow empty
     * coursesets.
     *
     * @param \core\event\course_deleted $event
     * @return boolean True if all references to the course are deleted correctly
     */
    public static function course_deleted(\core\event\course_deleted $event) {
        global $DB;

        $courseid = $event->objectid;

        $transaction = $DB->start_delegated_transaction();

        $DB->delete_records('prog_courseset_course', array('courseid' => $courseid));

        // Get IDs of empty coursesets so we can delete them.
        $emptycoursesets = $DB->get_fieldset_sql('SELECT cs.id FROM {prog_courseset} cs LEFT JOIN {prog_courseset_course} c ON cs.id = c.coursesetid WHERE c.coursesetid IS NULL GROUP BY cs.id');

        if (!empty($emptycoursesets)) {
            list($insql, $inparams) = $DB->get_in_or_equal($emptycoursesets);
            $DB->delete_records_select('prog_courseset', "id {$insql}", $inparams);
        }

        $transaction->allow_commit();

        return true;
    }

    /**
     * This function is triggered when the members of a cohort are (or might have been) updated.
     * It needs to mark all related programs and certifications for deferred update. The prog and cert
     * users will then be updated the next time the deferred program assignments scheduled task runs.
     *
     * @param \core\event\base $event Can be various events but objectid must be a cohort.
     * @return boolean True if successful
     */
    public static function cohort_members_updated(\core\event\base $event) {
        global $DB;
        $cohortid = $event->objectid;

        $sql = "UPDATE {prog} SET assignmentsdeferred = 1
                 WHERE id IN (SELECT programid
                                FROM {prog_assignment}
                               WHERE assignmenttype = :assignmenttypecohort
                                 AND assignmenttypeid = :cohortid)";
        $DB->execute($sql, array('assignmenttypecohort' => ASSIGNTYPE_COHORT, 'cohortid' => $cohortid));

        return true;
    }

    /**
     * This function is triggered when a user's position is updated. Their manager, position or organisation may
     * have changed, in which case we mark the programs and certifications which contain both the new and old
     * management hierarchy, position and organisation for deferred update.
     *
     * @param \totara_core\event\position_updated $event
     * @return boolean True if successful
     */
    public static function position_updated(\totara_core\event\position_updated $event) {
        global $DB;

        // Only process the primary position assignment.
        if ($event->other['type'] != POSITION_TYPE_PRIMARY) {
            return true;
        }

        $newassignment = $DB->get_record('pos_assignment',
            array('userid' => $event->relateduserid, 'type' => POSITION_TYPE_PRIMARY));

        if ($newassignment->managerid != $event->other['oldmanagerid']) {
            $directmanagerstoprocess = array();
            $indirectmanagerstoprocess = array();

            if ($newassignment->managerid) {
                $directmanagerstoprocess[] = $newassignment->managerid;
                $path = explode('/', substr($newassignment->managerpath, 1));
                $countpath = count($path);
                if ($countpath > 2) {
                    // Don't include the user or their manager here.
                    $indirectmanagerstoprocess = array_merge($indirectmanagerstoprocess, array_slice($path, 0, $countpath - 2));
                }
            }

            if ($event->other['oldmanagerid']) {
                $directmanagerstoprocess[] = $event->other['oldmanagerid'];
                $path = explode('/', substr($event->other['oldmanagerpath'], 1));
                $countpath = count($path);
                if ($countpath > 2) {
                    // Don't include the user or their manager here.
                    $indirectmanagerstoprocess = array_merge($indirectmanagerstoprocess, array_slice($path, 0, $countpath - 2));
                }
            }

            if (!empty($directmanagerstoprocess) || !empty($indirectmanagerstoprocess)) {
                $params = array('assignmenttypemanager' => ASSIGNTYPE_MANAGER);
                $managersql = "";

                if (!empty($directmanagerstoprocess)) {
                    list($directinsql, $directparams) = $DB->get_in_or_equal($directmanagerstoprocess, SQL_PARAMS_NAMED);
                    $managersql .= "assignmenttypeid " . $directinsql;
                    $params = array_merge($params, $directparams);
                }

                if (!empty($indirectmanagerstoprocess)) {
                    if (!empty($managersql)) {
                        $managersql .= " OR ";
                    }

                    list($indirectinsql, $indirectparams) = $DB->get_in_or_equal($indirectmanagerstoprocess, SQL_PARAMS_NAMED);
                    $managersql .= "assignmenttypeid {$indirectinsql} AND includechildren = 1";
                    $params = array_merge($params, $indirectparams);
                }

                $sql = "UPDATE {prog} SET assignmentsdeferred = 1
                         WHERE id IN (SELECT programid
                                        FROM {prog_assignment}
                                       WHERE assignmenttype = :assignmenttypemanager
                                         AND ($managersql))";
                $DB->execute($sql, $params);
            }
        }

        if ($newassignment->positionid != $event->other['oldpositionid']) {
            $positionstoprocess = array();

            if ($newassignment->positionid) {
                $positionstoprocess[] = $newassignment->positionid;
            }

            if ($event->other['oldpositionid']) {
                $positionstoprocess[] = $event->other['oldpositionid'];
            }

            if (!empty($positionstoprocess)) {
                list($insql, $params) = $DB->get_in_or_equal($positionstoprocess, SQL_PARAMS_NAMED);
                $sql = "UPDATE {prog} SET assignmentsdeferred = 1
                         WHERE id IN (SELECT programid
                                        FROM {prog_assignment}
                                       WHERE assignmenttype = :assignmenttypeposition
                                         AND assignmenttypeid {$insql})";
                $params['assignmenttypeposition'] = ASSIGNTYPE_POSITION;
                $DB->execute($sql, $params);
            }
        }

        if ($newassignment->organisationid != $event->other['oldorganisationid']) {
            $organisationstoprocess = array();

            if ($newassignment->organisationid) {
                $organisationstoprocess[] = $newassignment->organisationid;
            }

            if ($event->other['oldorganisationid']) {
                $organisationstoprocess[] = $event->other['oldorganisationid'];
            }

            if (!empty($organisationstoprocess)) {
                list($insql, $params) = $DB->get_in_or_equal($organisationstoprocess, SQL_PARAMS_NAMED);
                $sql = "UPDATE {prog} SET assignmentsdeferred = 1
                         WHERE id IN (SELECT programid
                                        FROM {prog_assignment}
                                       WHERE assignmenttype = :assignmenttypeorganisation
                                         AND assignmenttypeid {$insql})";
                $params['assignmenttypeorganisation'] = ASSIGNTYPE_ORGANISATION;
                $DB->execute($sql, $params);
            }
        }

        return true;
    }
}
