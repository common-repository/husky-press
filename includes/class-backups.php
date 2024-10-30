<?php

namespace HuskyPress;

use HuskyPress\Helpers\BackupProfile;
use HuskyPress\Backups\BackupBuilder;
use HuskyPress\Helpers\Interfaces\BackupProfileInterface;
use HuskyPress\Helpers\Interfaces\BackupBuilderInterface;

class Backups
{
    /**
     * Determine time for backup to start after (in seconds).
     *
     * @var int
     */
    private $start_after = 5;

    /**
     * Backup profile.
     *
     * @var BackupProfileInterface
     */
    private $profile = null;

    /**
     * Backup builder.
     *
     * @var BackupBuilderInterface
     */
    private $builder = null;

    /**
     * Backups Constructor.
     */
    public function __construct()
    {
        $this->init_profile();
        $this->init_builder();
    }

    /**
     * Initialize a backup profile.
     *
     * @return $this
     */
    private function init_profile()
    {
        $this->profile = apply_filters('husky_press_backup_profile', new BackupProfile());

        return $this;
    }

    /**
     * Initialize a backup profile.
     *
     * @return $this
     */
    private function init_builder()
    {
        $this->builder = apply_filters('husky_press_backup_builder', new BackupBuilder());

        return $this;
    }

    /**
     * @return BackupProfileInterface
     */
    public function profile(): BackupProfileInterface
    {
        return $this->profile;
    }

    /**
     * @return BackupBuilderInterface
     */
    public function builder(): BackupBuilderInterface
    {
        return $this->builder;
    }

    /**
     * Determine whether there's a backup in progress.
     *
     * @return boolean
     */
    public function in_proccess()
    {
        if (function_exists('as_has_scheduled_action')) {
            return as_has_scheduled_action('husky_press_backup');
        } elseif (function_exists('as_next_scheduled_action')) {
            return as_next_scheduled_action('husky_press_backup');
        }

        return false;
    }

    /**
     * Schedule a backup proccess with saved profile.
     *
     * @return void
     */
    public function schedule()
    {
        as_schedule_single_action($this->start_time(), 'husky_press_backup', [
            'profile' => $this->profile->all(),
        ], 'husky-press');
    }

    /**
     * @return int
     */
    private function start_time()
    {
        return time() + $this->start_after;
    }
}
