<?php

namespace App\Repositories\Impl;

use App\Models\Campaign;
use App\Repositories\CampaignRepository;

class CampaignRepositoryImpl extends BaseRepositoryImpl implements CampaignRepository
{
    public function model()
    {
        return Campaign::class;
    }
}
