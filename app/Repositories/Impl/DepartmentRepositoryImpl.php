<?php

namespace App\Repositories\Impl;

use DB;
use App\Models\Department;
use App\Repositories\DepartmentRepository;

class DepartmentRepositoryImpl extends BaseRepositoryImpl implements DepartmentRepository
{
    public function model()
    {
        return Department::class;
    }

    public function getAssessor($managerId)
    {
        return DB::table('users as t1')
                    ->join('model_has_roles as t2', 't1.id', '=', 't2.model_id')
                    ->join('department_manager as t3', 't3.department_id', '=', 't1.department_id')
                    ->where('t1.id', $managerId)
                    ->orWhere(function ($query) use ($managerId) {
                        $query->where('t3.user_id', $managerId);
                        $query->WhereIn('t2.role_id', [2, 3]);
                    })
                    ->distinct()
                    ->get(['t1.id', 't1.username',
                    't1.email', 't1.firstname',
                    't1.lastname']);
    }
}
