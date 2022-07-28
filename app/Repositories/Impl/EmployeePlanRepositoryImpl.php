<?php

namespace App\Repositories\Impl;

use App\Models\EmployeePlan;
use App\Repositories\EmployeePlanRepository;

class EmployeePlanRepositoryImpl extends BaseRepositoryImpl implements EmployeePlanRepository
{
    public function model()
    {
        return EmployeePlan::class;
    }
}
