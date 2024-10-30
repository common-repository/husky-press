<?php

namespace HuskyPress\Helpers\Interfaces;

interface BackupDestination
{
    public function send();

    public function name();
}
