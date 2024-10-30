<?php

namespace HuskyPress\Backups\Types;

use Symfony\Component\Process\Process;
use HuskyPress\Helpers\Interfaces\BackupSource;

class MySQLDumpSource implements BackupSource
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
     * @return void
     */
    public function fetch($destination = null)
    {
        $pfile = md5(time() . rand()) . '.tmp';
        file_put_contents(sprintf('%s/%s', $destination, $pfile), "[mysqldump]\npassword=\"" . addslashes($this->password) . "\"\n");

        foreach (explode(',', husky_press_build_mysqldump_list()) as $potsql) {
            if (!@is_executable($potsql)) {
                continue;
            }

            $args = [];

            if ('win' == strtolower(substr(PHP_OS, 0, 3))) {
                $args[] = 'cd';
                $args[] = escapeshellarg(str_replace('/', '\\', $destination));
                $args[] = '&';

                if (false !== strpos($potsql, ' ')) {
                    $potsql = '"' . $potsql . '"';
                }
            } else {
                $args[] = 'cd';
                $args[] = escapeshellarg($destination) . ";";

                if (false !== strpos($potsql, ' ')) {
                    $potsql = "'$potsql'";
                }
            }

            $args[] = $potsql;

            $args[] = "--defaults-file=$pfile";
            $args[] = '--quote-names';
            $args[] = '--add-drop-table';
            $args[] = '--skip-comments';
            $args[] = '--skip-set-charset';
            $args[] = '--allow-keywords';
            $args[] = '--dump-date';
            $args[] = '--extended-insert';
            $args[] = '--user=' . $this->user;

            if (preg_match('#^(.*):(\d+)$#', $this->host, $matches)) {
                $args[] = '--host=' . $matches[1];
                $args[] = '--port=' . $matches[2];
            } else {
                $args[] = '--host=' . $this->host;
            }

            $mysql_version = husky_press_get_mysql_version();
            if ($mysql_version && version_compare($mysql_version, '5.1', '>=')) {
                $args[] = '--no-tablespaces';
            }

            foreach ($this->excludes as $exclude) {
                $args[] = '--ignore-table=' . $this->database . '.' . $exclude;
            }

            $args[] = $this->database;

            foreach ($this->includes as $include) {
                $args[] = $include;
            }

            $process = new Process($args);
            $process->run();

            if ($process->isSuccessful()) {
                file_put_contents(sprintf('%s/%s/%s.sql', $destination, $this->name(), $this->database), $process->getOutput());
            }
        }

        if (file_exists($destination . '/' . $pfile)) {
            unlink($destination . '/' . $pfile);
        }
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * Detect if safe_mode is on.
     *
     * @return boolean
     */
    public function detect_safe_mode()
    {
        // @codingStandardsIgnoreLine
        return (@ini_get('safe_mode') && 'off' != strtolower(@ini_get('safe_mode'))) ? true : false;
    }
}
