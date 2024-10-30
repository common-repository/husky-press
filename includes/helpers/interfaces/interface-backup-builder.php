<?php

namespace HuskyPress\Helpers\Interfaces;

interface BackupBuilderInterface
{
    public function database();

    public function files();

    public function sources();

    public function zip($processor);

    public function destination();

    public function cleanup();
}
