<?php

namespace App\Repositories;

interface CheckPointRepository
{
    public function getListCheckpoint($param = []);

    public function getMyCheckpoint($userId);

    public function getEmployeeAttributes();

    public function getAssessorAttributes();

    public function getManagerAttributes();

    public function searchReport($params = []);

    public function total($params);
}
