<?php
/*
 * This file is part of Totara LMS
 *
 * Copyright (C) 2015 onwards Totara Learning Solutions LTD
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
 * facetoface module PHPUnit archive test class
 *
 * To test, run this from the command line from the $CFG->dirroot
 * vendor/bin/phpunit mod_facetoface_notifications_testcase mod/facetoface/tests/notifications_test.php
 *
 * @package    mod_facetoface
 * @subpackage phpunit
 * @author     Oleg Demeshev <oleg.demeshev@totaralms.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;

require_once($CFG->dirroot . '/mod/facetoface/lib.php');
require_once($CFG->dirroot . '/totara/hierarchy/prefix/position/lib.php');

class mod_facetoface_notifications_testcase extends advanced_testcase {

    /**
     * Intercept emails and stores them locally for later verification.
     */
    private $emailsink = NULL;


    /**
     * Original configuration value to enable sending emails.
     */
    private $cfgemail = NULL;

    /**
     * PhpUnit fixture method that runs before the test method executes.
     */
    public function setUp() {
        global $CFG;

        parent::setUp();

        $this->preventResetByRollback();
        $this->resetAfterTest();

        $this->emailsink = $this->redirectEmails();
        $this->assertTrue(phpunit_util::is_redirecting_phpmailer());

        $this->cfgemail = isset($CFG->noemailever) ? $CFG->noemailever : NULL;
        $CFG->noemailever = false;
    }

    public function test_cancellation_send_delete_session() {

        $session = $this->f2f_generate_data();

        // Call facetoface_delete_session function for session1.
        $this->emailsink = $this->redirectEmails();
        facetoface_delete_session($session);
        $this->emailsink->close();

        $emails = $this->get_emails();
        $this->assertCount(4, $emails, 'Wrong no of cancellation notifications sent out.');
    }

    public function test_cancellation_nonesend_delete_session() {

        $session = $this->f2f_generate_data(false);

        // Call facetoface_delete_session function for session1.
        $this->emailsink = $this->redirectEmails();
        facetoface_delete_session($session);
        $this->emailsink->close();

        $emails = $this->get_emails();
        $this->assertCount(0, $emails, 'Error: cancellation notifications should not be sent out.');
    }

    /**
     * Create course, users, face-to-face, session
     *
     * @param bool $future, time status: future or past, to test cancellation notifications
     * @return object $session
     */
    public function f2f_generate_data($future = true) {
        global $DB;

        $this->resetAfterTest(true);
        $this->setAdminUser();

        $teacher1 = $this->getDataGenerator()->create_user();
        $student1 = $this->getDataGenerator()->create_user();
        $student2 = $this->getDataGenerator()->create_user();
        $manager = $this->getDataGenerator()->create_user();

        $assignment = new position_assignment(array('userid' => $student1->id, 'type' => POSITION_TYPE_PRIMARY));
        $assignment->managerid = $manager->id;
        assign_user_position($assignment, true);

        $assignment = new position_assignment(array('userid' => $student2->id, 'type' => POSITION_TYPE_PRIMARY));
        $assignment->managerid = $manager->id;
        assign_user_position($assignment, true);

        $course = $this->getDataGenerator()->create_course();

        $teacherrole = $DB->get_record('role', array('shortname' => 'editingteacher'));
        $studentrole = $DB->get_record('role', array('shortname' => 'student'));

        $this->getDataGenerator()->enrol_user($teacher1->id, $course->id, $teacherrole->id);
        $this->getDataGenerator()->enrol_user($student1->id, $course->id, $studentrole->id);
        $this->getDataGenerator()->enrol_user($student2->id, $course->id, $studentrole->id);

        $facetofacegenerator = $this->getDataGenerator()->get_plugin_generator('mod_facetoface');

        $facetofacedata = array(
            'name' => 'facetoface',
            'course' => $course->id
        );
        $facetoface = $facetofacegenerator->create_instance($facetofacedata);

        $sessiondate = new stdClass();
        $sessiondate->sessiontimezone = 'Pacific/Auckland';
        if ($future) {
            $sessiondate->timestart = time() + WEEKSECS;
            $sessiondate->timefinish = time() + WEEKSECS + 60;
        } else {
            $sessiondate->timestart = time() - WEEKSECS;
            $sessiondate->timefinish = time() - WEEKSECS + 60;
        }

        $sessiondata = array(
            'facetoface' => $facetoface->id,
            'capacity' => 3,
            'allowoverbook' => 1,
            'sessiondates' => array($sessiondate),
            'datetimeknown' => '1',
            'mincapacity' => '1',
            'cutoff' => DAYSECS - 60
        );

        $sessionid = $facetofacegenerator->add_session($sessiondata);
        $session = $DB->get_record('facetoface_sessions', array('id' => $sessionid));
        $session->sessiondates = facetoface_get_session_dates($session->id);

        $discountcode = 'GET15OFF';
        $notificationtype = 1;
        $statuscode = MDL_F2F_STATUS_REQUESTED;

        // Signup user1.
        $this->emailsink = $this->redirectEmails();
        $this->setUser($student1);
        facetoface_user_signup($session, $facetoface, $course, $discountcode, $notificationtype, $statuscode);
        $this->emailsink->close();

        // Signup user2.
        $this->emailsink = $this->redirectEmails();
        $this->setUser($student2);
        facetoface_user_signup($session, $facetoface, $course, $discountcode, $notificationtype, $statuscode);
        $this->emailsink->close();

        return $session;
    }

    /**
     * Returns the email in the sink.
     *
     * @return array $emails a list of email bodies.
     */
    private function get_emails() {
        $emails = array();
        foreach ($this->emailsink->get_messages() as $email) {
            $emails[] = trim($email->body);
        }

        return $emails;
    }

    /**
     * PhpUnit fixture method that runs after the test method executes.
     */
    public function tearDown() {
        global $CFG;
        if (isset($this->cfgemail)) {
            $CFG->noemailever = $this->cfgemail;
            unset($this->cfgemail);
        }

        $this->emailsink->close();
        unset($this->emailsink);

        parent::tearDown();
    }

}