<?php

namespace HuskyPress\Shared;

use MyThemeShop\Helpers\Param;

defined('ABSPATH') || exit;

class SafeUpdate
{
    /**
     * Register hooks.
     *
     * @return void
     */
    public function hooks()
    {
        add_action('init', [$this, 'safe_update']);
    }

    /**
     * @return void
     */
    public function safe_update()
    {
		if(! defined('WP_SANDBOX_SCRAPING')){
			return;
		}

        if (WP_SANDBOX_SCRAPING && isset($_REQUEST['wp_scrape_key']) && isset($_REQUEST['wp_scrape_nonce']) && isset($_REQUEST['wp_scrape_action']) && 'wpu_activate_plugins' === $_REQUEST['wp_scrape_action']) {
            if (get_transient('scrape_key_' . substr(sanitize_key(wp_unslash($_REQUEST['wp_scrape_key'])), 0, 32)) !== wp_unslash($_REQUEST['wp_scrape_nonce'])) {
                return;
            }

            $plugin = get_site_option('husky_press_current_update_plugin');
			if(! $plugin){
				return;
			}

            activate_plugin($plugin, '', is_plugin_active_for_network($plugin), true);

            return;
        }
    }
}