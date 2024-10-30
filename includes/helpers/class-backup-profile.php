<?php

namespace HuskyPress\Helpers;

use Symfony\Component\Finder\Finder;
use HuskyPress\Helpers\Interfaces\BackupProfileInterface;

class BackupProfile implements BackupProfileInterface
{
    /**
     * @var string
     */
    private string $name;

    /**
     * @var string
     */
    private string $processor = 'zip';

    /**
     * @var array
     */
    private array $data = [];

    /**
     * BackupProfile Constructor.
     */
    public function __construct()
    {
        $this->name = sprintf('Backup-%s-%s', get_bloginfo('name'), date('Y-m-d-H'));
    }

    /**
     * Setup the backup profile.
     */
    public function data(array $data)
    {
        if (isset($data['name']) && !empty($data['name'])) {
            $this->name = sanitize_text_field($data['name']);
        }
        if (isset($data['suffix']) && !empty($data['suffix'])) {
            $this->suffix($data['suffix']);
        }
        if (isset($data['prefix']) && !empty($data['prefix'])) {
            $this->prefix($data['prefix']);
        }
        if (isset($data['processor']) && !empty($data['processor'])) {
            $this->processor = $data['processor'];
        }

        $base_dir = isset($data['base_dir']) ? $data['base_dir'] : $this->default_base_dir();
        $excludes = isset($data['exclude_files']) && is_array($data['exclude_files']) ? $data['exclude_files'] : $this->default_excludes();
        $incremental_date = isset($data['incremental_date']) ? sanitize_text_field($data['incremental_date']) : null;

        $this->set('files', [
            'enabled' => isset($data['files_enabled']) ? (bool) $data['files_enabled'] : true,
            'type' => isset($data['files_source']) ? $data['files_source'] : 'finder',
            'base_directory' => $base_dir,
            'batch' => $this->default_files($base_dir, $excludes, $incremental_date),
            'excludes' => $excludes,
            'incremental_date' => $incremental_date,
        ]);

        $this->set('sql', [
            'enabled' => isset($data['sql_enabled']) ? (bool) $data['sql_enabled'] : true,
            'finished' => false,
            'type' => isset($data['sql_source']) ? $data['sql_source'] : 'mysqlmanual',
            'connection' => $this->default_database_connection(),
            'includes' => isset($data['sql_includes']) && is_array($data['sql_includes']) ? $data['sql_includes'] : [],
            'excludes' => isset($data['sql_excludes']) && is_array($data['sql_excludes']) ? $data['sql_excludes'] : [],
        ]);

        $this->set('name', $this->name);
        $this->set('processor', $this->processor);

        $destination = isset($data['destination']) && is_array($data['destination']) ? $data['destination'] : $this->default_destination();

        $this->set('destination', $destination);

        return $this;
    }

    /**
     * Set data to this backup profile.
     *
     * @return void
     */
    public function set($key, $data)
    {
        $this->data[$key] = $data;
    }

    /**
     * Get setting.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = false)
    {
        $data = $this->all();
        $ids = explode('.', $key);

        foreach ($ids as $id) {
            if (is_null($data)) {
                break;
            }

            $data = isset($data[$id]) ? $data[$id] : null;
        }

        if (is_null($data)) {
            return $default;
        }

        return $data;
    }

    /**
     * Update the whole profile.
     *
     * @param array $data
     * @return $this
     */
    public function update(array $data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Reterive all saved date.
     *
     * @return void
     */
    public function all(): array
    {
        return $this->data;
    }

    /**
     * @return array
     */
    private function default_excludes()
    {
        return apply_filters('husky_press_backup_excludes', [
            'node_modules',
            'logs',
            'husky-backup',
        ]);
    }

    /**
     * @return string
     */
    private function default_base_dir()
    {
        return ABSPATH;
    }

    /**
     * @param string $source
     * @param array $excludes
     * @param mixed $incremental_date
     *
     * @return array
     */
    private function default_files($source, $excludes = [], $incremental_date = null)
    {
        return [
            'total_sizes' => $this->count_total_files($source, $excludes, $incremental_date),
            'max_files_by_batch' => husky_press_max_files_by_batch(),
            'current' => 0,
        ];
    }

    /**
     * @return array
     */
    private function default_database_connection()
    {
        return [
            'database' => DB_NAME,
            'user' => DB_USER,
            'password' => DB_PASSWORD,
            'host' => DB_HOST,
        ];
    }

    /**
     * @return array
     */
    private function default_destination()
    {
        return [
            'type' => 'husky-press',
            'extension' => 'zip',
        ];
    }

    /**
     * @param string $source
     * @param string $excludes
     * @param mixed $incremental_date
     *
     * @return int
     */
    private function count_total_files($source, $excludes = [], $incremental_date = null)
    {
        $finder = new Finder();
        $finder->files()->in($source)->ignoreDotFiles(false)->exclude($excludes);

        if ($incremental_date) {
            $finder->date($incremental_date);
        }

        return $finder->count();
    }

    /**
     * @return void
     */
    private function suffix($suffix)
    {
        $this->name .= '-' . $suffix;
    }

    /**
     * @return void
     */
    private function prefix($prefix)
    {
        $this->name = $prefix . '-' . $this->name;
    }
}
