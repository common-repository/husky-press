<?php

use HuskyPress\Skins\RollbackSkin;
use HuskyPress\Skins\PluginRollbacker;

if (!function_exists('husky_press_get_current_version')) {
    /**
     * Get installed plugin's version.
     *
     * @return int
     */
    function husky_press_get_current_version()
    {
        return get_option('husky_press_version') ? get_option('husky_press_version') : husky_press()->version;
    }
}

if (!function_exists('husky_press_get_menu_icon')) {
    /**
     * Get menu icon.
     *
     * @return string
     */
    function husky_press_get_menu_icon()
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="20px" height="20px" viewBox="0 0 512.000000 512.000000" preserveAspectRatio="xMidYMid meet"><g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)" fill="black" stroke="none"><path d="M584 5101 c-40 -24 -91 -86 -98 -120 -3 -14 -95 -890 -205 -1946 -221 -2121 -211 -1970 -142 -2045 32 -36 135 -84 1002 -474 531 -240 1001 -447 1043 -460 229 -75 523 -75 752 0 42 13 512 220 1043 460 867 390 970 438 1002 474 69 75 79 -76 -142 2045 -110 1056 -202 1932 -205 1947 -8 34 -67 104 -105 123 -35 19 -129 19 -172 1 -40 -17 -83 -62 -455 -483 l-303 -342 -72 25 c-440 152 -984 194 -1477 113 -137 -23 -344 -74 -457 -113 l-72 -25 -303 342 c-372 421 -415 466 -455 483 -48 21 -142 18 -179 -5z m602 -1056 c207 -234 313 -346 338 -358 94 -44 226 -4 271 83 18 37 27 110 16 147 l-9 32 126 30 c427 103 837 103 1264 0 l126 -30 -9 -32 c-11 -37 -2 -110 16 -147 45 -88 180 -128 273 -81 25 13 144 140 362 386 266 300 326 362 328 343 10 -77 239 -2293 237 -2296 -1 -1 -93 237 -205 530 -111 293 -218 563 -237 599 -80 155 -236 263 -416 288 -139 19 -213 1 -457 -111 -336 -155 -404 -193 -474 -263 -61 -61 -114 -148 -151 -247 -10 -27 -21 -48 -24 -48 -4 0 -19 34 -35 76 -50 131 -136 235 -251 306 -43 26 -443 216 -535 254 -154 63 -364 45 -504 -44 -61 -38 -136 -112 -175 -173 -24 -35 -113 -256 -251 -616 -118 -309 -216 -561 -217 -560 -1 1 52 518 118 1147 65 630 119 1154 119 1165 1 18 4 16 28 -10 15 -17 163 -183 328 -370z m634 -1023 c129 -61 251 -124 270 -140 31 -25 51 -69 164 -351 72 -180 140 -336 154 -353 69 -82 210 -89 290 -15 34 31 55 76 182 396 68 171 124 296 139 313 37 40 501 259 561 265 52 5 103 -17 133 -58 16 -22 740 -1900 734 -1905 -8 -7 -1555 -701 -1603 -720 -158 -59 -338 -68 -494 -24 -41 12 -325 134 -630 272 -305 137 -666 300 -801 360 l-247 111 23 61 c239 632 698 1821 711 1841 9 14 33 35 53 46 63 34 103 23 361 -99z"/><path d="M1711 2595 c-142 -44 -191 -242 -88 -354 71 -77 227 -81 305 -8 117 109 71 317 -81 362 -60 18 -78 18 -136 0z"/><path d="M3269 2594 c-92 -28 -160 -141 -142 -236 21 -111 102 -178 215 -178 126 1 209 87 209 216 0 92 -59 174 -145 199 -60 18 -77 18 -137 -1z"/><path d="M2210 1699 c-41 -16 -80 -60 -91 -103 -22 -80 -12 -96 174 -284 95 -96 184 -180 199 -188 15 -8 45 -14 68 -14 23 0 53 6 68 14 15 8 104 92 199 188 140 141 173 180 178 209 14 74 -29 152 -98 178 -37 14 -662 14 -697 0z"/></g></svg>';
    }
}

