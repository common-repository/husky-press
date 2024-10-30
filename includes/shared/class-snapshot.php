<?php

namespace HuskyPress\Shared;

class Snapshot
{
    /**
     * Register hooks.
     *
     * @return void
     */
    public function hooks()
    {
        add_action('init', [$this, 'snapshot']);
        add_action('husky_press_snapshot_data', [$this, 'send_snapshot'], 10);
    }

    /**
     * @return void
     */
    public function snapshot()
    {
        if (!function_exists('as_schedule_recurring_action') || !husky_press()->settings->get('auth.token') || !husky_press()->settings->get('auth.project_id')) {
            return;
        }

        if (false === as_has_scheduled_action('husky_press_snapshot_data')) {
            as_schedule_recurring_action(strtotime('now'), MINUTE_IN_SECONDS * 45, 'husky_press_snapshot_data', [], 'husky-press');
        } elseif (false === as_next_scheduled_action('husky_press_snapshot_data')) {
            as_schedule_recurring_action(strtotime('now'), MINUTE_IN_SECONDS * 45, 'husky_press_snapshot_data', [], 'husky-press');
        }
    }

    /**
     * @return void
     */
    public function send_snapshot()
    {
        if (!husky_press()->settings->get('auth.token') || !husky_press()->settings->get('auth.project_id')) {
            return;
        }

        wp_remote_post(HUSKY_PRESS_API_URL . '/snapshot/wp-data', [
            'timeout' => 60,
            'httpversion' => '1.1',
            'body' => json_encode([
                'plugins' => husky_press_get_plugins(),
                'themes' => husky_press_get_themes(),
                'data' => husky_press_get_data(),
                'roles' => husky_press_get_roles(),
            ]),
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
}
