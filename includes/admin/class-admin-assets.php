<?php

namespace HuskyPress\Admin;

defined('ABSPATH') || exit;

class AdminAssets
{
    /**
     * Prefix for the enqueue handles.
     */
    const PREFIX = 'husky-press-';

    /**
     * Register hooks.
     *
     * @return void
     */
    public function hooks()
    {
        add_filter('language_attributes', [$this, 'language_attributes']);

        add_action('admin_enqueue_scripts', [$this, 'register']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue']);
    }

    /**
     * Add dir attribute to html.
     *
     * @return void
     */
    public function language_attributes($lang)
    {
        if (is_admin()) {
            $lang .= ' dir="ltr"';
            if (is_rtl()) {
                $lang .= ' dir="rtl"';
            }
        }

        return $lang;
    }

	/**
	 * Register styles and scripts.
     *
     * @return void
	 */
    public function register()
    {
        $css = HUSKY_PRESS_DIR_URL . 'public/css/';
        $js  = HUSKY_PRESS_DIR_URL . 'public/js/';

        // Styles.
        wp_register_style(self::PREFIX . 'app', $css . 'app.css', null, '1.0.0');

        // Scripts.
        wp_register_script(self::PREFIX . 'app', $js . 'app.js', ['jquery', 'wp-i18n'], '1.0.0', true);
    }

	/**
	 * Enqueue styles and scripts.
	 */
    public function enqueue()
    {
        $screen = get_current_screen();

        if ('toplevel_page_husky-press' === $screen->id) {
            // Styles.
            wp_enqueue_style(self::PREFIX . 'app');

            // Scripts
            wp_enqueue_script(self::PREFIX . 'app');

            // Enqueue translations.
            $this->enqueue_translation();

            // Localize script for our services.
            wp_localize_script(self::PREFIX . 'app', 'HUSKY_PRESS_DATA', [
                'AJAX_URL' => admin_url('admin-ajax.php'),
                'NONCE' => wp_create_nonce('husky-press-ajax-nonce'),
                'REST_NONCE' => wp_create_nonce('wp_rest'),
                'API_URL' => HUSKY_PRESS_API_URL,
                'WP_REST_URL' => get_rest_url(),
                'SITE_URL' => get_site_url(),
            ]);
        }
    }

    /**
     * Enqueue translation.
     */
    private function enqueue_translation()
    {
        if (function_exists('wp_set_script_translations')) {
            wp_set_script_translations(self::PREFIX . 'app', 'husky-press', HUSKY_PRESS_DIR_URL . 'i18n/');
        }
    }
}