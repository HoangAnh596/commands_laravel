<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Common\Controller;
use App\Http\Resources\DepartmentResources;
use App\Services\DepartmentServices;

class DepartmentController extends Controller
{
    protected $departmentServices;

    public function __construct(DepartmentServices $departmentServices)
    {
        $this->departmentServices = $departmentServices->departmentRepo;
    }

    public function index()
    {
        $departments = $this->departmentServices->all()->where('parent_id', '<>', 0);
        return DepartmentResources::collection($departments);
    }

    public function assessor()
    {
        $user = auth()->user();
        $list = $this->departmentServices->getAssessor($user->id);
        return $list;
    }
}
