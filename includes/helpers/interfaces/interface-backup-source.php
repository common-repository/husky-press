<?php

namespace HuskyPress\Helpers\Interfaces;

interface BackupSource
{
    public function fetch();

    public function name();
}
