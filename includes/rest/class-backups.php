<?php

namespace HuskyPress\Rest;

use WP_REST_Server;
use WP_REST_Request;
use WP_REST_Controller;

defined('ABSPATH') || exit;

class Backups extends WP_REST_Controller
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
            '/backups',
            [
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => [$this, 'create_backup'],
                'permission_callback' => ['\\HuskyPress\\Rest\\Helpers', 'can_access_endpoint'],
            ]
        );
    }

    /**
     * Create backup request.
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function create_backup(WP_REST_Request $request)
    {
        if (husky_press()->backups->in_proccess()) {
            return rest_ensure_response([
                'success' => false,
                'data' => [
                    'message' => esc_html__('A backup is already in process. Please wait.', 'husky-press'),
                ],
            ]);
        }

        $backups = husky_press()->backups;

        // Setup backup profile from request.
        $backups->profile()->data([
            'name' => $request->get_param('name'),
            'suffix' => $request->get_param('suffix'),
            'prefix' => $request->get_param('prefix'),
            'processor' => $request->get_param('processor'),
            'base_dir' => $request->get_param('base_dir'),
            'exclude_files' => $request->get_param('exclude_files'),
            'incremental_date' => $request->get_param('incremental_date'),
            'files_enabled' => $request->get_param('files_enabled'),
            'sql_enabled' => $request->get_param('sql_enabled'),
            'sql_includes' => $request->get_param('sql_includes'),
            'sql_excludes' => $request->get_param('sql_excludes'),
            'destination' => $request->get_param('destination'),
        ]);

        // Schedule backup.
        $backups->schedule();

        return rest_ensure_response([
            'success' => true,
            'data' => [
                'message' => esc_html__('A backup proccess has been scheduled successfully.', 'husky-press'),
            ],
        ]);
    }
}
