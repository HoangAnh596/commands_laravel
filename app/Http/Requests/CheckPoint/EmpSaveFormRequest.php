<?php

namespace App\Http\Requests\CheckPoint;

use Illuminate\Http\Request;
use App\Rules\EmptyValueRule;
use App\Rules\EmptyValueArrayRule;
use App\Http\Controllers\Common\Controller;
use App\Rules\Checkpoint\CompleteFormRule;
use App\Http\Requests\CheckPoint\Traits\MessageValidationFormTrait;

class EmpSaveFormRequest extends Controller
{
    use MessageValidationFormTrait;

    public function __construct(Request $request)
    {
        // Modify data
        $empPlan = $request->get('emp_plan', null);
        if ($empPlan) {
            $empPlan = $this->modifyData($empPlan);
            $request->merge(['emp_plan' => json_encode($empPlan)]);
        }

        $empTraining = $request->get('emp_training', null);
        if ($empTraining) {
            $empTraining = $this->modifyData($empTraining);
            $request->merge(['emp_training' => json_encode($empTraining)]);
        }

        $this->validate($request, $this->getRules(), $this->getMessages());
        parent::__construct($request);
    }

    protected function modifyData($data)
    {
        $lines = json_decode($data, true);
        foreach ($lines as $k => $fields) {
            foreach ($fields as $valueField) {
                $valueField = trim(strip_tags($valueField));
                if (!$valueField) {
                    unset($lines[$k]);
                    break;
                }
            }
        }
        return $lines;
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function getRules()
    {
        $isSendRequest = request()->is('checkpoints/*/employee/send');
        return [
            'emp_assignment' => $isSendRequest ?
                                [new CompleteFormRule(1), 'required', new EmptyValueRule('emp_assignment')] :
                                [new CompleteFormRule(1)],
            'emp_target' => $isSendRequest ? ['required', new EmptyValueRule('emp_target')] : '',
            'emp_result' => $isSendRequest ? ['required', new EmptyValueRule('emp_result')] : '',
            'emp_evaluate_process' => $isSendRequest ? 'required|integer|min:1|max:10' : 'integer|min:1|max:10',
            'emp_evaluate_quality' => $isSendRequest ? 'required|integer|min:1|max:10' : 'integer|min:1|max:10',
            'emp_evaluate_complex' => $isSendRequest ? 'required|integer|min:1|max:10' : 'integer|min:1|max:10',
            'emp_evaluate_responsibility' => $isSendRequest ? 'required|integer|min:1|max:10' : 'integer|min:1|max:10',
            'emp_evaluate_policy' => $isSendRequest ? 'required|integer|min:1|max:10' : 'integer|min:1|max:10',
            'emp_plan' => $isSendRequest ? [new EmptyValueArrayRule('emp_plan')] : '',
            'emp_training' => $isSendRequest ? [new EmptyValueArrayRule('emp_training')] : '',
        ];
    }

    public function getMessages()
    {
        $fields = [
            'emp_evaluate_process',
            'emp_evaluate_quality',
            'emp_evaluate_complex',
            'emp_evaluate_responsibility',
            'emp_evaluate_policy',
        ];

        return array_merge([
            'emp_assignment.required' => trans(
                'messages.errors.validation.required',
                ['field_title' => trans('fields.checkpoint.emp_assignment')]
            ),
            'emp_target.required' => trans(
                'messages.errors.validation.required',
                ['field_title' => trans('fields.checkpoint.emp_target')]
            ),
            'emp_result.required' => trans(
                'messages.errors.validation.required',
                ['field_title' => trans('fields.checkpoint.emp_result')]
            ),
        ], $this->generateMessage($fields));
    }
}