if (!function_exists('husky_press_get_plugin_slug')) {
    /**
     * Get plugin's slug from it's filename.
     *
     * @param string $file
     *
     * @return string
     */
    function husky_press_get_plugin_slug(string $file)
    {
        $explode = explode('/', $file);
        if (isset($explode[0])) {
            return $explode[0];
        }

        return '';
    }
}

if (!function_exists('husky_press_array_only')) {
    /**
     * Return specific key-pairs from array by keys.
     *
     * @param array $array
     * @param array $keys
     *
     * @return array
     */
    function husky_press_array_only(array $array, array $keys)
    {
        return array_filter($array, function ($arr) use ($keys) {
            return in_array($arr, $keys);
        }, ARRAY_FILTER_USE_KEY);
    }
}

if (!function_exists('husky_press_get_plugin_dir')) {
    /**
     * Return plugin's directory path.
     *
     * @param string $plugin
     *
     * @return string
     */
    function husky_press_get_plugin_dir(string $plugin)
    {
        return WP_PLUGIN_DIR . '/' . $plugin;
    }
}

if (!function_exists('husky_press_get_saved_errors')) {
    /**
     * Return a collection of saved errors.
     *
     * @return array
     */
    function husky_press_get_saved_errors()
    {
        return husky_press()->errors->all();
    }
}

if (!function_exists('husky_press_delete_saved_errors')) {
    /**
     * Delete saved errors.
     *
     * @return boolean
     */
    function husky_press_delete_saved_errors()
    {
        return husky_press()->errors->delete();
    }
}

if (!function_exists('husky_press_can_send_error')) {
    /**
     * @return boolean
     */
    function husky_press_can_send_error()
    {
        return ! isset($error['file'], $error['code'], $error['line'], $error['message']);
    }
}

if (!function_exists('husky_press_prepare_error_for_request')) {
    /**
     * @param array $error
     * @return array
     */
    function husky_press_prepare_error_for_request(string $key, array $error)
    {
        $clean = str_replace(realpath(ABSPATH), '', $error['file']);
        $stylesheet = get_stylesheet_directory();
        $template = get_template_directory();

        if (false !== strpos($error['file'], realpath(WP_PLUGIN_DIR)) ? $clean : false) {
            $type = 'plugin';
        } elseif ($stylesheet != $template && false !== strpos($error['file'], realpath($stylesheet) . '\\') ? $clean : false) {
            $type = 'child_theme';
        } elseif (false !== strpos($error['file'], realpath($template)) ? $clean : false) {
            $type = 'parent_theme';
        } elseif (false !== strpos($error['file'], realpath(WP_CONTENT_DIR)) ? $clean : false) {
            $type = 'content';
        } else {
            $type = 'unknown';
        }

        $data = [
            'type' => $type,
            'uuid' => $key,
            'file' => $error['file'],
            'line' => $error['line'],
            'code' => $error['code'],
            'message' => $error['message'],
            'php_version' => phpversion(),
            'wordpress_version' => get_bloginfo('version'),
        ];

        $data = array_filter($data);

        switch ($type) {
            case 'plugin':
                $errorFromBonus = trim(dirname(str_replace(realpath(WP_PLUGIN_DIR), '', $error['file'])), '\\');
                $errorFromBonusArray = array_values(array_filter(explode('/', $errorFromBonus)));
                $errorFromBonusArray = array_values(array_filter(explode('/', $errorFromBonus)));
                $slug = $errorFromBonusArray[0];

                $plugins = get_plugins('/' . $slug);

                if ($plugin = reset($plugins)) {
                    $data['slug'] = $slug;
                    $data['name'] = $plugin['Name'];
                    $data['title'] = $plugin['Title'];
                    $data['version'] = $plugin['Version'];
                    $data['author'] = $plugin['Author'];
                    $data['author_name'] = $plugin['AuthorName'];
                    $data['author_uri'] = $plugin['AuthorURI'];
                    $data['uri'] = $plugin['PluginURI'];
                }
                break;
            case 'parent_theme':
            case 'child_theme':
                $theme = wp_get_theme();

                if ($theme) {
                    $data['name'] = $theme->name;
                    $data['title'] = $theme->title;
                    $data['description'] = $theme->description;
                    $data['version'] = $theme->version;
                    $data['author'] = $theme->author;
                    $data['author_ui'] = $theme->author_ui;
                    $data['parent_theme'] = $theme->parent_theme;
                    $data['template'] = $theme->template;
                    $data['stylesheet'] = $theme->stylesheet;
                }
                break;
        }

        return $data;
    }
}

