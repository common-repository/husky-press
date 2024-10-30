<?php

namespace HuskyPress\Rest;

use WP_Error;
use WP_REST_Request;

defined( 'ABSPATH' ) || exit;

class Helpers
{
	/**
	 * Determines if the current user can manage options.
	 *
	 * @return true
	 */
	public static function can_manage_options()
    {
		return current_user_can('manage_options');
	}

    /**
     * Determine whether the current user can enter this endpoint.
     *
     * @param  WP_REST_Request $request
     * @return true;
     */
    public static function can_access_endpoint(WP_REST_Request $request)
    {
        $nonce = $request->get_header('X-WP-Nonce');
        if ($nonce && wp_verify_nonce($nonce, 'wp_rest')) {
            return true;
        }

        $token = $request->get_header('X-Husky-SiteTOKEN');
        $project_id = $request->get_header('X-Husky-SiteProjectID');

        if (! $token || ! $project_id) {
            return false;
        }

        if ($project_id != husky_press()->settings->get('auth.project_id', null)) {
            return false;
        }

        if ($token != husky_press()->settings->get('auth.token', null)) {
            return false;
        }

        return true;
    }
}