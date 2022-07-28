<?php

namespace App\Repositories\Impl;

use App\Models\EmployeeTraining;
use App\Repositories\EmployeeTrainingRepository;

class EmployeeTrainingRepositoryImpl extends BaseRepositoryImpl implements EmployeeTrainingRepository
{
    public function model()
    {
        return EmployeeTraining::class;
    }
}
