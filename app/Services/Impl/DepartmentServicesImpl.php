<?php

namespace App\Services\Impl;

use App\Library\TransitData;
use App\Repositories\DepartmentManagerRepository;
use App\Repositories\DepartmentRepository;
use App\Services\DepartmentServices;

class DepartmentServicesImpl extends BaseServicesImpl implements DepartmentServices
{
    public $departmentRepo;
    public $departmentManagerRepo;

    public function __construct(
        DepartmentRepository $departmentRepository,
        DepartmentManagerRepository $departmentManagerRepository
    ) {
        $this->departmentRepo = $departmentRepository;
        $this->departmentManagerRepo = $departmentManagerRepository;
    }

    public function repository()
    {
        return DepartmentRepository::class;
    }

    public function syncAllData()
    {
        $syncData = TransitData::getInstance()->query('hrm', '/v1/department', 'GET');
        if (!$syncData['success']) {
            return;
        }
        $count = count($syncData['data']);
        if ($count > 0) {
            foreach ($syncData['data'] as $item) {
                $department = $this->departmentRepo->find($item->id);
                $dataDept = [
                    'id' => $item->id,
                    'parent_id' => $item->parent_id,
                    'name' => $item->name,
                    'code' => $item->code,
                    'd_type' => $item->d_type,
                    'total_member' => $item->total_member,
                    'description' => $item->description,
                    'created_at' => $item->created_time,
                    'updated_at' => $item->updated_time
                ];

                if (count($item->managers) > 0) {
                    foreach ($item->managers as $itemManger) {
                        $dataDeptManager = [
                            'id' => $itemManger->dept_manager_id,
                            'department_id' => $item->id,
                            'user_id' => $itemManger->id,
                            'role' => $itemManger->role,
                            'level' => $itemManger->level
                        ];
                        $departmentManager = $this->departmentManagerRepo->find($itemManger->dept_manager_id);
                        if (empty($departmentManager)) {
                            $this->departmentManagerRepo->create($dataDeptManager);
                        } else {
                            $this->departmentManagerRepo->update($itemManger->dept_manager_id, $dataDeptManager);
                        }
                    }
                }
                if (empty($department)) {
                    $this->departmentRepo->create($dataDept);
                } else {
                    $this->departmentRepo->update($item->id, $dataDept);
                }
            }
        }
    }
}
