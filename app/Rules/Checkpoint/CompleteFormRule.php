<?php

namespace App\Rules\Checkpoint;

use Carbon\Carbon;
use App\Services\CheckPointServices;
use Illuminate\Contracts\Validation\ImplicitRule;

class CompleteFormRule implements ImplicitRule
{
    protected $type;

    public function __construct($type)
    {
        $this->type = $type;
    }

    /**
     *
     * @SuppressWarnings("unused")
     * @return boolean
     */
    public function passes($attribute, $value)
    {
        $checkPointServices = app(CheckPointServices::class);
        $checkPoint = $checkPointServices->findOrFail(request()->id);
        $checkPoint->load('campaign');
        $now = Carbon::now()->format('Y-m-d');
        $isValid = false;

        switch ($this->type) {
            case 1:
                $isValid = ($now <= $checkPoint->campaign->deadline_emp_complete ||
                                $now <= $checkPoint->extra_emp_complete);
                break;
            case 2:
                $isValid = $now <= ($checkPoint->manager_id ==  $checkPoint->assessor_id ?
                                    $checkPoint->campaign->deadline_manager_approve :
                                        ($checkPoint->extra_assessor_complete ?
                                        $checkPoint->extra_assessor_complete :
                                        $checkPoint->campaign->deadline_assessor_complete)
                                    );
                break;
            case 3:
                $isValid = $now <= $checkPoint->campaign->deadline_manager_approve;
                break;
            default:
                break;
        }
        return $isValid;
    }

    public function message()
    {
        $messages = [
            '1' => 'Completing the employee\'s checkpoint form was long overdue',
            '2' => 'Evaluating the employee\'s checkpoint form was long overdue',
            '3' => 'Approving the employee\'s checkpoint form was long overdue',
        ];
        return $messages[$this->type];
    }
}
