<?php

namespace HuskyPress;

class Installer
{
  /**
   * Installed Constructor.
   */
  public function __construct()
  {
    register_activation_hook(HUSKY_PRESS_FILE, [$this, 'activation']);
    register_deactivation_hook(HUSKY_PRESS_FILE, [$this, 'deactivation']);

    add_action('plugins_loaded', [$this, 'publish_handler']);
  }

  /**
   * Do things when activating HuskyPress.
   *
   * @param bool $network_wide
   */
  public function activation($network_wide = false)
  {
    if (!is_multisite() || !$network_wide) {
      $this->activate();
      return;
    }

    $this->network_activate_deactivate(true);
  }

  /**
   * Do things when deactivating HuskyPress.
   *
   * @param bool $network_wide
   */
  public function deactivation($network_wide = false)
  {
    if (!is_multisite() || !$network_wide) {
      $this->deactivate();
      return;
    }

    $this->network_activate_deactivate(false);
  }

  /**
   * @return void
   */
  public function publish_handler()
  {
    if (!$this->can_publish_handler()) {
      return;
    }

    if (!file_exists(WPMU_PLUGIN_DIR) && !is_writable(dirname(WPMU_PLUGIN_DIR)) && !file_exists(WPMU_PLUGIN_DIR . '/_HuskyPressHandler.php')) {
      add_action('admin_notices', [$this, 'folder_notices']);

      return false;
    }

    if (!file_exists(WPMU_PLUGIN_DIR) && is_writable(dirname(WPMU_PLUGIN_DIR))) {
      wp_mkdir_p(WPMU_PLUGIN_DIR);
    }

    if (!@copy(
      HUSKY_PRESS_DIR . 'includes/helpers/_HuskyPressHandlerMU.php',
      WPMU_PLUGIN_DIR . '/_HuskyPressHandlerMU.php'
    )) {
      add_action('admin_notices', [$this, 'copy_notices']);

      return false;
    }

    $this->update_version();

    return true;
  }

  /**
   * Folder notices.
   *
   * @return void
   */
  public function folder_notices()
  {
?>
    <div class="notice notice-error">
      <p>
        <?php esc_html__('An error occurred while trying to create a mu-plugin.', 'husky-press') ?>
      </p>
    </div>
  <?php
  }

  /**
   * Copy notices.
   *
   * @return void
   */
  public function copy_notices()
  {
    ?>
      <div class="notice notice-error">
        <p>
          <?php esc_html__('An error was caused when attempted to create a file in your directory mu-plugins.', 'husky-press') ?>
        </p>
      </div>
    <?php
  }

  /**
   * Runs on activation of the plugin.
   *
   * @return void
   */
  private function activate()
  {
    update_option('husky_press_version', husky_press()->version);

    if (false === boolval(get_option('husky_press_install_date'))) {
      update_option('husky_press_install_date', current_time('timestamp')); // phpcs:ignore
    }

    if (!function_exists('wp_mkdir_p')) {
      require_once ABSPATH . 'wp-includes/functions.php';
    }

    if (!file_exists(HUSKY_PRESS_BACKUP)) {
      wp_mkdir_p(HUSKY_PRESS_BACKUP);
      chmod(HUSKY_PRESS_BACKUP, 0777);
    }
  }

  /**
   * Runs on deactivation of the plugin.
   *
   * @return void
   */
  private function deactivate()
  {
    //
  }

  /**
   * Run network-wide activation/deactivation of the plugin.
   *
   * @return void
   */
  private function network_activate_deactivate($activate)
  {
    global $wpdb;

    $blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs WHERE archived = '0' AND spam = '0' AND deleted = '0'");
    if (empty($blog_ids)) {
      return;
    }

    foreach ($blog_ids as $blog_id) {
      $func = true === $activate ? 'activate' : 'deactivate';

      switch_to_blog($blog_id);
      $this->$func();
      restore_current_blog();
    }
  }

  /**
   * @return boolean
   */
  private function update_version()
  {
    return update_option('husky_press_version', husky_press()->version);
  }

  /**
   * @return boolean
   */
  private function can_publish_handler()
  {
    return !file_exists(WPMU_PLUGIN_DIR . '/_HuskyPressHandlerMU.php') || version_compare(husky_press()->version, husky_press_get_current_version(), '>');
  }
}
