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
 * @package modules
 * @subpackage facetoface
 */

define('AJAX_SCRIPT', true);

require_once('../../config.php');
require_once($CFG->dirroot . '/mod/facetoface/lib.php');
require_once($CFG->dirroot . '/mod/facetoface/signup_tsandcs_form.php');

$s = required_param('s', PARAM_INT); // Facetoface session ID.
$backtoallsessions = optional_param('backtoallsessions', 0, PARAM_INT);

if (!$session = facetoface_get_session($s)) {
    print_error('error:incorrectcoursemodulesession', 'facetoface');
}
if (!$facetoface = $DB->get_record('facetoface', array('id' => $session->facetoface))) {
    print_error('error:incorrectfacetofaceid', 'facetoface');
}
if (!$course = $DB->get_record('course', array('id' => $facetoface->course))) {
    print_error('error:coursemisconfigured', 'facetoface');
}
if (!$cm = get_coursemodule_from_instance("facetoface", $facetoface->id, $course->id)) {
    print_error('error:incorrectcoursemoduleid', 'facetoface');
}
$context = context_module::instance($cm->id);

require_login();

if (!$facetoface->approvalreqd or !$session->selfapproval) {
    // This should not happen unless there is a concurrent change of settings.
    print_error('error');
}

if (is_enrolled($context, null, '', true)) {
    // User is already enrolled, let them view the text again.
    require_login($course, true, $cm);
    require_capability('mod/facetoface:view', $context);

} else {
    // Can user self enrol via any instance?
    if (!totara_course_is_viewable($course->id)) {
        print_error('error');
    }
    /** @var enrol_totara_facetoface_plugin $enrol */
    $enrol = enrol_get_plugin('totara_facetoface');
    $allow = false;
    $instances = $DB->get_records('enrol', array('courseid' => $course->id, 'enrol' => 'totara_facetoface'));
    foreach ($instances as $instance) {
        if ($enrol->can_self_enrol($instance, true) === true) {
            $allow = true;
            break;
        }
    }
    if (!$allow) {
        print_error('cannotenrol', 'enrol_totara_facetoface');
    }
}

$pagetitle = format_string($facetoface->name);

$mform = new signup_tsandcs_form(null, array('tsandcs' => $facetoface->selfapprovaltandc, 's' => $s));

// This should be json_encoded, but for now we need to use html content
// type to not break $.get().
header('Content-type: text/html; charset=utf-8');
$mform->display();