if (!function_exists('husky_press_rollback_plugin')) {
    /**
     * Rollback an exisiting plugin.
     *
     * @param string $plugin
     * @param string $version
     *
     * @return void
     */
    function husky_press_rollback_plugin(string $plugin, string $version)
    {
        $plugin_data = get_plugin_data(husky_press_get_plugin_dir($plugin));

        $name = $plugin_data['Name'];
        $slug = husky_press_get_plugin_slug($plugin);
        $nonce = 'upgrade-plugin_' . $slug;
        $url = 'update.php?action=upgrade-plugin&plugin=' . urlencode($plugin);

        $upgrader = new PluginRollbacker(new RollbackSkin([
            'title' => $name,
            'nonce' => $nonce,
            'url' => $url,
            'plugin' => $slug,
            'version' => $version,
        ]));
        $upgrader->rollback($plugin);
    }
}

if (!function_exists('husky_press_safe_update')) {
    /**
     * Determine whether the plugin was updated successfully.
     *
     * @param string $action
     * @return boolean
     */
    function husky_press_safe_update($action = 'wpu_activate_plugins')
    {
        if (function_exists('set_time_limit')) {
            @set_time_limit(300);
        }

        $scrape_key = md5(rand());
        $scrape_nonce = wp_create_nonce('husky_press_safe_update_nonce');

        set_transient("scrape_key_{$scrape_key}", $scrape_nonce, 60);

        $needle_start = "###### wp_scraping_result_start:$scrape_key ######";
		$needle_end = "###### wp_scraping_result_end:$scrape_key ######";
		$scrape_params = [
			'wp_scrape_key' => $scrape_key,
			'wp_scrape_nonce' => $scrape_nonce,
        ];
        if (!empty($action)) {
            $scrape_params['wp_scrape_action'] = $action;
        }

        $response = wp_remote_get(add_query_arg($scrape_params, home_url('/')), [
            'timeout' => 60,
            'httpversion' => '1.1',
            'headers' => [
                'Cache-Control' => 'no-cache',
            ],
            'sslverify' => false,
        ]);

        if (is_wp_error($response)) {
            return false;
        }

        $body = $response['body'];
        $scrape_result_position = strpos($body, $needle_start);

        delete_transient("scrape_key_{$scrape_key}");

		$result = null;
		if (false === $scrape_result_position) {
			return false;
		} else {
			$error_output = substr($body, $scrape_result_position + strlen($needle_start));
			$error_output = substr($error_output, 0, strpos($error_output, $needle_end));

            $result = json_decode(trim($error_output), true);
			if (empty($result)) {
				return false;
			}
		}

        return true;
    }
}

if (!function_exists('husky_press_get_plugins')) {
    /**
     * Return all plugins.
     *
     * @return array
     */
    function husky_press_get_plugins()
    {
        wp_update_plugins();

        if (! function_exists('get_plugin_updates') || ! function_exists('get_plugins')) {
            include_once ABSPATH . '/wp-admin/includes/plugin.php';
            include_once ABSPATH . '/wp-admin/includes/update.php';
        }

        $plugins = get_plugins();

        $i = 0;
        $data = [];
        foreach ($plugins as $key => $plugin) {
            $data[$i] = $plugin;
            $data[$i]['key'] = $key;
            $data[$i]['slug'] = husky_press_get_plugin_slug($key);
            $data[$i]['active'] = is_plugin_active($key);

            ++$i;
        }

        $needUpdates = get_plugin_updates();
        if (! empty($needUpdates)) {
            foreach ($needUpdates as $plugin) {
                $index = array_search($plugin->Name, array_column($data, 'Name'));

                $data[$index]['need_update'] = true;
                $data[$index]['update'] = $plugin->update;
            }
        }

        return $data;
    }
}

