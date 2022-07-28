<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CampaignPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        return $user->hasPermissionTo(config('auth.permissions.campaign.create'));
    }

    public function view(User $user)
    {
        return $user->hasPermissionTo(config('auth.permissions.campaign.view'));
    }

    public function update(User $user)
    {
        return $user->hasPermissionTo(config('auth.permissions.campaign.update'));
    }

    public function delete(User $user)
    {
        return $user->hasPermissionTo(config('auth.permissions.campaign.delete'));
    }

    public function import(User $user)
    {
        return $user->hasPermissionTo(config('auth.permissions.campaign.import_emp'));
    }
}
