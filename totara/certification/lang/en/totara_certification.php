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
 * @author Russell England <russell.england@catalyst-eu.net>
 * @package totara
 * @subpackage certification
 */

$string['activeperiod'] = 'Active Period';
$string['addcertifprogramcontent'] = 'Add certification program content';
$string['addcertifprogramcontent_help'] = 'By adding sets of course you can build up the learning path of the certification program.
Once sets are added the relationships between them can be defined. Sets are created from manually adding courses.

Once a number of sets have been created, set dividers are employed to allow the creation of sequences (i.e. dependencies) between each set.
An example program with four course sets defined could have dependencies as follows:

*   From set one the learner must complete one course (courseA or courseB) before proceeding to set two.
*   From set two the learner must complete all courses (courseC and courseD and courseE) before proceeding to set three or set four.
*   From set three the learner must complete one course (courseE) or all courses from set four (courseF and courseG).

Once the learning path is completed, the learner has finished the certification program.';
$string['addnewcertification'] = 'Add new certification';
$string['availablefrom'] = 'Available From';
$string['availableuntil'] = 'Available Until';
$string['beforewindowduetoclose'] = 'Before window is due to close';
$string['cancelcertificationmanagement'] = 'Clear unsaved changes';
$string['certassignmentduedates'] = 'Certification assignment due dates';
$string['certcomplete'] = 'Your certification is complete.';
$string['certexpired'] = 'Your certification has expired, you need to complete the original certification';
$string['certifcategories'] = 'Certification Categories';
$string['certification'] = 'Certification';
$string['certification:configurecertification'] = 'Configure certification';
$string['certification:configuredetails'] = 'Configure certification details';
$string['certification:createcertification'] = 'Create certification';
$string['certification:deletecertification'] = 'Delete certification';
$string['certification:viewhiddencertifications'] = 'View hidden certifications';
$string['certificationcontent'] = 'Define the program content by adding sets of courses';
$string['certificationcreatesuccess'] = 'Certification created sucessfully';
$string['certificationdeletesuccess'] = 'Certification "{$a}" deleted sucessfully';
$string['certificationdetailssaved'] = 'Certification details saved';
$string['certificationhistory'] = 'Previous Certification';
$string['certifications'] = 'Certifications';
$string['certificationsdisabled'] = 'Certifications are not enabled on this site';
$string['certificationsinthiscategory'] = 'Certifications in this category';
$string['certifdeletefail'] = 'Could not delete certification "{$a}"';
$string['certified'] = 'Certified';
$string['certifname'] = 'Certification Name';
$string['certifprog'] = 'Certification program';
$string['certifprogramcreatesuccess'] = 'Certification program creation successful';
$string['certifsmovedout'] = 'Certifications moved out from {$a}';
$string['certinprogress'] = 'Your certification is in progress';
$string['checkcertificationdelete'] = 'Are you sure you want to delete this certification and all its related items?';
$string['competency'] = 'Competency';
$string['comptype'] = 'Certification type';
$string['comptype_help'] = 'Select required Learning Component (currently just program)';
$string['comptypenotimplemented'] = 'Certification type not implemented';
$string['confirmchanges'] = 'Confirm certification changes';
$string['course'] = 'Course';
$string['createnewcertification'] = 'Create new certification';
$string['createnewcertifprog'] = 'Create new certification program';
$string['days'] = 'Day(s)';
$string['defaultcertprogramfullname'] = 'Certification program fullname 101';
$string['defaultcertprogramshortname'] = 'CP101';
$string['editcertif'] = 'Edit certification details';
$string['editcertification'] = 'Edit certification';
$string['editdetailsactive'] = 'Certification is active for';
$string['editdetailsactive_help'] = 'The period the certification is active for, before it expires';
$string['editdetailsactivep'] = 'Active Period';
$string['editdetailsdesc'] = 'Define the recertification details rules for all learners assigned to the certification';
$string['editdetailshdr'] = 'Recertification Details';
$string['editdetailsminimumactive'] = 'Minimum active period';
$string['editdetailsminimumactive_help'] = 'When "Use fixed expiry date" is set, this option allows you to specify the minimum length of time that the certification will be guaranteed to be active. If a user completes their certification and there is less than this amount of time until it would be due to expire, the expiry date is pushed forward by another whole "active period".

