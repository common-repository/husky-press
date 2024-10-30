<?php

namespace HuskyPress\Skins;

if (! class_exists('Plugin_Upgrader')) {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
    include_once ABSPATH . '/wp-admin/includes/admin.php';
    include_once ABSPATH . '/wp-admin/includes/plugin-install.php';
    include_once ABSPATH . '/wp-admin/includes/plugin.php';
    include_once ABSPATH . '/wp-admin/includes/class-wp-upgrader.php';
    include_once ABSPATH . '/wp-admin/includes/class-plugin-upgrader.php';
}

defined('ABSPATH') || exit;

class PluginRollbacker extends \Plugin_Upgrader
{
    /**
     * Rollback a plugin to specific version.
     *
     * @param string $plugin
     * @param array $args
     *
     * @return boolean
     */
    public function rollback($plugin, $args = [])
    {
        $defaults = [
            'clear_update_cache' => true,
        ];

        $args = wp_parse_args($args, $defaults);

        $this->init();
        $this->upgrade_strings();

        $url = sprintf('https://downloads.wordpress.org/plugin/%s.%s.zip',$this->skin->plugin, $this->skin->options['version']);

        add_filter('upgrader_pre_install', [$this, 'deactivate_plugin_before_upgrade' ], 10, 2);
        add_filter('upgrader_clear_destination', [$this, 'delete_old_plugin' ], 10, 4);

        $this->run([
            'package'           => $url,
            'destination'       => WP_PLUGIN_DIR,
            'clear_destination' => true,
            'clear_working'     => true,
            'hook_extra'        => [
                'plugin' => $plugin,
                'type'   => 'plugin',
                'action' => 'update',
            ],
        ]);

        remove_filter('upgrader_pre_install', [$this, 'deactivate_plugin_before_upgrade']);
        remove_filter('upgrader_clear_destination', [$this, 'delete_old_plugin']);

        if (! $this->result || is_wp_error($this->result)) {
            return $this->result;
        }

        wp_clean_plugins_cache($args['clear_update_cache']);

        return true;
    }
}