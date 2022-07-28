<?php

namespace App\Repositories\Impl;

use App\Models\DepartmentManager;
use App\Repositories\DepartmentManagerRepository;

class DepartmentManagerRepositoryImpl extends BaseRepositoryImpl implements DepartmentManagerRepository
{
    public function model()
    {
        return DepartmentManager::class;
    }
}