For example:

* if a user was newly assigned to a certification in July, the active period is 1 year, the window opens 2 months before expiring, the minimum active period is 6 months and they have an assignment due date of 1 December, then when they complete in August, their expiry date will be set to 1 December of the following year (actual active period of about 16 months).
* if a user in the same certification completes their recertification in November while the window is open, then their certification would be set to expire on 1 December of the following year (actual active period of about 13 months).
* if a user in the same certification failed to recertify on time and subsequently completed the primary certification in April, then their certification would be set to expire on 1 December of that year (actual active period of about 8 months).';
$string['editdetailsrccmpl'] = 'Use certification completion date';
$string['editdetailsrcexp'] = 'Use certification expiry date';
$string['editdetailsrcfixed'] = 'Use fixed expiry date';
$string['editdetailsrcopt'] = 'Recertification date';
$string['editdetailsrcopt_help'] = 'This setting determines how the expiry date is calculated when a user completes primary certification or recertifies.

<b>Use certification completion date:</b> The active period is added to the completion date. Over the span of several recertifications, this option can cause the expiry date to "drift" backwards (when completed early) and forwards (when completed overdue).

<b>Use certification expiry date:</b> Uses the last expiry date to calculate the next. The first time this is calculated, it adds the active period to the assignment due date if there is one, otherwise it uses the date the certification was completed. If the user is overdue or expired, it adds the "active period" to the date the primary certification was just completed (as if the user was newly assigned), so that certifications are active for a minimum of the active period. Over the span of several recertifications, this option can cause the expiry date to "drift" forwards (when completed overdue) but not backwards.

