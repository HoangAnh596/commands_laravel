<?php

namespace App\Services\Impl;

use App\Models\Campaign;
use App\Repositories\CampaignRepository;
use App\Services\CampaignServices;
use App\Services\CheckPointServices;

class CampaignServicesImpl extends BaseServicesImpl implements CampaignServices
{
    public function repository()
    {
        return CampaignRepository::class;
    }

    public function getCurrentCampaign()
    {
        $campaign = $this->repository->allQuery(['status' => Campaign::STATUS_OPEN])->first();
        if (empty($campaign)) {
            $campaign = $this->repository->allQuery()->orderBy('end_date', 'desc')->first();
        }
        return $campaign;
    }

    /**
     * Get list campaigns by user logged in
     *
     * @return Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getListCampaigns()
    {
        $user = auth()->user();
        if ($user->hasRole('HR')) {
            return $this->repository->paginate(config('app.per_page'));
        }

        $campaignsId = [];
        $checkPointServices = app(CheckPointServices::class);
        $checkPointQuery = $checkPointServices->repository->model->newQuery();

        if ($user->hasRole('EMP')) {
            $checkPointQuery = $checkPointQuery->orWhere('emp_id', $user->id);
        }

        if ($user->hasRole('PM')) {
            $checkPointQuery = $checkPointQuery->orWhere('assessor_id', $user->id);
        }

        if ($user->hasRole('DD')) {
            $checkPointQuery = $checkPointQuery->orWhere('manager_id', $user->id);
        }

        $campaignsId = $checkPointQuery->pluck('campaign_id')->toArray();
        $campaignsId = array_unique($campaignsId, SORT_REGULAR);
        return $this->repository->whereIn('id', $campaignsId)->paginate(config('app.per_page'));
    }
}
