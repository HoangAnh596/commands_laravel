<?php

namespace App\Services\Impl;

use App\Repositories\DepartmentManagerRepository;
use App\Services\DepartmentManagerServices;

class DepartmentManagerServicesImpl extends BaseServicesImpl implements DepartmentManagerServices
{
    public function repository()
    {
        return DepartmentManagerRepository::class;
    }

    public function getDepartmentIdByManager()
    {
        $deptIds = $this->repository->allQuery(['user_id' => auth()->user()->id])->pluck('department_id')->toArray();
        return $deptIds;
    }
}