if (!function_exists('husky_press_get_themes')) {
    /**
     * @return array
     */
    function husky_press_get_themes()
    {
        require_once ABSPATH . '/wp-admin/includes/theme.php';

        $themes = wp_get_themes();
        $active = get_option('current_theme');

        wp_update_themes();

        if (function_exists('get_site_transient') && $transient = get_site_transient('update_themes')) {
            $current = $transient;
        } elseif ($transient = get_transient('update_themes')) {
            $current = $transient;
        } else {
            $current = get_option('update_themes');
        }

        $data = [];
        foreach ($themes as $key => $theme) {
            $new_version = isset($current->response[$theme->get_stylesheet()]) ? $current->response[$theme->get_stylesheet()]['new_version'] : null;

            $data[$key] = [
                'name' => $theme->get('Name'),
                'active' => $active == $theme->get('Name'),
                'template' => $theme->get_template(),
                'stylesheet' => $theme->get_stylesheet(),
                'screenshot' => $theme->get_screenshot(),
                'author_uri' => $theme->get('AuthorURI'),
                'author' => $theme->get('Author'),
                'latest_version' => $new_version ? $new_version : $theme->get('Version'),
                'version' => $theme->get('Version'),
                'theme_uri' => $theme->get('ThemeURI'),
                'require_wp' => $theme->get('RequiresWP'),
                'requires_php' => $theme->get('RequiresPHP'),
            ];
        }

        return $data;
    }
}

if (! function_exists('husky_press_get_current_theme_key')) {
    /**
     * @return string
     */
    function husky_press_get_current_theme_key()
    {
        $themes = wp_get_themes();
        $active = get_option('current_theme');

        foreach ($themes as $key => $theme) {
            if ($active != $theme->get('Name')) {
                continue;
            }

            return $key;
        }
    }
}

if (!function_exists('husky_press_get_data')) {
    /**
     * @return array
     */
    function husky_press_get_data()
    {
        return [
            'is_indexable' => (int) get_option('blog_public') === 1,
            'is_ssl' => is_ssl(),
            'is_multisite' => is_multisite(),
            'urls' => [
                'base_url' => site_url(),
                'admin_url' => admin_url(),
                'rest_url' => rest_url(),
            ],
            'defines' => [
                'defined_wp_debug'         => defined('WP_DEBUG') ? WP_DEBUG : false,
                'defined_wp_debug_log'     => defined('WP_DEBUG_LOG') ? WP_DEBUG_LOG : false,
                'defined_wp_debug_display' => defined('WP_DEBUG_DISPLAY') ? WP_DEBUG_DISPLAY : false,
            ],
            'php' => husky_press_get_php_data(),
            'php_extensions' => husky_press_get_php_extensions(),
            'up_to_date' => husky_press_get_up_to_date_data(),
        ];
    }
}

if (!function_exists('husky_press_get_roles')) {
    /**
     * @return array
     */
    function husky_press_get_roles()
    {
        if (!function_exists('get_editable_roles')) {
            include_once ABSPATH . '/wp-admin/includes/user.php';
        }

        $roles = get_editable_roles();

        $data = [];
        foreach ($roles as $key => $role) {
            $data[] = [
                'key' => $key,
                'name' => $role['name'],
            ];
        }

        return $data;
    }
}

