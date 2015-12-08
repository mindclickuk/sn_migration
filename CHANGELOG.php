<?php
/*

Totara LMS Changelog

Release 2.9.1.1 (9th December 2015):
====================================


Bug fixes:

    TL-8096        Fixed course module completion calculation

                   This fixes a regression introduced by TL-6981 in 2.9.1, 2.7.9, 2.6.26, and
                   2.5.33 in which the calculation of course module completion would lead to
                   all activities being marked complete incorrectly for a user.
                   The problem occurs when the user completes the first activity in the
                   course. It occurs if the user manually marks themselves complete, achieves
                   the completion criteria (with the exception of "Student must view this
                   activity to complete it"), or is marked complete by a trainer. The user
                   must then log out and log back in again in order to see the problem.
                   The problem will not occur if it is not the first activity in the course.
                   When this occurs all activities in the course will be marked complete,
                   regardless of which section or order they are within the course and
                   regardless of whether they are required for course completion or not.


Release 2.9.1 (16th November 2015):
==================================================


Security issues:

    TL-7886        Fixed access checks for the position assignment AJAX script
    TL-7829        Removed reliance on url parameter for choosing table

                   The script for getting the positions and organisations to assign to a
                   program relied on a url parameter to choose which table to access. The
                   table is now chosen according to the type of hierarchy that the query is
                   for.

    MoodleHQ       Security fixes from MoodleHQ http://docs.moodle.org/dev/Moodle_2.9.3_release_notes

                   Security related issues:
                   * MDL-51861 enrol: Don't get all parts in get_enrolled_users with groups
                   * MDL-51684 badges: Make sure 'moodle/badges:viewbadges' is respected
                   * MDL-51569 mod_choice: validate user actions and availability
                   * MDL-51091 core_registration: session key check in registration.
                   * MDL-51000 editor_atto: No autosave for guests
                   * MDL-50837 mod_scorm: Fix availability checks
                   * MDL-50426 messaging: Fix permissions checks when sending messages
                   * MDL-49940 mod_survey: Fix XSS
                   * MDL-48109 mod_lesson: prevent CSRF on password protected lesson


Improvements:

    TL-6282        Improved handling and displaying of the user's name in Core dialogs
    TL-6529        Added the manager's email as a selectable column for reports that include user's position fields
    TL-6657        Added actual due dates to program Assignments and audience Enrolled Learning tabs

                   The Assignments tab in programs and certifications and the Enrolled
                   Learning tab in audiences now include a column "Actual due date". This new
                   column shows the due date that the user will see. For group assignments
                   (such as audiences or organisations), clicking the "View dates" link will
                   show a popup with a list of all assigned users relating to that group
                   assignment. The help popup for the "Actual due date" column explains why
                   assignment due dates may be different from the actual due dates. After
                   upgrading, the "Actual due date" field can be manually added to the
                   "Audience: Enrolled Learning" embedded report, or you can reset it to the
                   default to have it automatically added.

    TL-7183        Trigger updates to program user assignments when changing assignments via the audience Enrolled Learning tab

                   When you make a change in an audience's Enrolled Learning tab, it will
                   immediately trigger an update of program and certification user
                   assignments. If there are less than 200 total users involved in the program
                   then the users will be processed immediately, otherwise the update will be
                   deferred. By default, deferred program user assignments are processed the
                   next time cron runs. This patch makes the behaviour consistent with making
                   changes in a program's Assignments tab.

    TL-7256        Mark programs for deferred user assignment update when assignment membership changes

                   This patch includes several improvements which cause program and
                   certification memberships to be updated sooner:
                   * When audience membership changes, either by a user manually editing an
                   audience or when a dynamic audience's membership is automatically updated,
                   related programs and certifications will be marked as having user
                   assignment changes deferred.
                   * When a user's assigned position, organisation or manager change, programs
                   and certifications related to the old and new positions, organisations and
                   management hierarchy are marked as having user assignment changes
                   deferred.

                   With this change in place, all changes to program membership should now be
                   detected as they occur and are either processed immediately or by the
                   "Deferred program assignments changes" scheduled task. As such, we
                   recommend setting the related tasks to their default schedules: "Deferred
                   program assignments changes" can be run every time cron runs, while
                   "Program user assignments" only needs to be run once per day.

    TL-7575        Removed Totara menu from the print layout
    TL-7741        Removed HTML table behind the Weekend Days setting
    TL-7745        Added labels to settings on Site administration > Front page > Front page settings
    TL-7748        Improved Accessibility when editing the Site administration > Plugins > Activity modules > Quiz Settings
    TL-7750        Improved layout of "User Fullname (with links to learning components)" Report builder column
    TL-7792        Added settings to enforce https access and prevent embedding of content in external Flash and PDF files
    TL-7813        Reduced events triggered when program user assignments are updated

                   Some events were being triggered unnecessarily when updating program and
                   certification user assignments. They will now only be triggered if it is
                   certain that there are changes that need to be signalled.

                   Please remember that user_assignments_task by default is scheduled to
                   execute just once per day, whereas assignments_deferred_task is designed to
                   be run every time cron runs.

    TL-7824        Moved program user assignment deferred flag reset to start of function

                   If changes are made to a program's assignments while the function is
                   running in cron, those changes will be processed the next time the deferred program
                   assignments scheduled task runs (default: Every cron run), rather than having to
                   wait until  program user assignments scheduled task runs (default: Nightly) or
                   another change is made.

    TL-7878        Added a page title when adding and removing Feedback360 requests with javascript turned off


Bug fixes:

    TL-6936        Face-to-face direct enrolment plugin allows users to signup to more then one Face-to-face.

                   Users can now sign up to one session per Face-to-face in the course via the
                   Face-to-face direct enrolment plugin. If at least one of the session
                   signups was successful then user will be enrolled to the course.

                   If all successful signups require managers approval then course enrolment
                   will be pending. T&Cs when enabled are required and will be checked before
                   any signups or enrolments are processed.

    TL-6957        Display correct due date value in the upcoming certifications block
    TL-6981        Reaggregate course completion when activity completion criteria are unlocked without deletion

                   Previously, course completion status was only reaggregated if "unlock with
                   delete" was used. If "unlock without delete" was used, it was possible that
                   users who meet the new completion criteria were not marked complete, and
                   this would not be fixed by any cron task. This could lead to users being
                   stuck with an incomplete status. Now, the records will be marked for
                   reaggregation and will be processed by the completion cron task.

    TL-7273        Corrected the help text for Report builder simple select filters

                   Filters that use a drop-down select with no additional options such as 'not
                   equal to' now have correct corresponding help text, rather than referring
                   to additional options that do not exist.

    TL-7437        Switched the badges backpack URL to use HTTPS
    TL-7514        Fixed the display order of Face-to-face sessions for the Face-to-face direct enrolment plugin

                   Sessions will now be displayed in order of their start date/times instead
                   of when they were created

    TL-7559        Enabled the transfer of position and organistion custom fields for the database source of HR Sync
    TL-7562        Fixed strings for audience rules based on course and program completion
    TL-7594        Fixed users booked on a Face-to-face session with no dates being incorrectly removed when another user cancels their booking
    TL-7602        Re-enabled the Save and Cancel buttons for the Face-to-face take attendance tab

                   Save and Cancel buttons present in previous versions have been reintroduced
                   to the Face-to-face take attendance tab. Save must be clicked in order to
                   update attendance records.

    TL-7611        Fixed the handling of username and suspended fields for external database sources in HR Import
    TL-7644        Corrected the amount of white space in the 'recordoflearning' language string
    TL-7659        Prevented cancellation notifications being sent to users booked in completed Face-to-face sessions when the course is deleted
    TL-7660        Fixed the behaviour of pagination on hierarchy index pages

                   When viewing Positions, Organisations, Competencies or Goals within a
                   framework, pagination was not working correctly and instead was displaying
                   all of the items even though the paging bar was displaying the correct
                   number of pages.

    TL-7664        Fixed dynamic audience rules based upon checkbox position custom fields
    TL-7675        Fixed the display of an aggregation warning for Report builder columns

                   The warning that column aggregation options may not be compatible with
                   reports that use aggregation internally is now shown only for reports that
                   actually use aggregation internally.

    TL-7676        Fixed the display of duplicate categories in pie charts
    TL-7686        Fixed URL validation when adding new links to the quicklinks block
    TL-7695        Re-aggregate when course completion criteria is changed without deletion

                   When changing course completion criteria, and unlocking without deleting
                   existing completion data, re-aggregation was not being performed. Now,
                   users who are assigned but not complete and match the new criteria will be
                   marked complete after cron re-aggregates them. To fix any users affected by
                   this problem, an upgrade script will mark all incomplete users in all
                   courses for re-aggregation, which will be performed by cron, and may take a
                   while to process on larger sites.

    TL-7698        Fixed the handling of position and organisation 'Text area' custom fields within HR Import
    TL-7711        Fixed the "duedate(extra info)" column for Report builder export to pdf
    TL-7724        Fixed an error when adding audience visibility during program creation.

                   A user who was assigned the site manager role within a category context
                   would previously be presented with an error when giving audiences
                   visibility during program creation. This error no longer appears.

    TL-7732        Allow HR import to set posenddate value as blank when posstartdate is set
    TL-7769        The Report builder "Manager's name" filter now counts users without a manager as "empty"
    TL-7770        Fixed date validation for Face-to-face sessions when removing dates or wait-listing a session
    TL-7783        Fixed the ordering of the Face-to-face waitlist

                   Previously when a user cancelled an overbooked session the Face-to-face
                   replaced them with a user from the waitlist based off the user's names, now
                   the replacement is decided based off their signup time.

    TL-7784        Fixed the help text for Face-to-face 'minimum capacity' setting
    TL-7789        Fixed the formatting of the Face-to-face intro page
    TL-7821        Fixed a Totara Connect upgrade step that introduced a non-existent local plugin
    TL-7833        Fixed cron failure when sending Face-to-face notifications

                   When scheduled Face-to-face notifications were being sent out, the cron
                   task would potentially fail if notifications were going to users who had
                   their session bookings approved by a manager. This has now been fixed,
                   notifications go out as normal, and cron is not disrupted.

    TL-7836        Ensured images are restricted by their assigned widths

                   If an image is resized from its native dimensions and then displayed,
                   Internet Explorer would display the image at its native size, and not the
                   size that had been requested.

    TL-7844        Grader report now scrolls when it is too wide for the screen
    TL-7849        Removed reports and saved searches from the report table block when users do not have access
    TL-7851        Fixed the display of the "duedates" column for program and certification overview Report builder reports
    TL-7876        Stopped the incorrect archiving of facetoface sessions without dates

                   Previously if a user was waitlisted on a Face-to-face session which had no
                   dates set, in a Certification Course. When the user's recertification
                   window opened, the signup would be marked as archived, meaning it would no
                   longer count towards course completion.

    TL-7881        Recreate course completion records when activity criteria are reset with deletion

                   Course completion records for users who were not complete according to the
                   new criteria were not being recreated immediately. Although the records
                   were being created when the completion cron task was run or when a user's
                   status in the course changed, it was possible that some unexpected
                   behaviour could have occurred due to the missing records.

    TL-7883        Fixed date handling on the Face-to-face block calendar page
    TL-7909        Make sure url params are passed when using report builder toolbar search
    TL-7921        Fixed regression with media playback when request_order="GPC" in PHP.ini


Release 2.9.0 (3rd November 2015):
==================================

New features:

    TL-2250        New options to turn off extension requests for programs and certifications

                   It is now possible to disable program/certification extension requests both
                   for the whole system and for individual instances via the edit details page.

    TL-2565        New "Fixed date" custom user profile field type

                   A fixed date new custom user profile field type has been added.
                   This new field type is designed to store absolute dates for which
                   timezones are irrelevant and not applied.
                   An example of where this field is applicable is your birthday.
                   Regardless of your location you birthday is the same.

                   Data for this date field is stored as midday UTC for the selected date.

    TL-7098        Dynamic audience rules for the new fixed date custom user profile field

                   These rules include being able to define an audience according to their
                   custom profile field date being before or after a fixed date.
                   There are also duration-based rules, meaning they can be set according to
                   the profile dates being within or before a previous number of days, as well
                   as within or after an upcoming number of days.

                   Notes:
                      * With duration-based rules audiences will be updated at midday UTC.

    TL-4485        New personal goal types with custom fields

                   Personal goal types can now be defined and custom fields added to them.
                   Preexisting goal types have been renamed to company goal types and continue to
                   function as they did previously.
                   Personal goal types differ from company goal types in that the custom field
                   values entered for a personal goal will be associated with the user whom
                   the personal goal belongs to.

                   Thanks to Ryan Lafferty at Learning Pool for the contribution.

    TL-5094        Support for anonymous 360 feedback

                   We have added a new "Anonymous" setting when creating or editing a
                   360 Feedback.
                   Enabling this hides the name of responders to the 360 Feedback.
                   Responders can see whether a 360 Feedback is anonymous in the header of the
                   response page, along with the number of requests sent.
                   This setting does restrict some functionality to maintain anonymity:

                      * When enabled, requests for feedback can only be added, they can not be
                        cancelled. This is to stop users from potentially cancelling all
                        requests in order to figure out who has replied.
                      * Reminders can still be sent but who will receive them will no
                        longer be displayed to you.

                   While we have endeavoured to enforce anonymity please be aware that
                   responders are still recorded and there are ways to get this information
                   out of the system, such as:
                      * Site logs.
                      * Malicious code written to reveal it.
                      * Direct database investigation.
                      * Activity logs
                      * Blocks displaying Recently logged in users

    TL-5097        New options to disable changes in membership for dynamic audiences

                   With this feature you can now control if users are automatically added or
                   removed from a dynamic audience's membership when they meet, or no longer
                   meet, the criteria defined by the rule set.
                   This is facilitated with a new setting "Automatically update membership"
                   which can be found on the rule sets tab when editing a dynamic audience.
                   This new setting has two options, both of which are enabled by default:

                      * Make a user a member when they meet rule sets criteria
                      * Remove a user's membership when they no longer meet the rule sets
                        criteria

                   Toggling these settings allows you to prevent new members being added to
                   the audience and/or prevent users from being removed from the audience.

    TL-5818        Report builder reports can now be cloned

                   Added a new action that clones reports to the Report builder manage
                   reports page. Cloning a report creates a full copy of the report
                   and its columns, filters and settings. It does not copy any scheduling
                   or caching that has been set up for the report.
                   Both user created and embedded reports can be cloned.

                   In order to clone a report the user must have the
                   totara/reportbuilder:managereports capability.

    TL-6023        New program completion block

                   A new program completion block has been added in this release that lists
                   one or  more selected programs and the users completion status for each.
                   If no programs have been selected the block is not displayed.

                   Thanks to Eugene Venter at Catalyst NZ for the contribution

    TL-6525        New report table block

                   Added new block that displays tabular data belonging to a selected Report
                   builder report.
                   Optionally a saved search for the report can also be selected to limit the
                   data shown in the block.

                   Notes:
                      * Backward incompatibility of saved searches with third party filters
                        might occur. If saved searches do not work with your third party
                        filters, please contact the developer of the filters to update them.
                      * Only user created reports can be selected. Embedded reports cannot
                        be used within this block at present.

                   Thanks to Dennis Heany at Learning Pool for the contribution.

    TL-6621        Report Builder export is now pluggable

                   Reportbuilder export code was abstracted into a new plugin type 'tabexport'
                   located in folder '/core/tabexport'.
                   Tabular export plugins may now have settings and administrator can disable
                   individual export plugins.
                   New plugins have to include a class named '\tabexport_xyz\writer' that extend
                   base class '\totara_core\tabexport_writer'.
                   The base class contains a basic developer documentation, included plugins
                   can be used as examples.

    TL-6684        Added new global report restrictions

                   Global report restrictions allows rules to be applied to a report
                   restricting the results to those belonging to the users you are allowed to
                   view.
                   This allows you to define relationships between groups of users limiting
                   the information a user can see to just information belonging to the
                   permitted group or groups of users.

                   Notes:
                      * All users including the administrator can be restricted.
                      * Global report restrictions are only supported by report sources where
                        data can be seen as owned by one or more users.
                      * There are internal limitations for some database backends. For example
                        MySQL is limited to 61 tables in one join which may limit the maximum
                        number of items in each restriction.
                      * Active restrictions may impact the performance of reports.
                      * Report caching is not compatible with Global Report Restrictions and
                        is ignored when restrictions are active.

                   Thanks to Maccabi Healthcare Services for funding the development of this
                   feature.

    TL-6942        Define and use different flavours of Totara during installation and upgrade

                   A new feature set including plugin type has been added called Flavours.
                   Flavours can change default settings, the sites actual settings, and force
                   settings into a specific state.
                   During installation and upgrade the selected Flavour is applied allowing it
                   to control which settings get turned on or off.
                   It is also given the opportunity to execute code post installation or
                   upgrade.

                   Notes:
                      * Sites that do not use a specific flavour will default to the
                        Enterprise flavour that ships with Totara.
                        This flavour does not make any configuration changes.
                      * This feature was added for the benefit of Totara Cloud to allow us to
                        control cloud functionality. It is not used by default by Totara and
                        provides no new functionality

    TL-7021        Added a new setting to the advanced features page that enables/disables Competencies
    TL-7105        Added optional support for producing PDF snapshots of Appraisals via wkhtmltopdf executable
    TL-7246        Totara Connect Client

                   Totara Connect makes it possible to connect one or more Totara LMS or
                   Totara Social installations to a master Totara LMS installation.
                   This connection allows for users, and audiences to be synchronised from the
                   master to all connected client sites.
                   Synchronised users can move between the connected sites with ease
                   thanks to the single sign on system accompanying Totara Connect.


Improvements:

    TL-2368        Capability to manage reminders added to courses

                   A new capability moodle/course:managereminders has been created to allow
                   fine grained control over which roles can manage reminders within a
                   course.
                   Prior to this capability the moodle/course:update capability was used.
                   Now both capabilities are required.
                   Only site managers are given this capability by default.

    TL-5020        Change of 'from' email address when sending emails

                   Now all Totara messages (Email and alerts) use the system wide
                   "noreply" address as the "From" address.
                   This can be configured by the admin via 'Site administration > Plugins >
                   Message outputs > Email > No reply address'.

                   In the case of Facetoface, where there is another setting in 'Site
                   administration > Plugins > Activity modules > Facetoface > Sender From' the
                   system will send Facetoface messages from that address if it is set, or from
                   the no-Reply address otherwise.

    TL-5088        Logo now scales for smaller screens

                   When using the Custom Totara Responsive theme with a custom logo uploaded,
                   it now scales on smaller screens

    TL-5239        Added several new columns to the site log report source

                   This change introduced several new columns to the site log report source:

                      * A new column "Event name with link"
                      * A new column "Context"
                      * A new column "Component"
                      * A new filter "Event name"

                   This now facilitates filtering by event name (for example this report can
                   can show only "Course Completed" events) as well as providing a column that
                   links to event source (corresponding course, report, user, etc).

    TL-5356        Added new user columns and filters to the Messages report source

                   The standard user information columns and filters have been added to the
                   messages report source.
                   This allows you to find out information such as who the message was sent
                   to.

    TL-5362        Assessor, Regional Manager and Regional Trainer roles were removed

                   Previously roles Assessor, Regional Manager and Regional Trainer were
                   automatically created during Totara installation. These roles did not have
                   any special capabilities by default.
                   As of Totara 2.9.0 these roles will no longer be automatically created.
                   If you need them you may easily create them and add any required
                   capabilities.

    TL-5394        The initialdisplay setting can now be passed when creating embedded reports

                   Thanks to Russell England at Vision NV for the contribution.

    TL-5511        Added pagination to the bottom of Report builder reports

                   When viewing a report pagination is now shown both above and below the
                   report.

    TL-5954        Added a link between the start and finish date pickers for Facetoface sessions

                   The start and finish date pickers are now linked so when changing the start
                   date the finish date will automatically change to the same day.

    TL-6022        Added the AND operator to course set operators in Programs and Certifications

                   This allows people to create rules such as "Learner must complete one of
                   course1, course2, course3 AND one of course4, course5, course6".

                   Thanks to Eugene Venter at Catalyst NZ for the contribution.

    TL-6154        Refactor filters to use named constants instead of numbers

                   Previously filter operators within code were represented just as integers.
                   This change introduced new constants to make the handling of these
                   operators much clearer.

    TL-6204        Customisable font when exporting a report to PDF

                   A new setting has been introduced that allows the font used within Report
                   builder PDF exports to be customised.
                   This allows those on non-standard installations to work around required
                   fonts that they do not have.

    TL-6206        Improvements of CSV export in Report builder

                   CSV export now starts sending data immediately instead of waiting for the
                   whole file to be generated.
                   The total processing time is the same, memory usage is decreased and users
                   may download the file in the background.

    TL-6308        Improve control over who can approve Facetoface attendance requests

                   A new capability mod/facetoface:approveanyrequest has been added that is
                   now required in order to approve Facetoface session attendance request.
                   Prior to this patch only site administrators or a user's assigned manager
                   could approve a request. Now anyone with this capability can also approve
                   an attendance request.
                   This capability is not given to anyone by default.

                   Thanks to Eugene Venter at Catalyst NZ for the contribution.

    TL-6383        Improved accessibility of the general settings page

                   Previously there was an empty label associated with the warnings checkbox
                   following the "Send notifications for" label. This has now been improved so
                   that "Send notifications for" is now a legend and the "Errors" and
                   "Warnings" checkboxes now have labels correctly assigned to them.

    TL-6413        Report builder sources may now be marked as ignored

                   The Report builder report source API has been extended so that report
                   sources can now inform Report Builder that they should be ignored.
                   Report builder can then choose to treat an ignored source differently, at
                   the very least ensuring that it is not accessible.

                   This can be used in situations such as when the source depends upon a
                   plugin or feature being enabled.
                   Previously this would could lead to errors if someone tried to use the
                   source.
                   Now it is dealt with gracefully.

    TL-6414        Added several new placeholders to Facetoface notifications

                   The following placeholders were added for notification emails regarding
                   Facetoface sessions:

                      * [lateststarttime] - Start time of the session. If there are multiple
                        session dates it will use the last one.
                      * [lateststartdate] - Date at the start of the session. If there are
                        multiple session dates it will use the last one.
                      * [latestfinishtime] - Finish time of the session. If there are multiple
                        session dates it will use the last one.
                      * [latestfinishdate] - Date at the end of the session. If there are
                        multiple session dates it will use the last one.

                   These can be used in conjunction with existing placeholders that use the
                   first session date. For example: "[starttime], [startdate] to
                   [latestfinishtime], [latestfinishdate]" will give the overall start and
                   finish times of a multi-date session.

    TL-6451        Added option in HR Import to force password reset when undeleting users

                   Previously, users would have their password reset if it was not provided in
                   the same import. This change makes the reset optional. If a password is
                   provided in the import then it will still take precedence and the reset
                   will not occur.

    TL-6453        Added a time due column to the program and certification completion report sources

                   Thanks to Eugene Venter at Catalyst NZ for the contribution

    TL-6454        Improved the robustness of Facetoface archive tests

                   Thanks to Andrew Hancox at Synergy Learning for the contribution.

    TL-6496        Added several new filters to the certification overview report source

                   The following filters have been added to the certification overview report
                   source:

                      * Add status
                      * Renewal status
                      * Time completed

                   Thanks to Eugene Venter at Catalyst NZ for the contribution.

    TL-6497        Added a timedue filter to the program overview report source

                   Thanks to Eugene Venter at Catalyst NZ for the contribution.

    TL-6531        Improved the performance of prog_get_all_programs

                   Thanks to Pavel Tsakalidis at Kineo UK for the contribution

    TL-6605        Improved the alignment of advanced checkbox labels in all themes
    TL-6629        DOMPDF library has been updated to 0.6.1

                   DOMPDF has been upgraded from 0.6.0 to 0.6.1.
                   This upgrade includes a large number of fixes to both bugs and stability.

    TL-6655        Removed legacy md5 hashing from lessons and lesson user / group overrides
    TL-6667        Course completion now correctly considers activity completion without a grade as complete

                   This patch fixes an inconsistency in how activity completion gets treated.
                   Prior to this patch if you achieved activity completion without getting a
                   passing grade for that activity some places would consider it as complete
                   and others would not.
                   This is now consistently and correctly considered to be complete by all
                   areas of Totara that work with activity completion.

    TL-6676        Improved responsive design when viewing an appraisal
    TL-6761        Updated jQuery dataTables plugin from version 1.9.4 to 1.10.7
    TL-6777        Minified Totara specific JavaScript files

                   Currently there are a large number of JavaScript files that are transferred
                   from your Totara server that are not minified. This issue minifies files
                   for Totara dialogues, and ensures the jQuery plugin files that we use are
                   minified. The minified files are only delivered if the cachejs
                   configuration value is set to true (as it should be on production sites).

                   Minified files reduce the amount of data that is transferred from the
                   server to the browser, resulting in faster page loading times (although
                   this may not be noticeable).

    TL-6880        Role definition page now shows the number of users having each role
    TL-6913        Reviewed language strings that used Moodle and improved them where required
    TL-6915        Forum post emails are now using "totaraforumX" in list ids.
    TL-6919        Installation and upgrade reliability improvements

                   The following changes have been made to the installation and upgrade
                   process:

                   * Fixes and improvements in install and upgrade logic
                   * Fixed Totara plugin environment tests
                   * Fixed missing 'canvas' theme when upgrading from Moodle

    TL-6920        Session Cookie names now use Totara as a prefix
    TL-6926        Improved memcached cache store prefix handling

                   Prior to this patch if no prefix was specified and the settings for the
                   cache store instance were changed, the cache environment could become
                   corrupt and the cache would need to be manually purged.
                   Now if no prefix is specified, a hash is generated from the store instance
                   settings and this is used as the prefix.
                   As the prefix will now change when the settings change, keys cannot conflict
                   and this avoids any need to manually purge the cache.
                   Those who have a prefix set will still need to manually manage their
                   memcached purging if they change any settings.

    TL-6931        Curl requests made by Totara now use a specific TotaraBot agent string
    TL-6943        Support generating of tree structures when bulk adding hierarchy items

                   It is now possible to use the bulk add functionality to generate a tree
                   structure instead of just a flat list of new items. Use 2 spaces in front
                   of the item name to indent an item by one level.

    TL-6961        Each custom field type is now managed by a single capability

                   In Totara 2.7 and all older versions every custom field type had three
                   capabilities used to manage them (create, edit, delete).
                   This improvement sees the capabilities simplified so that each custom field
                   type uses only a single capability for management instead of the three.
                   This ensures that any actions taken by a user can also be undone, it also
                   greatly simplifies management of capabilities around custom field types.
                   The old create, edit, and delete capabilities have been removed.

                   The following is a list of custom field types and the new capability that
                   is used to manage them:

                   * Facetoface custom fields managed by mod/facetoface:managecustomfield
                   * Course custom fields managed by totara/core:coursemanagecustomfield
                   * Program and Certification custom fields managed by
                     totara/core:programmanagecustomfield
                   * Competency custom fields managed by
                     totara/hierarchy:competencymanagecustomfield
                   * Goal custom fields managed by totara/hierarchy:goalmanagecustomfield
                   * Organisation custom fields managed by
                     totara/hierarchy:organisationmanagecustomfield
                   * Position custom fields managed by
                     totara/hierarchy:positionmanagecustomfield

    TL-7013        Installer now only shows available language packs

                   Prior to this change the installation process showed all possible language
                   packs, rather than just those that were available.

    TL-7019        Dashboard functionality can now be disabled via advanced features
    TL-7022        My Team functionality can now be disabled via advanced features
    TL-7031        The URL download repository is now disabled by default on new installations

                   This change is intended to improve security. We strongly recommend that those sites
                   which are upgrading also disable this repository, unless they are actually using its
                   functionality.
                   The repository itself allows content to be downloaded from the internet and
                   used within the site.
                   Whilst measures are taken to ensure the download and use of internet
                   content is handled safely and securely it is not possible to completely
                   inoculate the system from threat.
                   We recommend putting the Totara server into a DMZ if this repository is
                   enabled and used.

    TL-7038        Report default sort order is used consistently after report updates
    TL-7072        Converted Facetoface predefined room JS into an AMD module

                   The previously defined totaraDialog_handler_addpdroom has been converted to
                   an AMD module allowing the module to be loaded only when needed.

    TL-7140        Improved the display of Totara table borders such as those used for embedded reports
    TL-7159        Facetoface predefined rooms are no longer required to specify a name, building or address

                   The capacity field is still required.

    TL-7162        Converted the myappraisal JS to an AMD module

                   The M.totara_appraisal_myappraisal JavaScript code has been converted from
                   a statically loaded JS file to an AMD module.
                   This allows the JS to be loaded dynamically with much greater ease and
                   unlocks the benefits AMD brings such as minification and organisation.

                   This change removes the totara/appraisal/js/myappraisal.js file.

    TL-7197        Converted plan templates JS into an AMD module

                   The totara_plan_template JS class has been converted into an AMD module
                   allowing it to be required only when needed.

    TL-7198        Converted the totara_plan_component JS into an AMD module

                   The totara_plan_component JavaScript class has been converted into an AMD
                   module allowing it to be required only when needed.

                   The totara/plan/component.js file was removed as part of this change.

    TL-7236        Login prompt state is now maintained across invalid attempts

                   The state of the the login prompt is now maintained upon a failed
                   authentication attempt.
                   The username entered by the user will remain in the username field, and the
                   state of the remember username checkbox will persist.

    TL-7237        Serving of user submitted files has been hardened to improve security

                   The headers used when serving user submitted files has been improved.
                   Mime type handling has been improved and the following headers are now
                   included when the file being served is forcing a download:

                   * X-Content-Type-Options: nosniff
                   * X-Frame-Options: deny
                   * X-XSS-Protection: 1; mode=block

    TL-7244        Converted M.totara_cohort_assignroles JS into an AMD module

                   The file totara/cohort/assignroles.js has been removed as part of this
                   change.

    TL-7293        Session timezone is now used for date fields when editing Facetoface session
    TL-7297        Guest access is now disabled in new installations
    TL-7331        New framework for improved error message handling when AJAX calls fail

                   Previously there was no framework for when a jQuery AJAX call fails.
                   This can leave a number interactions in Totara with nondescript errors.
                   This fix provides a framework for errors to be caught, handled and
                   displayed. It also provides debugging information when debug has been
                   turned on allowing JS issues to be investigated with much more ease.

    TL-7384        Begin phasing out the "Hide" option for advanced Totara features

                   Previously many of the advanced features added in Totara could be set to
                   three states enabled, disabled, hidden.
                   The hidden state in many situations was poorly and inconsistently
                   implemented.
                   After discussions it was decided that the hidden state would be removed in
                   favour of a more straight forward enabled/disabled state.

                   Any sites using the "Hide" state will continue to experience the same
                   behaviour they have previously.
                   However the "Hide" state is no longer made available for selection.
                   In the future support for the "Hide" state will be removed.

    TL-7388        Improved reliability of the Atto editor superscript and subscript buttons
    TL-7389        Improved rtl language support for the collapse block icon

                   This introduces a new icon that points to the right in right to left
                   languages (such as Hebrew) when a block is collapsed (t/switch_plus_rtl)

    TL-7432        Added a new capability to allow marking of course completion for related users

                   A new capability has been added totara/core:markusercoursecomplete which
                   can be assigned within the user context and allows a user with that
                   capability to mark another user's required learning courses as complete.
                   Previously, this was only possible for managers marking completion for
                   their staff.

                   This new capability is not given to anyone by default.

    TL-7482        Updated the TCPDF library from 6.2.6 to 6.2.12
    TL-7510        Improved the flow of links within the Appraisal report source
    TL-7524        Improved the secure page layout within standard Totara responsive
    TL-7529        Fixed handling of RPL records when resetting or deleting a course or its completions

                   This change fixes how RPL records are handled when a course is reset by a
                   certification, deleted or reset by a user, or course completions unlocked
                   by a teacher.

                   When deleting or resetting a course, RPL completions are now also deleted
                   correctly. Previously these were not removed. An upgrade step will safely
                   remove invalid data records for deleted courses.

                   In 2.9.0 when a users course completion gets reset by a certification
                   window opening, all course and activity RPL completions will be removed.

                   As before, when a teacher unlocks course completion criteria and selects to
                   delete, course and activity RPL records will be kept and still count
                   towards a users completion.

                   Thanks to Eugene Venter at Catalyst NZ for the contribution

    TL-7530        Improved the display of error messages for date controls
    TL-7589        Added timezone support to plans and plan templates
    TL-7682        Improved the date display code to ensure it is consistent across all platforms

                   Language packs may now use all strftime parameters as listed here
                   http://php.net/manual/en/function.strftime.php


Accessibility improvements:

    TL-5275        Removed the fieldset surrounding the action buttons within a form

                   Previously the action (submit + cancel) buttons within a form were being
                   printed within a fieldset.
                   In order to improve accessibility across all forms by reducing the number
                   of nested fieldsets this particular fieldset was removed.

    TL-6234        Removed the HTML table used for layout when adding badge criteria

                   This patch also improves accessibility around single selects.

    TL-6239        Improved accessibility when setting a custom room

                   When editing a Facetoface session, the form elements for setting a custom
                   room had labels that were not correctly linked to their HTML elements, and
                   had an unnecessary HTML table.

    TL-6291        Improved the accessibility when viewing learning plans
    TL-6294        Removed the table used for layout on the course participants page

                   When viewing participants within a course, the filters at the top of the
                   page were within an HTML table. This has been removed and replaced with a
                   series of div elements making the page more accessible and responsive.

    TL-6310        Removed incorrect label on the Certificate settings page
    TL-6320        Replaced invalid use of HTML labels within the add activity dialog
    TL-6337        Removed the HTML table used on the group user management page for courses
    TL-6380        Improved accessibility of question bank export
    TL-6381        Improved accessibility of question bank import
    TL-6382        The fieldset template within forms now uses a fieldset

                   There were a number of form fieldsets that were incorrectly used, making a
                   number of web pages inaccessible to screen readers.
                   This change improves accessibility considerably but is likely to cause
                   display problems for themes that have restyled forms.
                   A good place to look at check your theme styles would be by reviewing the
                   form used when adding a Facetoface session.

    TL-6388        Improved accessibility on the users badge profile setting page
    TL-6390        Removed empty labels and legend attributes from all form element
    TL-6394        Removed the HTML label from admin settings when it was not referencing anything

                   What were formerly HTML label elements are now spans with the admin-label
                   css class. Any CSS styles that were applied to HTML labels (in the admin
                   area), will also need to be applied to this class (adjusting the font-size
                   will need to override ".form-item .admin-label")

    TL-6423        Removed the HTML table within a course user details view
    TL-6585        Improved accessibility uploading images into the Certificate module
    TL-7139        Removed heading around no results messages for flexible tables
    TL-7187        Removed the HTML table around user details when inside a chat activity
    TL-7188        Removed the HTML table used for layout when entering a chat message
    TL-7552        Replaced individual hierarchy item description table with a datalist


Database schema changes
=======================

New tables:
Bug ID   New table name
-----------------------------
TL-4485  goal_user_info_data
TL-4485  goal_user_info_data_param
TL-4485  goal_user_info_field
TL-4485  goal_user_type_cohort
TL-4485  goal_user_type
TL-6684  report_builder_global_restriction
TL-6684  reportbuilder_grp_cohort_record
TL-6684  reportbuilder_grp_org_record
TL-6684  reportbuilder_grp_pos_record
TL-6684  reportbuilder_grp_user_record
TL-6684  reportbuilder_grp_cohort_user
TL-6684  reportbuilder_grp_org_user
TL-6684  reportbuilder_grp_pos_user
TL-6684  reportbuilder_grp_user_user
TL-7246  auth_connect_servers
TL-7246  auth_connect_users
TL-7246  auth_connect_user_collections
TL-7246  auth_connect_sso_requests
TL-7246  auth_connect_sso_sessions

New fields:
Bug ID   Table name                New field name
------------------------------------------------------------
TL-2250  prog                      allowextensionrequests
TL-4485  goal_personal             typeid
TL-4485  goal_personal             visible
TL-5094  feedback360               anonymous
TL-5097  cohort_rule_collections   addnewmembers
TL-5097  cohort_rule_collections   removeoldmembers
TL-6684  report_builder            globalrestriction

Modified fields:
Bug ID   Table name                Field name
------------------------------------------------------------
TL-6621  report_builder_schedule   format        Converted from int to char


API Changes
===========

TL-2250 New options to turn off program extension requests
----------------------------------------------------------
 * New totara_prog_extension_allowed function returns true if the given program allows extension requests

TL-2565 Fixed date custom user profile field type
-------------------------------------------------
 * totara_date_parse_from_format has a new forth argument $forcetimezone

TL-4485 New personal goal types with custom fields
----------------------------------------
 * New report source class rb_source_goal_custom
 * New totara_cohort_get_goal_type_cohorts function returns the cohorts associated with a personal goal type
 * customfield_base::customfield_definition has a new optional sixth argument $disableheader
 * totara_customfield_renderer::get_redirect_options has a new optional third argument $class
 * totara_customfield_renderer::customfield_manage_edit_form has a new ninth argument $class
 * hierarchy::get_type_by_id new optional second argument $usertype
 * hierarchy::display_add_type_button new optional second argument $class
 * hierarchy::delete_type new optional second argument $class
 * hierarchy::delete_type_metadata new optional second argument $class
 * New totara_hierarchy_save_cohorts_for_type function to save the cohort/audience data against the
   hierarchy type
 * totara_hierarchy_renderer::mygoals_personal_table new third optional argument $display

TL-5020 Change of 'from' email address when sending emails
----------------------------------------------------------
 * New totara_get_user_from function returns a user to use as the from user when sending emails

TL-5094 Support for anonymous 360 feedback
------------------------------------------
 * New property feedback360->anonymous [bool]
 * totara_feedback360_renderer->display_feedback_header() has two new arguments $anonymous and $numresponders
 * totara_feedback360_renderer->view_request_infotable() has a new argument $anonymous
 * totara_feedback360_renderer->system_user_record() has a new argument $anonymous
 * totara_feedback360_renderer->external_user_record() has a new argument $anonymous
 * totara_feedback360_renderer->nojs_feedback_request_users() has a new argument $anonymous

TL-5097 New options to disable changes in membership for dyanmic audiences
--------------------------------------------------------------------------
 * New event totara_cohort\event\option_updated fired when ever cohort options are updated.
 * New totara_cohort_update_membership_options function to update cohort options. Fires the above event.

TL-5356 Added new user columns and filters to the Messages report source
---------------------------------------------------------------------
 * rb_base_source->add_user_table_to_joinlist() has a new argument $alias
 * rb_base_source::add_user_fields_to_filters() has a new argument $addtypetoheading

TL-5818 Report builder reports can now be cloned
------------------------------------------------
 * New totara_reportbuilder\event\report_cloned event that gets fired when a report is cloned.
 * New reportbuilder_set_default_access function that sets the default restrictive access for new report
 * New reportbuilder_clone_report function to clone a report

TL-6234 Removed the HTML table used for layout when adding badge criteria
-------------------------------------------------------------------------
 * core_renderer::single_select has a new argument $attributes

TL-6310 Removed incorrect label on the Certificate settings page
----------------------------------------------------------------
 * Deleted mod/certificate/adminsetting.class.php and the mod_certificate_admin_setting_upload class within

TL-6413 Report builder sources may now be marked as ignored
-----------------------------------------------------------
 * New reportbuilder::reset_caches static method to reset user permitted report caches
 * New reportbuilder::get_user_permitted_reports static method to get the reports a user can access
 * reportbuilder_get_reports has been deprecated, please use reportbuilder::get_user_permitted_reports instead
 * New rb_base_source::is_ignored method that can be overridden if the report should always be available

TL-6525 New report table block
------------------------------
 * New reportbuilder::overrideuniqueid() to set a unique ID.
 * New reportbuilder::overrideignoreparams() tells report builder to ignore the standard params when
   constructing the next report.
 * New reportbuilder->get_uniqueid() to report the reports unique ID. All external calls to $report->_id
   should be upgraded to use this method.

TL-6684 Added new global report restrictions
--------------------------------------------
 * New rb_global_restriction class which manages report restrictions rules
 * New rb_global_restriction_set class which integrates restrictions into report builder
 * New parameter in reportbuilder constructor which expects instance of rb_global_restriction_set
 * New rb_base_source::global_restrictions_supported method which should be overridden by report sources
   that support Global Report Restrictions
 * New rb_base_source::get_global_report_restriction_join method to inject Global Report Restrictions
   SQL snippet into base query
 * New parameters signature in rb_base_source::__construct now it should be ($groupid, rb_global_restriction_set
   $globalrestrictionset = null) for all inherited classes
 * New reportbuilder::display_restriction method which displays current restrictions and options to change
   them on report pages
 * New rb_base_embedded::embedded_global_restrictions_supported method which should be overridden by embedded
   report classes to indicate their Global Report restrictions support

TL-6942 Define and use different flavours of Totara during installation and upgrade
-----------------------------------------------------------------------------------
 * New Totara Flavour plugins, component is totara_flavour, plugins are flavour_pluginname.
 * New totara_flavour\definition class that all flavours must extend.

TL-6961 Each custom field type is now managed by a single capability
--------------------------------------------------------------------
 * New method totara_customfield\prefix\*_type->get_capability_managefield()
 * Deleted method totara_customfield\prefix\*_type->get_capability_editfield()
 * Deleted method totara_customfield\prefix\*_type->get_capability_createfield()
 * Deleted method totara_customfield\prefix\*_type->get_capability_createfield()
 * Deleted method totara_customfield\prefix\competency_type->get_capability_deletefield()

Capability changes
 * Added capability: totara/core:coursemanagecustomfield
 * Added capability: totara/core:programmanagecustomfield
 * Added capability: mod/facetoface:managecustomfield
 * Added capability: totara/hierarchy:positionmanagecustomfield
 * Added capability: totara/hierarchy:organisationmanagecustomfield
 * Added capability: totara/hierarchy:goalmanagecustomfield
 * Added capability: totara/hierarchy:competencymanagecustomfield
 * Removed capability: totara/core:createcoursecustomfield
 * Removed capability: totara/core:updatecoursecustomfield
 * Removed capability: totara/core:deletecoursecustomfield
 * Removed capability: totara/core:createprogramcustomfield
 * Removed capability: totara/core:updateprogramcustomfield
 * Removed capability: totara/core:deleteprogramcustomfield
 * Removed capability: mod/facetoface:updatefacetofacecustomfield
 * Removed capability: mod/facetoface:createfacetofacecustomfield
 * Removed capability: mod/facetoface:deletefacetofacecustomfield
 * Removed capability: totara/hierarchy:updatecompetencycustomfield
 * Removed capability: totara/hierarchy:createcompetencycustomfield
 * Removed capability: totara/hierarchy:deletecompetencycustomfield
 * Removed capability: totara/hierarchy:updategoalcustomfield
 * Removed capability: totara/hierarchy:creategoalcustomfield
 * Removed capability: totara/hierarchy:deletegoalcustomfield
 * Removed capability: totara/hierarchy:updateorganisationcustomfield
 * Removed capability: totara/hierarchy:createorganisationcustomfield
 * Removed capability: totara/hierarchy:deleteorganisationcustomfield
 * Removed capability: totara/hierarchy:updatepositioncustomfield
 * Removed capability: totara/hierarchy:createpositioncustomfield
 * Removed capability: totara/hierarchy:deletepositioncustomfield

TL-7237 Serving of user submitted files has been hardened to improve security
-----------------------------------------------------------------------------
 * New totara_tweak_file_sending function that gets called before serving files.

TL-7246 Totara Connect Client
-----------------------------
 * Totara Connect makes it possible to connect one or more Totara LMS or Totara Social
   installations to a master Totara LMS installation.
 * This connection allows for users, and audiences to be synchronised from the
   master to all connected client sites.
 * Synchronised users can move between the connected sites with ease thanks to the
   single sign on system accompanying Totara Connect.

TL-7529 Fixed handling of RPL records when resetting or deleting a course or its completions
--------------------------------------------------------------------------------------------
 * New method completion_info->delete_course_completion_data_including_rpl()


Deleted files
=============

Bug ID   File
----------------------------------------------
TL-5094 totara/feedback360/request/save.php
TL-6310 mod/certificate/adminsetting.class.php
TL-6684 mod/feedback/rb_sources/lang/en/rb_source_feedback_questions.php
TL-6684 mod/feedback/rb_sources/lang/en/rb_source_graphical_feedback_questions.php
TL-6684 mod/feedback/rb_sources/rb_preproc_feedback_questions.php
TL-6684 mod/feedback/rb_sources/rb_source_feedback_questions.php
TL-6684 mod/feedback/rb_sources/rb_source_graphical_feedback_questions.php
TL-6777 totara/core/js/lib/jquery.dataTables.js
TL-6777 totara/core/js/lib/jquery.dataTables.min.js
TL-6777 totara/core/js/lib/jquery.placeholder.js
TL-6777 totara/core/js/lib/jquery.placeholder.min.js
TL-6777 totara/core/js/lib/jquery.treeview.js
TL-6777 totara/core/js/lib/jquery.treeview.min.js
TL-6777 totara/core/js/lib/load.placeholder.js
TL-6777 totara/core/js/lib/readme_totara.txt
TL-6777 totara/core/js/lib/totara_dialog.js
TL-7013 install/lang/ Multiple language files for unsupported languages
TL-7162 totara/appraisal/js/myappraisal.js
TL-7197 totara/plan/templates.js
TL-7198 totara/plan/component.js
TL-7244 totara/cohort/assignroles.js


Contributions:

    * Andrew Hancox at Synergy Learning - TL-6454
    * Dennis Heany at Learning Pool - TL-6525
    * Ryan Lafferty at Learning Pool - TL-4485
    * Eugene Venter at Catalyst NZ - TL-6022, TL-6023, TL-6308, TL-6453, TL-6496, TL-6497, TL-7529
    * Maccabi Healthcare Services a client of Kineo Israel - TL-6684
    * Pavel Tsakalidis at Kineo UK   - TL-6531
    * Russell England at Vision NV - TL-5394

 */
