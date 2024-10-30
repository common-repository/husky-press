<?php

namespace HuskyPress\Rest;

use Exception;
use Theme_Upgrader;
use WP_REST_Server;
use WP_REST_Request;
use WP_REST_Controller;
use Automatic_Upgrader_Skin;

defined('ABSPATH') || exit;

class Themes extends WP_REST_Controller
{
    /**
     * Themes Constructor.
     */
    public function __construct()
    {
        $this->namespace = 'husky-press/v1';
    }

    /**
     * Register REST API routes.
     *
     * @return void
     */
    public function register_routes()
    {
        register_rest_route(
            $this->namespace,
            '/themes',
            [
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => [$this, 'themes'],
                'permission_callback' => ['\\HuskyPress\\Rest\\Helpers', 'can_access_endpoint'],
            ]
        );
        register_rest_route(
            $this->namespace,
            '/themes/activate',
            [
                'methods'             => WP_REST_Server::CREATABLE,
                'args'                => [
                    'theme'  => [
                        'required' => true,
                    ],
                ],
                'callback'            => [$this, 'activate'],
                'permission_callback' => ['\\HuskyPress\\Rest\\Helpers', 'can_access_endpoint'],
            ]
        );
        register_rest_route(
            $this->namespace,
            '/themes/update',
            [
                'methods'             => WP_REST_Server::CREATABLE,
                'args'                => [
                    'theme'  => [
                        'required' => true,
                    ],
                ],
                'callback'            => [$this, 'update'],
                'permission_callback' => ['\\HuskyPress\\Rest\\Helpers', 'can_access_endpoint'],
            ]
        );
    }

    /**
     * List of installed themes.
     *
     * @return void
     */
    public function themes()
    {
        $themes = husky_press_get_themes();

        return rest_ensure_response([
            'success' => true,
            'data' => $themes,
        ]);
    }

    /**
     * Update Theme By Id.
     *
     * @param \WP_REST_Request  $request
     *
     * @return \WP_REST_Response
     */
    public function update(WP_REST_Request $request)
    {
        $theme = $request->get_param('theme');

        try {
            $nonce = 'upgrade-theme' . $theme;
            $url = 'update.php?action=update-theme&theme=' . urlencode($theme);

            include_once ABSPATH . 'wp-admin/includes/admin.php';
            require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

            $skin = new Automatic_Upgrader_Skin(compact('nonce', 'url', 'theme'));
            $upgrader = new Theme_Upgrader($skin);

            $response = $upgrader->upgrade($theme);

            if (is_wp_error($response)) {
                return rest_ensure_response([
                    'success' => false,
                    'data' => [
                        'errors' => [
                            'theme' => esc_html__('We have not been able to update the theme. Please contact us for more information.', 'husky-press'),
                        ],
                    ],
                ]);
            }

            return rest_ensure_response([
                'success' => true,
                'data' => [
                    'message' => esc_html__('The theme has been updated successfully.', 'husky-press'),
                ],
            ]);
        } catch (Exception $e) {
            return rest_ensure_response([
                'success' => false,
                'data' => [
                    'errors' => [
                        'theme' => $e->getMessage(),
                    ],
                ],
            ]);
        }
    }

    /**
     * Activate Theme By Id.
     *
     * @param \WP_REST_Request  $request
     *
     * @return \WP_REST_Response
     */
    public function activate(WP_REST_Request $request)
    {
        $theme = $request->get_param('theme');

        if (!wp_get_theme($theme)->exists()) {
            return rest_ensure_response([
                'success' => false,
                'data' => [
                    'errors' => [
                        'theme' => __('The theme is not installed', 'husky-press'),
                    ],
                ],
            ]);
        }

        // To activate it if any errors occurs.
        $old_theme = husky_press_get_current_theme_key();

        switch_theme($theme);
        if (!husky_press_safe_update()) {
            switch_theme($old_theme);

            return rest_ensure_response([
                'success' => false,
                'data' => [
                    'errors' => [
                        'theme' => __('The theme is not installed', 'husky-press'),
                    ],
                ],
            ]);
        }

        return rest_ensure_response([
            'success' => true,
            'data' => [
                'message' => __('The theme has been activated successfully', 'husky-press'),
            ],
        ]);
    }
}