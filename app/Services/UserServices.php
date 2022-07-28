<?php

namespace App\Services;

interface UserServices
{
    public function syncAllData();

    public function getInfo($userId);

    public function getListCheckpoint($userId);
}
