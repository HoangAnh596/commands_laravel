<?php

namespace App\Rules\Checkpoint;

use Carbon\Carbon;
use App\Services\CheckPointServices;
use Illuminate\Contracts\Validation\ImplicitRule;

class AssignAssessorRule implements ImplicitRule
{
    /**
     *
     * @SuppressWarnings("unused")
     * @return boolean
     */
    public function passes($attribute, $value)
    {
        $checkPointServices = app(CheckPointServices::class);
        $checkPoint = $checkPointServices->findOrFail($value);
        $checkPoint->load('campaign');
        $now = Carbon::now()->format('Y-m-d');
        return $now <= $checkPoint->campaign->deadline_manager_assign;
    }

    public function message()
    {
        return 'Assigning an assessor to an employee was long overdue';
    }
}
