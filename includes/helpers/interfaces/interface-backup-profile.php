<?php

namespace HuskyPress\Helpers\Interfaces;

interface BackupProfileInterface
{
    public function data(array $data);

    public function set($key, $data);

    public function get($key, $default = null);

    public function all(): array;
}