if (!function_exists('husky_press_get_php_extensions')) {
    /**
     * @return array
     */
    function husky_press_get_php_extensions()
    {
        $modules = [
            'curl' => [
                'function'     => 'curl_version',
                'key'          => 'curl',
                'is_installed' => true,
            ],
            'dom' => [
                'class'        => 'DOMNode',
                'key'          => 'dom',
                'is_installed' => true,
            ],
            'exif' => [
                'function'     => 'exif_read_data',
                'key'          => 'exif',
                'is_installed' => true,
            ],
            'fileinfo' => [
                'function'     => 'finfo_file',
                'key'          => 'fileinfo',
                'is_installed' => true,
            ],
            'hash' => [
                'function'     => 'hash',
                'key'          => 'hash',
                'is_installed' => true,
            ],
            'json' => [
                'function'     => 'json_last_error',
                'key'          => 'json',
                'is_installed' => true,
            ],
            'mbstring'  => [
                'function'     => 'mb_check_encoding',
                'key'          => 'mbstring',
                'is_installed' => true,
            ],
            'mysqli' => [
                'function'     => 'mysqli_connect',
                'key'          => 'mysqli',
                'is_installed' => true,
            ],
            'libsodium' => [
                'constant'            => 'SODIUM_LIBRARY_VERSION',
                'key'                 => 'libsodium',
                'is_installed'        => true,
                'php_bundled_version' => '7.2.0',
            ],
            'openssl' => [
                'function'     => 'openssl_encrypt',
                'key'          => 'openssl',
                'is_installed' => true,
            ],
            'pcre' => [
                'function'     => 'preg_match',
                'key'          => 'pcre',
                'is_installed' => true,
            ],
            'imagick' => [
                'extension'    => 'imagick',
                'key'          => 'imagick',
                'is_installed' => true,
            ],
            'mod_xml' => [
                'extension'    => 'libxml',
                'key'          => 'mod_xml',
                'is_installed' => true,
            ],
            'zip' => [
                'class'        => 'ZipArchive',
                'key'          => 'zip',
                'is_installed' => true,
            ],
            'filter' => [
                'function'     => 'filter_list',
                'key'          => 'filter',
                'is_installed' => true,
            ],
            'gd' => [
                'extension'    => 'gd',
                'key'          => 'gd',
                'is_installed' => true,
                'fallback_for' => 'imagick',
            ],
            'iconv' => [
                'function'     => 'iconv',
                'key'          => 'iconv',
                'is_installed' => true,
            ],
            'mcrypt' => [
                'extension'    => 'mcrypt',
                'key'          => 'mcrypt',
                'is_installed' => true,
                'fallback_for' => 'libsodium',
            ],
            'simplexml' => [
                'extension'    => 'simplexml',
                'key'          => 'simplexml',
                'is_installed' => true,
                'fallback_for' => 'mod_xml',
            ],
            'xmlreader' => [
                'extension'    => 'xmlreader',
                'key'          => 'xmlreader',
                'is_installed' => true,
                'fallback_for' => 'mod_xml',
            ],
            'zlib' => [
                'extension'    => 'zlib',
                'key'          => 'zlib',
                'is_installed' => true,
                'fallback_for' => 'zip',
            ],
        ];

        foreach ($modules as $library => $module) {
            $extension = (isset($module['extension']) ? $module['extension'] : null);
            $function = (isset($module['function']) ? $module['function'] : null);
            $constant = (isset($module['constant']) ? $module['constant'] : null);
            $class_name = (isset($module['class']) ? $module['class'] : null);

            if (isset($module['fallback_for'])) {
                continue;
            }

            if (!husky_press_php_extension_compatibility($extension, $function, $constant, $class_name) && (!isset($module['php_bundled_version']) || version_compare(PHP_VERSION, $module['php_bundled_version'], '<'))) {
                $modules[$library]['is_installed'] = false;
            }
        }

        return $modules;
    }
}

if (!function_exists('husky_press_get_up_to_date_data')) {
    /**
     * @return array
     */
    function husky_press_get_up_to_date_data()
    {
        global $wp_version;

        $core_update = false;

        $from_api = get_site_option('_site_transient_update_core');
        if (isset($from_api->updates) && is_array($from_api->updates)) {
            $core_update = $from_api->updates;
        }

        $isUpToDate = $core_update && (!isset($core_update[0]->response) || 'latest' == $core_update[0]->response);

        return [
            'latest' => $core_update && isset($core_update[0]) ? $core_update[0] : null,
            'is_up_to_date' => $isUpToDate,
            'wordpress_version' => $wp_version,
        ];
    }
}

if (!function_exists('husky_press_get_php_data')) {
    /**
     * @return array
     */
    function husky_press_get_php_data()
    {
        if (! function_exists('wp_check_php_version')) {
            include_once ABSPATH . '/wp-admin/includes/misc.php';
        }

        $response = wp_check_php_version();

        return [
            'current_version'        => PHP_VERSION,
            'recommended_version'    => $response['recommended_version'],
            'is_secure'              => $response['is_secure'],
            'is_supported'           => $response['is_supported'],
        ];
    }
}

