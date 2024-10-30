<?php

namespace HuskyPress\Backups\Destinations;

use CURLFile;
use HuskyPress\Helpers\Interfaces\BackupDestination;

class HuskyPressDestination implements BackupDestination
{
    /**
     * @var string
     */
    private $name;

    /**
     * HuskyPressDestination Constructor.
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @param array $options
     */
    public function send($destination = null, $options = [])
    {
        $name = sprintf('%s.%s', $this->name(), $options['extension'] ?? 'zip');
        $filename = @realpath(sprintf('%s/%s', $destination, $name));

        if (!file_exists($filename)) {
            return;
        }

        if (husky_press()->settings->get('auth.token', null) && husky_press()->settings->get('auth.project_id', null)) {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, HUSKY_PRESS_API_URL . '/backups');
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 900);
            curl_setopt($ch, CURLOPT_TIMEOUT, 900);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, [
                'file' => new CURLFile($filename),
            ]);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: ' . 'multipart/form-data',
                'Accept: ' . 'application/json',
                'Authorization: ' . sprintf('Bearer %s', husky_press()->settings->get('auth.token', null)),
                'X-Husky-SiteURL: ' . site_url(),
                'X-Husky-SiteProjectID: ' . husky_press()->settings->get('auth.project_id', null),
            ]);
            curl_exec($ch);
            curl_close($ch);
        }
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }
}
