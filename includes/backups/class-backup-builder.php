<?php

namespace HuskyPress\Backups;

use HuskyPress\Backups\Types\MySQLDumpSource;
use HuskyPress\Backups\Types\MySQLManualSource;
use HuskyPress\Backups\Types\FilesFinderSource;
use HuskyPress\Backups\Processors\ZipProcessor;
use HuskyPress\Helpers\Interfaces\BackupSource;
use HuskyPress\Helpers\Interfaces\BackupProcessor;
use HuskyPress\Helpers\Interfaces\BackupBuilderInterface;
use HuskyPress\Backups\Destinations\HuskyPressDestination;

class BackupBuilder implements BackupBuilderInterface
{
    /**
     * @var string
     */
    private $name = '';

    /**
     * @var array
     */
    private $types = [];

    /**
     * @var array
     */
    private $processors = [];

    /**
     * @var array
     */
    private $destinations = [];

    /**
     * Backup made sources.
     *
     * @var BackupSource
     */
    private $sources = [];

    /**
     * BackupBuilder Constructor.
     */
    public function __construct()
    {
        $this->types = apply_filters('husky_press_backup_types', [
            'mysqlmanual' => MySQLManualSource::class,
            'mysqldump' => MySQLDumpSource::class,
            'finder' => FilesFinderSource::class,
        ]);
        $this->processors = apply_filters('husky_press_backup_processors', [
            'zip' => ZipProcessor::class,
        ]);
        $this->destinations = apply_filters('husky_press_backup_destinations', [
            'husky-press' => HuskyPressDestination::class,
        ]);
    }

    /**
     * Set this proccess name.
     *
     * @return $this;
     */
    public function name($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Backup database.
     *
     * @return $this
     */
    public function database($options = [])
    {
        $type = $this->predict_type($options['type'], 'mysqlmanual');

        if (!$type) {
            return $this;
        }

        $source = new $type(
            $this->name,
            $options['connection']['database'],
            $options['connection']['user'],
            $options['connection']['password'],
            $options['connection']['host'],
            $options['includes'],
            $options['excludes'],
        );

        if ($source) {
            $this->sources[] = $source;
        }

        return $this;
    }

    /**
     * Backup files.
     *
     * @return $this
     */
    public function files($options = [])
    {
        $type = $this->predict_type($options['type'], 'finder');

        if (!$type) {
            return $this;
        }

        $incremental_date = isset($options['incremental_date']) ? $options['incremental_date'] : null;
        if ($incremental_date) {
            $type = 'finder-proccess';
        }

        $source = new $type($this->name, $options['base_directory'], $options['batch']['current'], $options['batch']['max_files_by_batch'], $options['excludes']);

        if ($incremental_date) {
            $source->since($incremental_date);
        }

        if ($source) {
            $this->sources[] = $source;
        }

        return $this;
    }

    /**
     * @return void
     */
    public function sources()
    {
        foreach ($this->sources as $source) {
            $source->fetch(HUSKY_PRESS_BACKUP);
        }
    }

    /**
     * Store all backup files into ZIP.
     *
     * @return void
     */
    public function zip($processor)
    {
        $processor = $this->predict_processor($processor);

        if (is_null($processor)) {
            return $this;
        }

        (new $processor($this->name))->process(HUSKY_PRESS_BACKUP);

        return $this;
    }

    /**
     * Send the archive to any destination.
     *
     * @param array $options
     * @return void
     */
    public function destination($options = [])
    {
        $type = $this->predict_destination($options['type'], 'husky-press');

        if (is_null($type)) {
            return $this;
        }

        (new $type($this->name))->send(HUSKY_PRESS_BACKUP, $options);
    }

    /**
     * Cleanup after all backups.
     *
     * @return void
     */
    public function cleanup()
    {
        husky_press_remove_dir(sprintf('%s/%s', HUSKY_PRESS_BACKUP, $this->name));
    }

    /**
     * @return BackupSource
     */
    private function predict_type($type, $default = '')
    {
        return isset($this->types[$type]) ? $this->types[$type] : $this->types[$default];
    }

    /**
     * @return BackupProcessor
     */
    private function predict_processor($processor, $default = '')
    {
        return isset($this->processors[$processor]) ? $this->processors[$processor] : $this->processors[$default];
    }

    /**
     * @return BackupDestination
     */
    private function predict_destination($destination, $default = '')
    {
        return isset($this->destinations[$destination]) ? $this->destinations[$destination] : $this->destinations[$default];
    }
}
