<?php

namespace App\Services\Impl;

use App\Library\Common;
use App\Library\TransitData;
use App\Services\UserServices;
use App\Repositories\UserRepository;
use Spatie\Permission\Models\Role;

/**
 * @SuppressWarnings(PHPMD)
 */
class UserServicesImpl extends BaseServicesImpl implements UserServices
{
    public function repository()
    {
        return UserRepository::class;
    }

    public function syncAllData($departmentId = 0)
    {
        $page = 1;
        do {
            $query = [];
            $query['page'] = $page;

            if ($departmentId) {
                $query['department_id'] = $departmentId;
            }

            //$syncData = TransitData::getInstance()->query('hrm', "/v1/members?" . http_build_query($query));
            $syncData = TransitData::getInstance()->query('hrm', "/pub/member/get-list?" . http_build_query($query));

            if (!$syncData['success']) {
                return response()->json($syncData);
            }
            $count = count($syncData['data']);
            if ($count > 0) {
                $roleEmp = Role::where('name', 'EMP')->first();
                foreach ($syncData['data'] as $userData) {
                    if ($userData->status === 1) {
                        $user = $this->find($userData->id);
                        $joinDate = ($userData->official_date && Common::validateDate($userData->official_date)) ?
                                        $userData->official_date : $userData->join_date;
                        $dataUser = [
                            'id' => $userData->id,
                            'email' => $userData->email,
                            'username' => $userData->username,
                            'firstname' => $userData->firstname,
                            'lastname' => $userData->lastname,
                            'department_id' => $userData->department_id,
                            'employee_code' => $userData->employee_code,
                            "job_position" => $userData->job_position,
                            "job_rank" => $userData->job_rank,
                            "job_family" => $userData->job_family,
                            "contract_type" => $userData->contract_type,
                            "join_date" => $joinDate,
                            "created_at" => $userData->created_time,
                            "updated_at" => $userData->updated_time,
                        ];
                        if (empty($user)) {
                            $user = $this->create($dataUser);
                            $user->assignRole($roleEmp);
                        } else {
                            $this->update($userData->id, $dataUser);
                        }
                    }
                }
            }
            $page++;
        } while ($count > 0);
    }

    public function getInfo($userId)
    {
        return $this->repository->getInfo($userId);
    }

    public function getListCheckpoint($userId)
    {
        return $this->repository->getListCheckpoint($userId);
    }
}
