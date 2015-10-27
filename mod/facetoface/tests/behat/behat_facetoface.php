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
 * @author Maria Torres <maria.torres@totaralms.com>
 * @package mod_facetoface
 */

// NOTE: no MOODLE_INTERNAL used, this file may be required by behat before including /config.php.
require_once(__DIR__ . '/../../../../lib/behat/behat_base.php');

use Behat\Behat\Context\Step\Given as Given;
use Behat\Gherkin\Node\TableNode as TableNode;

/**
 * Contains functions used by behat to test functionality.
 *
 * @package    mod_facetoface
 * @category   test
 */
class behat_facetoface extends behat_base {

    /**
     * Create a session in the future based on the current date.
     *
     * @Given /^I fill facetoface session with relative date in form data:$/
     * @param TableNode $data
     * @return array the list of actions to perform
     */
    public function i_fill_facetoface_session_with_relative_date_in_form_data(TableNode $data) {

        $dataclone = clone $data;
        $rows = $dataclone->getRows();

        // Setting the timezone. Don't know if there is a better way for this.
        date_default_timezone_set($rows[1][1]);

        // Get timestart and modify its current value.
        $timestartyear  = (!empty($rows[4][1]) ? $rows[4][1] . ' years' : '');
        $timestartmonth = (!empty($rows[3][1]) ? $rows[3][1] . ' months': '');
        $timestartday   = (!empty($rows[2][1]) ? $rows[2][1] . ' days': '');
        $timestarthour  = (!empty($rows[5][1]) ? $rows[5][1] . ' hours': '');
        $timestartmin   = (!empty($rows[6][1]) ? $rows[5][1] . ' minutes': '');

        // Get timestart and modify its current value.
        $timefinishyear  = (!empty($rows[9][1])  ? $rows[9][1]  . ' years' : '');
        $timefinishmonth = (!empty($rows[8][1])  ? $rows[8][1]  . ' months': '');
        $timefinishday   = (!empty($rows[7][1])  ? $rows[7][1]  . ' days': '');
        $timefinishhour  = (!empty($rows[10][1]) ? $rows[10][1] . ' hours': '');
        $timefinishmin   = (!empty($rows[11][1]) ? $rows[11][1] . ' minutes': '');

        $now = time();
        $newdate = strtotime("{$timestartmonth} {$timestartday} {$timestartyear} {$timestarthour} {$timestartmin}" , $now) ;
        $startdate = new DateTime(date('Y-m-d H:i' , $newdate));

        $newdate = strtotime("{$timefinishmonth} {$timefinishday} {$timefinishyear} {$timefinishhour} {$timefinishmin}" , $now) ;
        $finishdate = new DateTime(date('Y-m-d H:i' , $newdate));

        // Values for the minutes field should be multiple of 5 (from 00 to 55). So we need to fix these values.
        $startmin = $startdate->format("i");
        $minutes = (($startmin % 5 ) !== 0) ? floor($startmin / 5) * 5 + 5 : ($startmin / 5) * 5;
        $minutes = ($minutes > 55) ? 0 : $minutes;
        $startdate->setTime($startdate->format('H'), $minutes);

        $finishmin = $finishdate->format('i');
        $minutes = (($finishmin % 5 ) !== 0) ? floor($finishmin / 5) * 5 + 5 : ($finishmin / 5) * 5;
        $minutes = ($minutes > 55) ? 0 : $minutes;
        $finishdate->setTime($finishdate->format('H'), $minutes);

        // Replace values for timestart.
        $rows[2][1] = (int) $startdate->format('d');
        $rows[3][1] = (int) $startdate->format('m');
        $rows[4][1] = (int) $startdate->format('Y');
        $rows[5][1] = (int) $startdate->format('H');
        $rows[6][1] = (int) $startdate->format('i');

        // Replace values for timefinish.
        $rows[7][1]  = (int) $finishdate->format('d');
        $rows[8][1]  = (int) $finishdate->format('m');
        $rows[9][1]  = (int) $finishdate->format('Y');
        $rows[10][1] = (int) $finishdate->format('H');
        $rows[11][1] = (int) $finishdate->format('i');

        // Set the the rows back to data.
        $dataclone->setRows($rows);

        return array(
            new Given('I set the following fields to these values:', $dataclone),
        );
    }

    /**
     * Click on a selected link that is located in a table row.
     *
     * @Given /^I click on the link "([^"]*)" in row (\d+)$/
     */
    public function i_click_on_the_link_in_row($text, $row) {
        $xpath = "//table//tbody//tr[{$row}]//a[text()='{$text}']";
        $node = $this->find(
            'xpath',
            $xpath,
            new \Behat\Mink\Exception\ExpectationException('Could not find specific link "'.$text.'" in the row' . $row . $xpath, $this->getSession())
        );
        $node->click();
    }

    /**
     * Select to approve the given user.
     *
     * @Given /^I select to approve "([^"]*)"$/
     */
    public function i_select_to_approve($user) {
        return array(
            new Given('I click on "input[value=\'2\']" "css_element" in the "'.$user.'" "table_row"')
        );
    }
}
