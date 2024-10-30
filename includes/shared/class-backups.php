<?php

namespace HuskyPress\Shared;

defined('ABSPATH') || exit;

class Backups
{
    /**
     * Register hooks.
     *
     * @return void
     */
    public function hooks()
    {
        add_action('husky_press_backup', [$this, 'backup_batch'], 10, 1);
    }

    /**
     * Do a backup.
     *
     * @return void
     */
    public function backup_batch($profile)
    {
        if (!husky_press()->settings->get('auth.token') || !husky_press()->settings->get('auth.project_id')) {
            return;
        }

        if (function_exists('set_time_limit')) {
            @set_time_limit(900);
        }

        $backups = husky_press()->backups;
        $builder = $backups->builder()->name($profile['name']);

        if (!file_exists(sprintf('%s/%s', HUSKY_PRESS_BACKUP, $profile['name']))) {
            @mkdir(sprintf('%s/%s', HUSKY_PRESS_BACKUP, $profile['name']), 0777, true);
        }

        // Backup database tables.
        if ($profile['sql']['enabled'] && !$profile['sql']['finished']) {
            $this->database_batch($builder, $profile);
            return;
        }

        // Backup files.
        if ($profile['files']['enabled'] && !($profile['files']['batch']['current'] >= $profile['files']['batch']['total_sizes'])) {
            $this->files_batch($builder, $profile);
            return;
        }

        // Archive all backups.
        $builder->zip($profile['processor']);

        // Cleanup.
        $builder->cleanup();

        // Send to destination.
        $builder->destination($profile['destination']);
    }

    /**
     * Do the database backup batch.
     *
     * @return void
     */
    private function database_batch($builder, $profile)
    {
        // Make database backup.
        $builder->database($profile['sql']);
        $builder->sources();

        // Mark this batch as finished
        $profile['sql']['finished'] = true;

        // Make another batch.
        $this->batch($profile);
    }

    /**
     * Do the files backup batch.
     *
     * @return void
     */
    private function files_batch($builder, $profile)
    {
        // Make files backup.
        $builder->files($profile['files']);
        $builder->sources();

        $current = $profile['files']['batch']['current'] + $profile['files']['batch']['max_files_by_batch'];
        $profile['files']['batch']['current'] = $current;

        // Make another batch.
        $this->batch($profile);
    }

    /**
     * @return void
     */
    private function batch($profile)
    {
        $backups = husky_press()->backups;

        $backups->profile()->update($profile);
        $backups->schedule();
    }
}
