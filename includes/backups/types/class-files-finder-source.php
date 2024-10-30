<?php

namespace HuskyPress\Backups\Types;

use ZipArchive;
use LimitIterator;
use Symfony\Component\Finder\Finder;
use HuskyPress\Helpers\Interfaces\BackupSource;

class FilesFinderSource implements BackupSource
{
    /**
     * @var string
     */
    private string $name;

    /**
     * @var string
     */
    private string $source;

    /**
     * @var array
     */
    private array $excludes = [];

    /**
     * @var string
     */
    private $since = null;

    /**
     * @var int
     */
    private int $current = 0;

    /**
     * @var int
     */
    private int $max_files;

    /**
     * FilesFinderSource Constructor.
     */
    public function __construct($name, $source, $current, $max_files, $excludes = [])
    {
        $this->name = $name;
        $this->source = $source;
        $this->current = $current;
        $this->max_files = $max_files;
        $this->excludes = $excludes;
    }

    /**
     * Fetch and save files.
     *
     * @param string $destination
     * @return void
     */
    public function fetch($destination = null)
    {
        $finder = new Finder();
        $finder->files()->in($this->source)->ignoreDotFiles(false)->exclude($this->excludes);

        if ($this->since) {
            $finder->date($this->since);
        }

        $current = $this->current;
        $max_files = $this->max_files;

        $zip = new ZipArchive();
        $zip->open(sprintf('%s/%s/%s.zip', $destination, $this->name(), $this->name()), ZipArchive::CREATE);

        foreach (new LimitIterator($finder->getIterator(), $current, $max_files) as $file) {
            $realPath = $file->getRealPath();

            if (!file_exists($realPath)) {
                continue;
            }

            $zip->addFile($file->getRealPath(), $file->getRelativePathname());
        }

        $zip->close();
    }

    /**
     * Exclude files.
     *
     * @param array $excludes
     * @return array
     */
    public function exludes(array $excludes)
    {
        $this->excludes = $excludes;

        return $this;
    }

    /**
     * Exclude file.
     *
     * @param string $file
     * @return $this
     */
    public function exclude(string $file)
    {
        if (!in_array($file, $this->excludes)) {
            $this->excludes[] = $file;
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function since($since)
    {
        $this->since = $since;

        return $this;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }
}
