<?php

namespace App\Repositories;

interface UserRepository
{
    public function getInfo($userId);

    public function getListCheckpoint($userId);
}
