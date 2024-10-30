<?php

namespace HuskyPress\Admin;

use MyThemeShop\Admin\Page;

class AdminMenu
{
    /**
     * Register hooks.
     *
     * @return void
     */
    public function hooks()
    {
        add_action('init', [$this, 'register_pages']);
        add_action('admin_menu', [$this, 'fix_first_submenu'], 999);
    }

    /**
     * Register admin pages for plugin.
     *
     * @return void
     */
    public function register_pages()
    {
        if (apply_filters('husky_press_white_label', false)) {
            return;
        }

        new Page(
            'husky-press',
            esc_html__('Husky Press', 'husky-press'),
            [
                'position' => 70,
                'capability' => 'level_1',
                'render' => $this->get_view('dashboard'),
                'classes' => ['husky-press-page'],
                'assets' => [
                    'styles' => [
                        'husky-press-dashboard' => '',
                    ],
                    'scripts' => [
                        'husky-press-dashboard' => '',
                    ],
                ],
                'icon' => 'data:image/svg+xml;base64,' . base64_encode(husky_press_get_menu_icon()),
                'is_network' => is_network_admin(),
            ]
        );

        new Page(
            'husky-press-support',
            esc_html__('Support', 'husky-press'),
            [
                'position' => 70,
                'parent' => 'husky-press',
                'capability' => 'level_1',
            ]
        );
    }

    /**
     * Fix first submenu.
     *
     * @return void
     */
    public function fix_first_submenu()
    {
        global $submenu;

        if (!isset($submenu['husky-press'])) {
            return;
        }

        unset($submenu['husky-press'][1]);

        if ('Husky Press' === $submenu['husky-press'][0][0]) {
            if (current_user_can('manage_options')) {
                $submenu['husky-press'][0][0] = esc_html__('Dashboard', 'husky-press');
            } else {
                unset($submenu['husky-press'][0]);
            }
        }

        if (empty($submenu['husky-press'])) {
            return;
        }

        $submenu['husky-press'][] = [esc_html__('Help &amp; Support', 'husky-press') . '<i class="dashicons dashicons-external" style="font-size:12px;vertical-align:-2px;height:10px;"></i>', 'level_1', 'https://wp-husky.com/contact?utm_source=Plugin&utm_campaign=WP'];
    }

    /**
     * Get admin view file.
     *
     * @param string $view
     * @return string
     */
    protected function get_view($view)
    {
        $view = sanitize_key($view);

        return HUSKY_PRESS_DIR . "views/{$view}.php";
    }
}