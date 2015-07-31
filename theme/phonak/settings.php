<?php
defined('MOODLE_INTERNAL') || die;

require_once(dirname(__DIR__) . '/sonovacustomthemelib.php');

if ($ADMIN->fulltree) {
   foreach (sonova_settings_for_theme('phonak') as $setting) {
       $settings->add($setting);
   }
}
