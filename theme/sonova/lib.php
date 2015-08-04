<?php

require_once(dirname(__DIR__) . '/sonovacustomthemelib.php');

/**
 * Serves any files associated with the theme settings.
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param context $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @param array $options
 * @return bool
 */
function theme_sonova_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    return sonova_theme_pluginfile('sonova', $course, $cm, $context, $filearea, $args, $forcedownload, $options);
}
