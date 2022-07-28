<?php

namespace App\Services\Impl;

use App\Repositories\EmployeeTrainingRepository;
use App\Services\EmployeeTrainingServices;

class EmployeeTrainingServicesImpl extends BaseServicesImpl implements EmployeeTrainingServices
{
    public function repository()
    {
        return EmployeeTrainingRepository::class;
    }
}
