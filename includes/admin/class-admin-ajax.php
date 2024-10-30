<?php

namespace HuskyPress\Admin;

use MyThemeShop\Helpers\Param;

defined( 'ABSPATH' ) || exit;

class AdminAjax
{
    /**
     * Register hooks.
     *
     * @return void
     */
    public function hooks()
    {
        add_action('wp_ajax_husky_press_login', [$this, 'login']);
    }

    /**
     * Update Auth Token.
     *
     * @return void
     */
    public function login()
    {
        check_ajax_referer('husky-press-ajax-nonce', 'nonce');

        $email = Param::post('email');
        $password = Param::post('password');

        $response = wp_remote_post(HUSKY_PRESS_API_URL . '/login', [
            'timeout' => 60,
            'httpversion' => '1.1',
            'body' => [
                'email' => $email,
                'password' => $password,
            ],
            'headers' => [
                'Accept' => 'application/json',
                'X-Husky-SiteURL' => site_url(),
            ],
            'sslverify' => false,
        ]);

        if (is_wp_error($response)) {
            wp_send_json_error([
                'errors' => [
                    'email' => [
                        $response->get_error_message(),
                    ]
                ],
            ]);
            return;
        }

        $body = json_decode($response['body'], true);

        if (! empty($body['message'])) {
            wp_send_json_error([
                'errors' => [
                    'email' => [
                        $body['message'],
                    ],
                ],
            ]);
            return;
        }

        if (! empty($body['errors'])) {
            wp_send_json_error([
                'errors' => $body['errors']
            ]);
            return;
        }

        if (! empty($body['token']) && ! empty($body['data']['project'])) {
            husky_press()->settings->set('auth', 'token', $body['token']);
            husky_press()->settings->set('auth', 'project_id', $body['data']['project']['id']);

            husky_press()->settings->save();
        }

        wp_send_json_success(array_merge($body['data'], [
            'token' => $body['token'],
        ]));
    }

    /**
     * Update Project ID.
     *
     * @return void
     */
    public function update_project_id()
    {
        check_ajax_referer('husky-press-ajax-nonce', 'nonce');

        $project_id = absint(Param::post('project_id'));

        if ($project_id) {
            husky_press()->settings->set('auth', 'project_id', $project_id);

            husky_press()->settings->save();
        }

        wp_send_json_success();
    }
}