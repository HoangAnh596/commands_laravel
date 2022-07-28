<?php

namespace App\Services\Impl;

use App\Repositories\EmployeePlanRepository;
use App\Services\EmployeePlanServices;

class EmployeePlanServicesImpl extends BaseServicesImpl implements EmployeePlanServices
{
    public function repository()
    {
        return EmployeePlanRepository::class;
    }
}
