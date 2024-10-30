<?php

namespace HuskyPress\Backups\Types;

use Coderatio\SimpleBackup\SimpleBackup;
use HuskyPress\Helpers\Interfaces\BackupSource;

class MySQLManualSource implements BackupSource
{
    /**
     * @var string
     */
    private string $name;

    /**
     * @var string
     */
    private string $database;

    /**
     * @var string
     */
    private string $user;

    /**
     * @var string
     */
    private string $password;

    /**
     * @var string
     */
    private string $host;

    /**
     * @var array
     */
    private array $includes = [];

    /**
     * @var array
     */
    private array $excludes = [];

    /**
     * MySQLManual Constructor.
     */
    public function __construct($name, $database, $user, $password, $host, $includes = [], $excludes = [])
    {
        $this->name = $name;
        $this->database = $database;
        $this->user = $user;
        $this->password = $password;
        $this->host = $host;
        $this->includes = $includes;
        $this->excludes = $excludes;
    }

    /**
     * Fetch files.
     *
     * @param string $destination
     * @return void
     */
    public function fetch($destination = null)
    {
        if (!function_exists('wp_mkdir_p')) {
            require_once ABSPATH . 'wp-includes/functions.php';
        }

        $destination = sprintf('%s/%s', $destination, $this->name());

        if (!file_exists($destination)) {
            wp_mkdir_p($destination);
            chmod($destination, 0777);
        }

        $instance = SimpleBackup::setDatabase([
            $this->database,
            $this->user,
            $this->password,
            $this->host,
        ]);

        if (!empty($this->includes)) {
            $instance = $instance->includeOnly($this->includes);
        }
        if (!empty($this->excludes)) {
            $instance = $instance->excludeOnly($this->excludes);
        }

        $instance = $instance->storeAfterExportTo($destination, $this->database);
    }

    /**
     * Generate name for this source.
     *
     * @return string
     */
    public function name()
    {
        return $this->name;
    }
}
