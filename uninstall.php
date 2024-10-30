<?php

// If uninstall not called from WordPress, then exit.
if (!defined('WP_UNINSTALL_PLUGIN')) {
  exit;
}

/**
 * Delete options.
 *
 * @return void
 */
function husky_press_delete_options()
{
  global $wpdb;

  $where = $wpdb->prepare('WHERE option_name LIKE %s OR option_name LIKE %s', '%' . $wpdb->esc_like('husky-press') . '%', '%' . $wpdb->esc_like('husky_press') . '%');
  $wpdb->query("DELETE FROM {$wpdb->prefix}options {$where}"); // phpcs:ignore
}

husky_press_delete_options();
