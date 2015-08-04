<?php
defined('MOODLE_INTERNAL') || die;

require_once(dirname(__DIR__) . '/sonovacustomthemelib.php');

if ($ADMIN->fulltree) {
   foreach (sonova_settings_for_theme('sonova') as $setting) {
       $settings->add($setting);
   }
}
