<?php

namespace HuskyPress\Rest;

use Exception;
use WP_REST_Server;
use Plugin_Upgrader;
use WP_REST_Request;
use WP_REST_Controller;
use Automatic_Upgrader_Skin;

defined('ABSPATH') || exit;

class Plugin extends WP_REST_Controller
{
    /**
     * Shared Constructor.
     */
    public function __construct()
    {
        $this->namespace = 'husky-press/v1/plugins';
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
            '/all',
            [
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => [$this, 'plugins'],
                'permission_callback' => ['\\HuskyPress\\Rest\\Helpers', 'can_access_endpoint'],
            ]
        );
        register_rest_route(
            $this->namespace,
            '/update',
            [
                'methods'             => WP_REST_Server::CREATABLE,
                'args'                => [
                    'plugin'  => [
                        'required' => true,
                    ],
                ],
                'callback'            => [$this, 'update'],
                'permission_callback' => ['\\HuskyPress\\Rest\\Helpers', 'can_access_endpoint'],
            ]
        );
        register_rest_route(
            $this->namespace,
            '/rollback',
            [
                'methods'             => WP_REST_Server::CREATABLE,
                'args'                => [
                    'plugin'  => [
                        'required' => true,
                    ],
                    'version' => [
                        'required' => true,
                    ],
                ],
                'callback'            => [$this, 'rollback'],
                'permission_callback' => ['\\HuskyPress\\Rest\\Helpers', 'can_access_endpoint'],
            ]
        );
        register_rest_route(
            $this->namespace,
            '/activate',
            [
                'methods'             => WP_REST_Server::CREATABLE,
                'args'                => [
                    'plugin'  => [
                        'required' => true,
                    ],
                ],
                'callback'            => [$this, 'activate'],
                'permission_callback' => ['\\HuskyPress\\Rest\\Helpers', 'can_access_endpoint'],
            ]
        );
        register_rest_route(
            $this->namespace,
            '/deactivate',
            [
                'methods'             => WP_REST_Server::CREATABLE,
                'args'                => [
                    'plugin'  => [
                        'required' => true,
                    ],
                ],
                'callback'            => [$this, 'deactivate'],
                'permission_callback' => ['\\HuskyPress\\Rest\\Helpers', 'can_access_endpoint'],
            ]
        );
        register_rest_route(
            $this->namespace,
            '/tags',
            [
                'methods'             => WP_REST_Server::READABLE,
                'args'                => [
                    'plugin'  => [
                        'required' => true,
                    ],
                ],
                'callback'            => [$this, 'tags'],
                'permission_callback' => ['\\HuskyPress\\Rest\\Helpers', 'can_access_endpoint'],
            ]
        );
    }

    /**
     * Get Plugins data.
     *
     * @param \WP_REST_Request  $request
     *
     * @return \WP_REST_Response
     */
    public function plugins()
    {
        return rest_ensure_response([
            'data' => husky_press_get_plugins(),
        ]);
    }

    /**
     * Update Plugin By Id.
     *
     * @param \WP_REST_Request  $request
     *
     * @return \WP_REST_Response
     */
    public function update(WP_REST_Request $request)
    {
        $plugin = $request->get_param('plugin');

        try {
            $nonce = 'upgrade-plugin_' . $plugin;
            $url = 'update.php?action=upgrade-plugin&plugin=' . urlencode($plugin);

            update_site_option('husky_press_current_update_plugin', $plugin);

            require_once ABSPATH . 'wp-admin/includes/plugin.php';
            require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
            require_once ABSPATH . 'wp-admin/includes/file.php';

            $plugin_data = get_plugin_data(husky_press_get_plugin_dir($plugin));

            $upgrader = new Plugin_Upgrader(new Automatic_Upgrader_Skin(compact('nonce', 'url', 'plugin')));
            $response = $upgrader->upgrade($plugin);

            if (is_wp_error($response) || !husky_press_safe_update()) {
                husky_press_rollback_plugin($plugin, $plugin_data['Version']);

                return rest_ensure_response([
                    'success' => false,
                    'data' => [
                        'errors' => [
                            'plugin' => esc_html__('We have not been able to update the plugin. Please contact us for more information.', 'husky-press'),
                        ],
                    ],
                ]);
            }

            return rest_ensure_response([
                'success' => true,
                'data' => [
                    'message' => esc_html__('The plugin has been updated successfully.', 'husky-press'),
                ],
            ]);
        } catch (Exception $e) {
            return rest_ensure_response([
                'success' => false,
                'data' => [
                    'errors' => [
                        'plugin' => $e->getMessage(),
                    ],
                ],
            ]);
        }
    }

    /**
     * Rollback Plugin By Id and Verion.
     *
     * @param \WP_REST_Request  $request
     *
     * @return \WP_REST_Response
     */
    public function rollback(WP_REST_Request $request)
    {
        $plugin = $request->get_param('plugin');
        $version = $request->get_param('version');

        if (! function_exists('activate_plugin') || ! function_exists('deactivate_plugin')) {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        try {
            $is_active = is_plugin_active($plugin);

            husky_press_rollback_plugin($plugin, $version);

            if ($is_active) {
                activate_plugin($plugin, '', is_plugin_active_for_network($plugin), true);
            } else {
                deactivate_plugins($plugin, true, is_plugin_active_for_network($plugin));
            }

            return rest_ensure_response([
                'success' => true,
                'data' => [
                    'message' => esc_html__('The plugin has been rollbacked successfully.', 'husky-press'),
                ],
            ]);
        } catch (Exception $e) {
            return rest_ensure_response([
                'success' => false,
                'data' => [
                    'errors' => [
                        'plugin' => $e->getMessage(),
                    ],
                ],
            ]);
        }
    }

    /**
     * Activate Plugin By Id.
     *
     * @param \WP_REST_Request  $request
     *
     * @return \WP_REST_Response
     */
    public function activate(WP_REST_Request $request)
    {
        $plugin = $request->get_param('plugin');

        if (! function_exists('activate_plugin')) {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        try {
            activate_plugin($plugin, '', is_plugin_active_for_network($plugin), true);

            return rest_ensure_response([
                'success' => true,
                'data' => [
                    'message' => esc_html__('The plugin has been activated successfully.', 'husky-press'),
                ],
            ]);
        } catch (Exception $e) {
            return rest_ensure_response([
                'success' => false,
                'data' => [
                    'errors' => [
                        'plugin' => $e->getMessage(),
                    ],
                ],
            ]);
        }
    }

    /**
     * Deactivate Plugin By Id.
     *
     * @param \WP_REST_Request  $request
     *
     * @return \WP_REST_Response
     */
    public function deactivate(WP_REST_Request $request)
    {
        $plugin = $request->get_param('plugin');

        if (! function_exists('deactivate_plugins')) {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        try {
            deactivate_plugins($plugin, true, is_plugin_active_for_network($plugin));

            return rest_ensure_response([
                'success' => true,
                'data' => [
                    'message' => esc_html__('The plugin has been deactivated successfully.', 'husky-press'),
                ],
            ]);
        } catch (Exception $e) {
            return rest_ensure_response([
                'success' => false,
                'data' => [
                    'errors' => [
                        'plugin' => $e->getMessage(),
                    ],
                ],
            ]);
        }
    }

    /**
     * Get plugin tags.
     *
     * @param \WP_REST_Request  $request
     *
     * @return \WP_REST_Response
     */
    public function tags(WP_REST_Request $request)
    {
        $plugin = $request->get_param('plugin');
        $slug = husky_press_get_plugin_slug($plugin);

        if (! empty($slug)) {
            $url = sprintf('https://api.wordpress.org/plugins/info/1.0/%s.json', $slug);
            $response = wp_remote_get($url);

            if (wp_remote_retrieve_response_code($response) !== 200) {
                return rest_ensure_response([
                    'success' => false,
                    'data' => [
                        'message' => esc_html__('We can\'t find old versions of this plugin. Please contact us for more details.', 'husky-press'),
                    ],
                ]);
            }

            $data = json_decode(wp_remote_retrieve_body($response), true);

            return rest_ensure_response([
                'success' => true,
                'data' => husky_press_array_only($data, [
                    'name',
                    'versions',
                ]),
            ]);
        }

        return rest_ensure_response([
            'success' => true,
            'data' => [
                'message' => esc_html__('We can\'t find old versions of this plugin. Please contact us for more details.', 'husky-press'),
            ],
        ]);
    }
}