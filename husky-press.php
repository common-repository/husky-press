<?php

/**
 * HuskyPress
 *
 * @package   Husky Press
 * @author    Rezkonline
 * @link      https://wp-husky.com
 * @copyright 2021 by Rezkonline
 *
 * @wordpress-plugin
 * Plugin Name: Husky Press
 * Plugin URI:  https://wp-husky.com
 * Description: This plugin gives your the ability to manage, maintain and monitor one, or multiple WordPress websites.
 * Version:     1.1.1
 * Author:      Rezkonline
 * Author URI:  https://rezkonline.com
 * License:     GPL v3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: husky-press
 * Domain Path: /i18n/
 */

defined('ABSPATH') || exit;

if (!class_exists('Husky_Press')) {
    /**
     * Main Husky Class.
     *
     * @since 1.0.0
     */
    final class Husky_Press
    {
        /**
         * Plugin version.
         *
         * @var string
         */
        public $version = '1.1.1';

        /**
         * Minimum version of WordPress required to run HuskyPress.
         *
         * @var string
         */
        private $wordpress_version = '5.2';

        /**
         * Minimum version of PHP required to run HuskyPress.
         *
         * @var string
         */
        private $php_version = '7.2';

        /**
         * Holds various class instances.
         *
         * @var array
         */
        private $container = [];

        /**
         * Hold install error messages.
         *
         * @var array
         */
        private $messages = [];

        /**
         * @var Husky_Press
         */
        protected static $instance = null;

        /**
         * Main Husky Instance.
         *
         * @static
         * @return Husky_Press
         */
        public static function get()
        {
            if (is_null(self::$instance)) {
                self::$instance = new self;
            }

            return self::$instance;
        }

        /**
         * Husky_Press Constructor.
         */
        public function __construct()
        {
            $this->define_constants();

            if (!$this->is_requirements_meet()) {
                return;
            }

            $this->includes();
            $this->init_hooks();

            do_action('husky_press_loaded');
        }

        /**
         * Define Constants.
         *
         * @return void
         */
        public function define_constants()
        {
            $this->define('HUSKY_PRESS_ROOT', __DIR__);
            $this->define('HUSKY_PRESS_FILE', __FILE__);
            $this->define('HUSKY_PRESS_DIR_URL', plugin_dir_url(HUSKY_PRESS_FILE));
            $this->define('HUSKY_PRESS_BASENAME', plugin_basename(HUSKY_PRESS_FILE));
            $this->define('HUSKY_PRESS_DIR', dirname(HUSKY_PRESS_FILE) . '/');
            $this->define('HUSKY_PRESS_BACKUP', WP_CONTENT_DIR . '/husky-backup');
            $this->define('HUSKY_PRESS_SITE_URL', 'https://wp-husky.com');
            $this->define('HUSKY_PRESS_APP_URL', 'https://app.wp-husky.com');
            $this->define('HUSKY_PRESS_API_URL', 'https://app.wp-husky.com/api/v1');
        }

        /**
         * Check that the WordPress and PHP setup meets the plugin requirements.
         *
         * @return bool
         */
        private function is_requirements_meet()
        {
            // Check WordPress version.
            if (version_compare(get_bloginfo('version'), $this->wordpress_version, '<')) {
                /* translators: WordPress Version */
                $this->messages[] = sprintf(esc_html__('You are using the outdated WordPress, please update it to version %s or higher.', 'husky-press'), $this->wordpress_version);
            }

            // Check PHP version.
            if (version_compare(phpversion(), $this->php_version, '<')) {
                /* translators: PHP Version */
                $this->messages[] = sprintf(esc_html__('HuskyPress requires PHP version %s or above. Please update PHP to run this plugin.', 'husky-press'), $this->php_version);
            }

            if (empty($this->messages)) {
                return true;
            }

            // Auto-deactivate plugin.
            add_action('admin_init', [$this, 'auto_deactivate']);
            add_action('admin_notices', [$this, 'activation_error']);

            return false;
        }

        /**
         * Auto-deactivate plugin if requirements are not met, and display a notice.
         *
         * @return void
         */
        public function auto_deactivate()
        {
            deactivate_plugins(plugin_basename(HUSKY_PRESS_FILE));
            if (isset($_GET['activate'])) { // phpcs:ignore
                unset($_GET['activate']); // phpcs:ignore
            }
        }

        /**
         * Error notice on plugin activation.
         *
         * @return void
         */
        public function activation_error()
        {
?>
            <div class="notice notice-error">
                <p>
                    <?php echo join('<br>', $this->messages); // phpcs:ignore
                    ?>
                </p>
            </div>
<?php
        }

        /**
         * Include required core files.
         *
         * @return void
         */
        public function includes()
        {
            include HUSKY_PRESS_DIR . 'vendor/autoload.php';

            new \HuskyPress\Installer();

            $this->container['settings'] = new \HuskyPress\Settings();
            $this->container['errors'] = new \HuskyPress\Errors();
            $this->container['backups'] = new \HuskyPress\Backups();
        }

        /**
         * Init required hooks.
         *
         * @return void
         */
        public function init_hooks()
        {
            add_action('plugins_loaded', [$this, 'localization_setup'], 9);

            add_filter('plugin_row_meta', [$this, 'plugin_row_meta'], 10, 2);
            add_filter('plugin_action_links_' . plugin_basename(HUSKY_PRESS_FILE), [$this, 'plugin_action_links']);

            add_action('rest_api_init', [$this, 'init_rest_api']);

            if (is_admin()) {
                add_action('plugins_loaded', [$this, 'init_admin'], 15);
            }

            add_action('plugins_loaded', [$this, 'init_shared']);
        }

        /**
         * Load the REST API endpoints.
         *
         * @return void
         */
        public function init_rest_api()
        {
            $controllers = [
                new \HuskyPress\Rest\Admin(),
                new \HuskyPress\Rest\Shared(),
                new \HuskyPress\Rest\Plugin(),
                new \HuskyPress\Rest\Themes(),
                new \HuskyPress\Rest\Issues(),
                new \HuskyPress\Rest\Backups(),
            ];

            foreach ($controllers as $controller) {
                $controller->register_routes();
            }
        }

        /**
         * @return void
         */
        public function init_admin()
        {
            new \HuskyPress\Admin\AdminHooks();
        }

        /**
         * @return void
         */
        public function init_shared()
        {
            new \HuskyPress\Shared\SharedHooks();
        }

        /**
         * Initialize plugin for localization.
         *
         * @return void
         */
        public function localization_setup()
        {
            $locale = is_admin() && function_exists('get_user_locale') ? get_user_locale() : get_locale();
            $locale = apply_filters('plugin_locale', $locale, 'husky-press'); // phpcs:ignore

            unload_textdomain('husky-press');
            if (false === load_textdomain('husky-press', WP_LANG_DIR . '/plugins/husky-press-' . $locale . '.mo')) {
                load_textdomain('husky-press', WP_LANG_DIR . '/husky-press/husky-press-' . $locale . '.mo');
            }
            load_plugin_textdomain('husky-press', false, HUSKY_PRESS_DIR . 'i18n/');
        }

        /**
         * Add extra links as row meta on the plugin screen.
         *
         * @param  mixed $links Plugin Row Meta.
         * @param  mixed $file  Plugin Base file.
         * @return array
         */
        public function plugin_row_meta($links, $file)
        {
            if (plugin_basename(HUSKY_PRESS_FILE) !== $file) {
                return $links;
            }

            $more = [
                '<a href="https://wp-husky.com/documentation">' . esc_html__('Documentation', 'husky-press') . '</a>',
            ];

            return array_merge($links, $more);
        }

        /**
         * Show action links on the plugin screen.
         *
         * @param  mixed $links Plugin Action links.
         * @return array
         */
        public function plugin_action_links($links)
        {
            $plugin_links[] = '<a href="' . esc_url(admin_url('admin.php?page=husky-press#/')) . '">' . esc_html__('Settings', 'husky-press') . '</a>';

            if (!$this->settings->get('auth.token') || !$this->settings->get('auth.project_id')) {
                $plugin_links[] = '<a href="' . HUSKY_PRESS_SITE_URL . '" style="font-weight:bold; color:red;" target="_blank">' . esc_html__('Become PREMIUM!', 'husky-press') . '</a>';
            }

            return array_merge($links, $plugin_links);
        }

        /**
         * Define constant if not exists.
         *
         * @return void
         */
        private function define(string $name, $value)
        {
            if (!defined($name)) {
                define($name, $value);
            }
        }

        /**
         * Magic isset to bypass referencing plugin.
         *
         * @param  string $prop Property to check.
         * @return bool
         */
        public function __isset($prop)
        {
            return isset($this->{$prop}) || isset($this->container[$prop]);
        }

        /**
         * Magic getter method.
         *
         * @param  string $prop Property to get.
         * @return mixed Property value or NULL if it does not exists.
         */
        public function __get($prop)
        {
            if (array_key_exists($prop, $this->container)) {
                return $this->container[$prop];
            }

            if (isset($this->{$prop})) {
                return $this->{$prop};
            }

            return null;
        }

        /**
         * Magic setter method.
         *
         * @param mixed $prop  Property to set.
         * @param mixed $value Value to set.
         */
        public function __set($prop, $value)
        {
            if (property_exists($this, $prop)) {
                $this->$prop = $value;
                return;
            }

            $this->container[$prop] = $value;
        }
    }
}

/**
 * Returns the main instance of Husky_Press.
 *
 * @return Husky_Press
 */
function husky_press()
{
    return Husky_Press::get();
}

// Let the husky go!!
husky_press();
