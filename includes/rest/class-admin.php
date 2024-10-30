<?php

namespace HuskyPress\Rest;

use WP_REST_Server;
use WP_REST_Controller;

defined('ABSPATH') || exit;

class Admin extends WP_REST_Controller
{
    /**
     * Shared Constructor.
     */
    public function __construct()
    {
        $this->namespace = 'husky-press/v1/admin';
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
            '/monitors',
            [
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => [$this, 'monitor_data'],
                'permission_callback' => ['\\HuskyPress\\Rest\\Helpers', 'can_access_endpoint'],
            ]
        );
        register_rest_route(
            $this->namespace,
            '/responses',
            [
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => [$this, 'responses_data'],
                'permission_callback' => ['\\HuskyPress\\Rest\\Helpers', 'can_access_endpoint'],
            ]
        );
    }

    /**
     * @return \WP_REST_Response
     */
    public function monitor_data()
    {
        if (!husky_press()->settings->get('auth.token') || !husky_press()->settings->get('auth.project_id')) {
            return rest_ensure_response([
                'success' => false,
                'data' => [],
            ]);
        }

        $response = wp_remote_get(HUSKY_PRESS_API_URL . '/monitors', [
            'timeout' => 60,
            'httpversion' => '1.1',
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => sprintf('Bearer %s', husky_press()->settings->get('auth.token', null)),
                'X-Husky-SiteURL' => site_url(),
                'X-Husky-SiteProjectID' => husky_press()->settings->get('auth.project_id', null),
            ],
        ]);

        if (is_wp_error($response)) {
            return rest_ensure_response([
                'success' => false,
                'data' => [
                    'errors' => [
                        'issue' => $response->get_error_message(),
                    ],
                ],
            ]);
        }

        $body = json_decode($response['body'], true);
        return rest_ensure_response([
            'success' => true,
            'data' => $body['data'],
        ]);
    }

    /**
     * @return \WP_REST_Response
     */
    public function responses_data()
    {
        if (!husky_press()->settings->get('auth.token') || !husky_press()->settings->get('auth.project_id')) {
            return rest_ensure_response([
                'success' => false,
                'data' => [],
            ]);
        }

        $response = wp_remote_get(HUSKY_PRESS_API_URL . '/responses', [
            'timeout' => 60,
            'httpversion' => '1.1',
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => sprintf('Bearer %s', husky_press()->settings->get('auth.token', null)),
                'X-Husky-SiteURL' => site_url(),
                'X-Husky-SiteProjectID' => husky_press()->settings->get('auth.project_id', null),
            ],
        ]);

        if (is_wp_error($response)) {
            return rest_ensure_response([
                'success' => false,
                'data' => [
                    'errors' => [
                        'issue' => $response->get_error_message(),
                    ],
                ],
            ]);
        }

        $body = json_decode($response['body'], true);
        return rest_ensure_response([
            'success' => true,
            'data' => $body['data'],
        ]);
    }
}