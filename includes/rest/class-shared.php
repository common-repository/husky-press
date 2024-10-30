<?php

namespace HuskyPress\Rest;

use WP_Error;
use WP_REST_Server;
use WP_REST_Request;
use WP_REST_Controller;
use MyThemeShop\Helpers\Str;

defined('ABSPATH') || exit;

class Shared extends WP_REST_Controller
{
    /**
     * Shared Constructor.
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
            '/wordpress',
            [
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => [$this, 'wordpress_data'],
                'permission_callback' => ['\\HuskyPress\\Rest\\Helpers', 'can_access_endpoint'],
            ]
        );
    }

    /**
     * Get WordPress data.
     *
     * @return \WP_REST_Response
     */
    public function wordpress_data()
    {
        return rest_ensure_response(husky_press_get_data());
    }
}