<b>Use fixed expiry date:</b> This option causes the expiry time to be calculated based on the specified assignment due date (if available, otherwise the <b>first</b> completion date), and subsequent completion expiry dates will be calculated using that same base date, regardless of whether they are late or early. The active period is repeatedly added to the base date until the first future date is discovered. The "Minimum active period" is available only with this setting, and must be at least as big as the "Recertification Window" (see the "Minimum active period" help for more information). Over the span of several recertifications, this option will prevent the expiry date from "drifting" forwards or backwards.';
$string['editdetailsrcwin'] = 'Recertification Window';
$string['editdetailsvalid'] = 'Define how long the certification should be valid once complete';
$string['editdetailswindow'] = 'Period window opens before expiration';
$string['editdetailswindow_help'] = 'The period before certification expires that a learner can start recertifying. When the window opens, all course and activity completion data will be reset, including courses and activities completed by RPL.';
$string['error:categoryidwasincorrect'] = 'Category ID was incorrect';
$string['error:categorymustbespecified'] = 'Category must be specified';
$string['error:certifsnotmoved'] = 'Error, certifications not moved from {$a}!';
$string['error:histalreadyexists'] = 'Certification history already exists certifid={$a->certifid}, timeexpires={$a->timeexpires}';
$string['error:incorrectcertifid'] = 'Incorrect certification ID certifid={$a}';
$string['error:incorrectid'] = 'Incorrect certification completion ID or user ID';
$string['error:invalidaction'] = 'Invalid action: {$a}';
$string['error:minimumactiveperiod'] = 'Active period must be greater than the recertification window period';
$string['error:minimumactiveperiodactive'] = 'Minimum active period cannot be greater than the active period';
$string['error:minimumactiveperiodwindow'] = 'Minimum active period cannot be less than the recertification window period';
$string['error:minimumwindowperiod'] = 'Recertification window period must be at least {$a}';
$string['error:missingprogcompletion'] = 'Missing program completion record for certifid={$a->certifid} userid={$a->userid}';
$string['error:mustbepositive'] = 'Number must be positive';
$string['error:nullactiveperiod'] = 'Recertification active period is not set';
$string['error:nullwindowperiod'] = 'Recertification window period is not set';
$string['error:useralreadyassigned'] = 'user already assigned for certifid={$a->certifid} userid={$a->userid}';
$string['eventupdated'] = 'Certification\'s setting updated';
$string['findcertifications'] = 'Find certifications';
$string['learningcomptype'] = 'Learning component';
$string['legend:recertfailrecertmessage'] = 'FAILURE TO RECERTIFY MESSAGE';
$string['legend:recertwindowdueclosemessage'] = 'RECERTIFICATION WINDOW DUE TO CLOSE MESSAGE';
$string['legend:recertwindowopenmessage'] = 'RECERTIFICATION WINDOW OPEN MESSAGE';
$string['managecertifications'] = 'Manage certifications';
$string['managecertifsinthiscat'] = 'Manage certifications in this category';
$string['months'] = 'Month(s)';
$string['moveselectedcertificationsto'] = 'Move selected certifications to...';
$string['nocertifdetailsfound'] = 'No certification details setup';
$string['nocertifications'] = 'No certifications';
$string['notcertified'] = 'Not certified';
$string['oricertpath'] = 'Original certification path';
$string['oricertpathdesc'] = 'Define the content required for the original certification path.';
$string['pluginname'] = 'Certification';
$string['prog_recert_failrecert_message'] = 'Program recertification failure message';
$string['prog_recert_windowdueclose_message'] = 'Program Recertification Window due close message';
$string['prog_recert_windowopen_message'] = 'Program recertification window open message';
$string['program'] = 'Program';
$string['programenrolledincohort'] = 'Certification is enrolled in by audience';
$string['programexpandlink'] = 'Certification Name (expanding details)';
$string['programname'] = 'Certification Name';
$string['programshortname'] = 'Certification Short Name';
$string['programidnumber'] = 'Certification ID number';
$string['programid'] = 'Certification ID';
$string['programsummary'] = 'Certification Summary';
$string['programvisible'] = 'Certification Visible';
$string['prognamelinkedicon'] = 'Certification Name and Linked Icon';
$string['recertfailrecert'] = 'Failure to recertify';
$string['recertfailrecertmessage'] = 'Failure to recertify message';
$string['recertfailrecertmessage_help'] = 'This message will be sent when a recertification period has expired and the learner will need to repeat the original certification.';
$string['recertification'] = 'Recertification';
$string['recertpath'] = 'Recertification path';
$string['recertpathdesc'] = 'Define the recertification path';
$string['recertwindowdueclose'] = 'Recertification window due to close';
$string['recertwindowdueclosemessage'] = 'Recertification window due to close message';
$string['recertwindowdueclosemessage_help'] = 'This message will be sent when a recertification period is about to expire.';
$string['recertwindowexpiredate'] = ' Your certification will expire on {$a}';
$string['recertwindowopen'] = 'Recertification window open';
$string['recertwindowopendate'] = ' The recertification window will open on {$a}';
$string['recertwindowopenmessage'] = 'Recertification window open message';
$string['recertwindowopenmessage_help'] = 'This message will be sent when a learner has entered the period when they can recertify';
$string['renewalstatus_dueforrenewal'] = 'Due for renewal';
$string['renewalstatus_expired'] = 'Renewal expired';
$string['renewalstatus_notdue'] = 'Not due for renewal';
$string['sameascert'] = 'Use the existing certification content';
$string['saveallchanges'] = 'Save all changes';
$string['searchcertifications'] = 'Search certifications';
$string['status_assigned'] = 'Assigned';
$string['status_certified'] = 'Certified';
$string['status_completed'] = 'Completed';
$string['status_expired'] = 'Expired';
$string['status_inprogress'] = 'In progress';
$string['status_notcertified'] = 'Not certified';
$string['status_unset'] = 'Unset';
$string['timeallowance'] = 'Minimum time required for recertification is {$a->timestring}';
$string['tosaveall'] = 'To save all changes, click \'Save all changes\'. To edit click \'Edit certification details\'. Saving changes cannot be undone.';
$string['type_competency'] = 'Competency';
$string['type_course'] = 'Course';
$string['type_program'] = 'Program';
$string['type_unset'] = 'Unset';
$string['unset'] = 'Unset';
$string['updatecertificationstask'] = 'Update certifications';
$string['viewallcertifications'] = 'View all certifications';
$string['viewcertification'] = 'View certification';
$string['weeks'] = 'Week(s)';
$string['windowopen'] = 'Open';
$string['windowopenin1day'] = 'Opens in 1 day';
$string['windowopeninxdays'] = 'Opens in {$a} days';
$string['windowperiod'] = 'Window Period';
$string['years'] = 'Year(s)';
$string['youhaveunsavedchanges'] = 'You have unsaved changes.';
$string['youareassigned'] = 'You are assigned to this certification';
