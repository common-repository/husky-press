<?php

if (! function_exists('is_plugin_active')) {
    include_once ABSPATH . 'wp-admin/includes/plugin.php';
}

if (is_plugin_active('husky-press/husky-press.php') && defined('WP_PLUGIN_DIR')) {
    include_once WP_PLUGIN_DIR . '/husky-press/includes/shared/class-track-errors.php';

    if (class_exists('\\HuskyPress\\Shared\\TrackErrors')) {
        $tracker = new \HuskyPress\Shared\TrackErrors();
        $tracker->hooks();
    }
}