if (!function_exists('husky_press_php_extension_compatibility')) {
    /**
     * @return boolean
     */
    function husky_press_php_extension_compatibility($extension = null, $function = null, $constant = null, $class = null)
    {
        if (! $extension && ! $function && ! $constant && ! $class) {
            return false;
        }
        if ($extension && ! extension_loaded($extension)) {
            return false;
        }
        if ($function && ! function_exists($function)) {
            return false;
        }
        if ($constant && ! defined($constant)) {
            return false;
        }
        if ($class && ! class_exists($class)) {
            return false;
        }

        return true;
    }
}

if (!function_exists('husky_press_permant_save_errors')) {
    /**
     * Permant save stored errors.
     *
     * @return void
     */
    function husky_press_permant_save_errors()
    {
        husky_press()->errors->save();
    }
}

if (!function_exists('husky_press_get_permant_saved_errors')) {
    /**
     * Return permant saved errors.
     *
     * @return void
     */
    function husky_press_get_permant_saved_errors()
    {
        return husky_press()->errors->get_saved();
    }
}

if (!function_exists('husky_press_get_max_execution_time')) {
    /**
     * @return int
     */
    function husky_press_get_max_execution_time()
    {
        try {
            return @ini_get('max_execution_time');
        } catch (\Exception $e) {
            return 300;
        }
    }
}

if (!function_exists('husky_press_max_files_by_batch')) {
    /**
     * @return int
     */
    function husky_press_max_files_by_batch()
    {
        $default = 2000;
        if (husky_press_get_max_execution_time() < 250) {
            $default = 1000;
        }

        return apply_filters('husky_press_max_files_by_batch', $default);
    }
}

if (!function_exists('husky_press_remove_dir')) {
    /**
     * Remove directory and it's contents.
     *
     * @param string $dir
     * @return boolean
     */
    function husky_press_remove_dir($dir)
    {
        if (!is_dir($dir) || is_link($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $file) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            if (!husky_press_remove_dir($dir . DIRECTORY_SEPARATOR . $file)) {
                chmod($dir . DIRECTORY_SEPARATOR . $file, 0777);
                if (!husky_press_remove_dir($dir . DIRECTORY_SEPARATOR . $file)) {
                    return false;
                }
            }
        }
        return rmdir($dir);
    }
}

if (!function_exists('husky_press_build_mysqldump_list')) {
    /**
     * For finding mysqldump. Added to include Windows locations.
     *
     * @return string
     */
    function husky_press_build_mysqldump_list()
    {
        if ('win' == strtolower(substr(PHP_OS, 0, 3)) && function_exists('glob')) {
            $drives = array('C', 'D', 'E');

            if (!empty($_SERVER['DOCUMENT_ROOT'])) {
                $current_drive = strtoupper(substr($_SERVER['DOCUMENT_ROOT'], 0, 1));
                if (!in_array($current_drive, $drives)) array_unshift($drives, $current_drive);
            }

            $directories = array();

            foreach ($drives as $drive_letter) {
                $dir = glob("$drive_letter:\\{Program Files\\MySQL\\{,MySQL*,etc}{,\\bin,\\?},mysqldump}\\mysqldump*", GLOB_BRACE);
                if (is_array($dir)) $directories = array_merge($directories, $dir);
            }

            $drive_string = implode(',', $directories);
            return $drive_string;
        } else {
            return '/usr/bin/mysqldump,/bin/mysqldump,/usr/local/bin/mysqldump,/usr/sfw/bin/mysqldump,/usr/xdg4/bin/mysqldump,/opt/bin/mysqldump';
        }
    }
}

if (!function_exists('husky_press_get_mysql_version')) {
    /**
     * Get installed MySQL version.
     *
     * @return int
     */
    function husky_press_get_mysql_version()
    {
        global $wpdb;

        $mysql_version = $wpdb->get_var('SELECT VERSION()');
        if (empty($mysql_version)) {
            $mysql_version = $wpdb->db_version();
        }

        return $mysql_version;
    }
}