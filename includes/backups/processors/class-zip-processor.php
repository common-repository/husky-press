<?php

namespace HuskyPress\Backups\Processors;

use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use HuskyPress\Helpers\Interfaces\BackupProcessor;

class ZipProcessor implements BackupProcessor
{
    /**
     * @param string
     */
    public string $name;

    /**
     * @param string
     */
    public string $extension;

    /**
     * @param int
     */
    public int $timeout = 600;

    /**
     * ZipProcessor Constructor.
     */
    public function __construct($name, $extension = 'zip', $timeout = 600)
    {
        $this->name = $name;
        $this->extension = $extension;
        $this->timeout = $timeout;
    }

    /**
     * @param string $destination
     * @return void
     */
    public function process($destination = null)
    {
        if (!class_exists('ZipArchive')) {
            return;
        }

        $zip = new ZipArchive();
        $zip->open(sprintf('%s/%s.%s', $destination, $this->name(), $this->extension), ZipArchive::CREATE);

        $source = sprintf('%s/%s', $destination, $this->name());
        $source = str_replace('\\', '/', realpath($source));

        if (empty($source)) {
            return;
        }

        if (is_dir($source)) {
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($source),
                RecursiveIteratorIterator::SELF_FIRST
            );

            foreach ($files as $file) {
                $this->timeout();

                $file = str_replace('\\', '/', $file);

                if (in_array(substr($file, strrpos($file, '/') + 1), array('.', '..'))) {
                    continue;
                }

                $file = realpath($file);

                if (is_dir($file)) {
                    $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                } elseif (is_file($file)) {
                    $zip->addFile($file, str_replace($source . '/', '', $file));
                }
            }
        } elseif (is_file($source)) {
            $zip->addFile($source, basename($source));
        }

        $zip->close();
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return void
     */
    private function timeout()
    {
        if (function_exists('set_time_limit')) {
            @set_time_limit($this->timeout);
        }
    }
}
