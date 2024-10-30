<?php

namespace HuskyPress\Shared;

defined('ABSPATH') || exit;

class TrackErrors
{
    /**
     * Register hooks.
     *
     * @return void
     */
    public function hooks()
    {
        set_error_handler([$this, 'error_handler']);
        register_shutdown_function([$this, 'shutdown_handler']);

        add_action('plugins_loaded', [$this, 'send_errors'], 10, 1);
    }

    /**
     * Handle error.
     *
     * @return void
     */
    public function error_handler($code, $message, $file, $line, $ctx = [])
    {
        $serialize = md5(vsprintf('%s-%s-%s-%s', [$file, $line, $code, $message]));

        if (!husky_press()->errors->has($serialize) && !husky_press()->errors->already_sent($serialize)) {
            husky_press()->errors->set($serialize, [
                'message' => $message,
                'file'    => $file,
                'code'    => $code,
                'line'    => $line,
                'date'    => current_time('Y-m-d H:i'),
            ]);
        }
    }

    /**
     * @return void
     */
    public function shutdown_handler()
    {
        $lastError = error_get_last();

        if (null !== $lastError) {
            $this->error_handler($lastError['type'], $lastError['message'], $lastError['file'], $lastError['line']);
        }
    }

    /**
     * Send stored errors to our systems.
     *
     * @return void
     */
    public function send_errors()
    {
        if (empty(husky_press()->settings->get('auth.token', null)) || empty(husky_press()->settings->get('auth.project_id', null))) {
            return;
        }

        $errors = husky_press_get_saved_errors();
        if (empty($errors)) {
            return;
        }

        husky_press_permant_save_errors();
        foreach ($errors as $key => $error) {
            if (!husky_press_can_send_error($error) || is_string($error) || husky_press()->errors->already_sent($key)) {
                continue;
            }

            $body = husky_press_prepare_error_for_request($key, $error);
            wp_remote_post(HUSKY_PRESS_API_URL . '/errors', [
                'timeout' => 60,
                'httpversion' => '1.1',
                'body' => json_encode($body),
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'Authorization' => sprintf('Bearer %s', husky_press()->settings->get('auth.token', null)),
                    'X-Husky-SiteURL' => site_url(),
                    'X-Husky-SiteProjectID' => husky_press()->settings->get('auth.project_id', null),
                ],
                'sslverify' => false,
            ]);

            husky_press()->errors->mark_as_sent($key);

            husky_press_delete_saved_errors();
        }
    }
}