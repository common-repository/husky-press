<?php

namespace HuskyPress\Rest;

use WP_Error;
use WP_REST_Server;
use WP_REST_Request;
use WP_REST_Controller;

defined('ABSPATH') || exit;

class Issues extends WP_REST_Controller
{
    /**
     * Shared Constructor.
     */
    public function __construct()
    {
        $this->namespace = 'husky-press/v1/issues';
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
                'callback'            => [$this, 'issues'],
                'permission_callback' => ['\\HuskyPress\\Rest\\Helpers', 'can_access_endpoint'],
            ]
        );
        register_rest_route(
            $this->namespace,
            '/fixed',
            [
                'methods'             => WP_REST_Server::CREATABLE,
                'args'                => [
                    'issue'  => [
                        'required' => true,
                    ],
                ],
                'callback'            => [$this, 'mark_as_fixed'],
                'permission_callback' => ['\\HuskyPress\\Rest\\Helpers', 'can_access_endpoint'],
            ]
        );
    }

    /**
     * Return all stored issues.
     *
     * @return WP_REST_Response
     */
    public function issues()
    {
        $response = array_reverse(husky_press()->errors->get_saved());

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

        return rest_ensure_response([
            'success' => true,
            'data' => $response,
        ]);
    }

    /**
     * @return void
     */
    public function mark_as_fixed(WP_REST_Request $request)
    {
        $issue = $request->get_param('issue');

        husky_press()->errors->remove_saved($issue);

        if (husky_press()->settings->get('auth.token', null) && husky_press()->settings->get('auth.project_id', null)) {
            wp_remote_post(HUSKY_PRESS_API_URL . "/errors/{$issue}", [
                'method' => 'PUT',
                'timeout' => 60,
                'httpversion' => '1.1',
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'Authorization' => sprintf('Bearer %s', husky_press()->settings->get('auth.token', null)),
                    'X-Husky-SiteURL' => site_url(),
                    'X-Husky-SiteProjectID' => husky_press()->settings->get('auth.project_id', null),
                ],
                'sslverify' => false,
            ]);
        }

        return rest_ensure_response([
            'success' => true,
        ]);
    }
}