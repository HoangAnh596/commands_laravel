<?php

namespace App\Repositories\Impl;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Repositories\CheckPointRepository;

class UserRepositoryImpl extends BaseRepositoryImpl implements UserRepository
{
    public function model()
    {
        return User::class;
    }

    public function getInfo($userId)
    {
        $query = $this->model->newQuery();
        $query
            ->with(['permissions', 'roles'])
            ->where('id', $userId)
            ->first();
        return $query->first();
    }

    public function getListCheckpoint($userId)
    {
        $checkpointRepo = app(CheckPointRepository::class);
        $checkpoints = $checkpointRepo->allQuery(['emp_id' => $userId])
                        ->orderBy('campaign_id', 'DESC')
                        ->get();
        $checkpoints->load([
            'employee' => function ($query) {
                $query->with('department');
            },
            'assessor',
            'manager',
            'plans',
            'training',
            'campaign' => function ($query) {
                $query->select(['id', 'start_date', 'status']);
            }
        ]);
        return $checkpoints;
    }
}
