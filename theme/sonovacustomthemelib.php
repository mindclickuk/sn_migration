<?php

function sonova_settings_for_theme($themename) {
    $settings = array();

    // Logo file setting.
    $name = "theme_$themename/logo";
    $title = new lang_string('logo', 'theme_customtotararesponsive');
    $description = new lang_string('logodesc', 'theme_customtotararesponsive');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'logo');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings[]= $setting;

    // Favicon file setting.
    $name = "theme_$themename/favicon";
    $title = new lang_string('favicon', 'theme_customtotararesponsive');
    $description = new lang_string('favicondesc', 'theme_customtotararesponsive');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'favicon', 0, array('accepted_types' => '.ico'));
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings[]= $setting;

    // Link colour setting.
    $name = "theme_$themename/linkcolor";
    $title = new lang_string('linkcolor', 'theme_customtotararesponsive');
    $description = new lang_string('linkcolordesc', 'theme_customtotararesponsive');
    $default = '#087BB1';
    $previewconfig = array('selector' => 'a', 'style' => 'color');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings[]= $setting;

    //Link visited colour setting.
    $name = "theme_$themename/linkvisitedcolor";
    $title = new lang_string('linkvisitedcolor', 'theme_customtotararesponsive');
    $description = new lang_string('linkvisitedcolordesc', 'theme_customtotararesponsive');
    $default = '#087BB1';
    $previewconfig = array('selector' => 'a:visited', 'style' => 'color');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings[]= $setting;

    // Page header background colour setting.
    $name = "theme_$themename/headerbgc";
    $title = new lang_string('headerbgc', 'theme_customtotararesponsive');
    $description = new lang_string('headerbgcdesc', 'theme_customtotararesponsive');
    $default = '#F5F5F5';
    $previewconfig = array('selector' => '#page-header', 'style' => 'backgroundColor');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings[]= $setting;

    // Button colour setting.
    $name = "theme_$themename/buttoncolor";
    $title = new lang_string('buttoncolor','theme_customtotararesponsive');
    $description = new lang_string('buttoncolordesc', 'theme_customtotararesponsive');
    $default = '#E6E6E6';
    $previewconfig = array('selector'=>'input[\'type=submit\']]', 'style'=>'background-color');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings[]= $setting;

    // Custom CSS file.
    $name = "theme_$themename/customcss";
    $title = new lang_string('customcss','theme_customtotararesponsive');
    $description = new lang_string('customcssdesc', 'theme_customtotararesponsive');
    $setting = new admin_setting_configtextarea($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings[]= $setting;

    return $settings;
}

/**
 * Serves any files associated with the theme settings.
 *
 * @param string $themename
 * @param stdClass $course
 * @param stdClass $cm
 * @param context $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @param array $options
 * @return bool
 */
function sonova_theme_pluginfile($themename, $course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    if ($context->contextlevel == CONTEXT_SYSTEM && ($filearea === 'logo' || $filearea === 'favicon')) {
        $theme = theme_config::load($themename);
        return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
    } else {
        send_file_not_found();
    }
}
