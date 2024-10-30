<?php

namespace HuskyPress\Helpers\Interfaces;

interface BackupProcessor
{
    public function process();

    public function name();
}
