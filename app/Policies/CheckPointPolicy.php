<?php

namespace App\Policies;

use App\Models\User;
use App\Models\CheckPoint;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Arr;

/**
 * Class CheckPointPolicy
 * @package App\Policies
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 *
 **/
class CheckPointPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        return $user->hasPermissionTo(config('auth.permissions.checkpoint.create'));
    }

    /**
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function view(User $user, CheckPoint $checkPoint)
    {
        if ($user->hasRole('HR')) {
            return true;
        }
        if ($user->hasPermissionTo(config('auth.permissions.checkpoint.view'))
            && ($user->id == $checkPoint->manager_id || $user->hasRole('PM'))
            && ($checkPoint->status == CheckPoint::STATUS_APPROVING
                || $checkPoint->status == CheckPoint::STATUS_DONE)) {
            return true;
        }

        if ($user->hasPermissionTo(config('auth.permissions.checkpoint.view'))
            && ((($user->id == $checkPoint->assessor_id  || $user->hasRole('DD'))
                    && ($checkPoint->status == CheckPoint::STATUS_REVIEWING
                        || $checkPoint->status == CheckPoint::STATUS_APPROVING
                        || $checkPoint->status == CheckPoint::STATUS_DONE
                    )) || $user->id == $checkPoint->emp_id)) {
            return true;
        }

        if ($user->hasPermissionTo(config('auth.permissions.checkpoint.view'))
            && $user->id == $checkPoint->emp_id && $checkPoint->status != CheckPoint::STATUS_NEW) {
            return true;
        }
        return false;
    }

    public function assignAssessor(User $user, CheckPoint $checkPoint)
    {
        if (!$user->hasRole('DD')) {
            return $this->deny('You must have role DD');
        }

        if (!$user->hasPermissionTo(config('auth.permissions.checkpoint.assign'))) {
            return $this->deny('You do not have permission to assign');
        }

        if (!($user->id == $checkPoint->manager_id)) {
            return $this->deny('You do not have permission to assign this employee');
        }

        if (!($checkPoint->status == CheckPoint::STATUS_NEW || $checkPoint->status == CheckPoint::STATUS_INPROGRESS)) {
            return $this->deny('The checkpoint status must be NEW or INPROGRESS');
        }

        return true;
    }

    public function saveFormEmp(User $user, CheckPoint $checkPoint)
    {
        return $user->hasPermissionTo(config('auth.permissions.checkpoint.update'))
            && $user->id == $checkPoint->emp_id
            && $checkPoint->status == CheckPoint::STATUS_INPROGRESS;
    }

    public function saveFormAssessor(User $user, CheckPoint $checkPoint)
    {
        return $user->hasPermissionTo(config('auth.permissions.checkpoint.update'))
            && $user->id == $checkPoint->assessor_id
            && ($checkPoint->status == CheckPoint::STATUS_REVIEWING ||
                ($user->hasRole('DD') &&
                    ($checkPoint->status == CheckPoint::STATUS_APPROVING || CheckPoint::STATUS_DONE))
                );
    }

    public function saveFormManager(User $user, CheckPoint $checkPoint)
    {
        return $user->hasPermissionTo(config('auth.permissions.checkpoint.update'))
            && $user->id == $checkPoint->manager_id
            && $checkPoint->status == CheckPoint::STATUS_APPROVING;
    }

    public function approveFormManager(User $user, CheckPoint $checkPoint)
    {
        $cheManagers = Arr::pluck($checkPoint->employee->managers, 'user_id');
        return $user->hasRole('DD')
            && $user->hasPermissionTo(config('auth.permissions.checkpoint.approve'))
            && ($user->id == $checkPoint->manager_id || in_array($user->id, $cheManagers))
            && $checkPoint->status == CheckPoint::STATUS_APPROVING;
    }

    public function rejectFormManager(User $user, CheckPoint $checkPoint)
    {
        return $user->hasRole('DD')
            && $user->hasPermissionTo(config('auth.permissions.checkpoint.approve'))
            && $user->id == $checkPoint->manager_id
            && ($checkPoint->status == CheckPoint::STATUS_APPROVING || $checkPoint->status == CheckPoint::STATUS_DONE);
    }

    public function saveNoteManager(User $user, CheckPoint $checkPoint)
    {
        return $user->hasRole('DD')
            && $user->hasPermissionTo(config('auth.permissions.checkpoint.update'))
            && $user->id === $checkPoint->manager_id;
    }

    public function showList(User $user)
    {
        return $user->hasAnyRole(['DD', 'HR', 'PM']);
    }

    public function showMyCheckpoint(User $user)
    {
        return $user->hasRole('EMP');
    }
}
