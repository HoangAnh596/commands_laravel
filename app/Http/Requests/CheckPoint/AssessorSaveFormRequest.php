<?php

namespace App\Http\Requests\CheckPoint;

use Illuminate\Http\Request;
use App\Http\Controllers\Common\Controller;
use App\Rules\Checkpoint\CompleteFormRule;
use App\Http\Requests\CheckPoint\Traits\MessageValidationFormTrait;

class AssessorSaveFormRequest extends Controller
{
    use MessageValidationFormTrait;

    public function __construct(Request $request)
    {
        $isRejectRequest = request()->is('checkpoints/*/assessor/reject');
        if (!$isRejectRequest) {
            $request->request->remove('assessor_comments');
            $request->request->remove('manager_comments');
        }

        if (isset($request->assessor_comments) && $isRejectRequest) {
            $request->merge(['assessor_comments' => trim(str_replace("&nbsp;", '', $request->assessor_comments))]);
        }

        if (isset($request->manager_comments) && $isRejectRequest) {
            $request->merge(['manager_comments' => trim(str_replace("&nbsp;", '', $request->manager_comments))]);
        }

        $this->validate($request, $this->getRules(), $this->getMessages());
        parent::__construct($request);
    }

    public function getRules()
    {
        $user = auth()->user();
        $isSendRequest = request()->is('checkpoints/*/assessor/send');
        $rulesAssessor = [
            'assessor_evaluate_process' => $isSendRequest ?
                                            [new CompleteFormRule(2), 'required', 'integer', 'min:1', 'max:10'] :
                                            [new CompleteFormRule(2), 'integer', 'min:1', 'max:10'],
            'assessor_evaluate_quality' => $isSendRequest ? 'required|integer|min:1|max:10' : 'integer|min:1|max:10',
            'assessor_evaluate_complex' => $isSendRequest ? 'required|integer|min:1|max:10' : 'integer|min:1|max:10',
            'assessor_evaluate_responsibility' => $isSendRequest ?
                                                            'required|integer|min:1|max:10' : 'integer|min:1|max:10',
            'assessor_evaluate_policy' => $isSendRequest ? 'required|integer|min:1|max:10' : 'integer|min:1|max:10',
            'assessor_comments' => 'sometimes|required',
        ];

        if ($user->hasRole('DD')) {
            $rulesAssessor = array_merge($rulesAssessor, [
                'manager_evaluate_ability' => 'integer|min:1|max:10',
                'manager_evaluate_activity' => 'integer|min:1|max:10',
                'manager_comments' => 'sometimes|required',
            ]);
        }

        return $rulesAssessor;
    }

    public function getMessages()
    {
        $fields = [
            'assessor_evaluate_process',
            'assessor_evaluate_quality',
            'assessor_evaluate_complex',
            'assessor_evaluate_responsibility',
            'assessor_evaluate_policy',
            'manager_evaluate_ability',
            'manager_evaluate_activity',
        ];
        return $this->generateMessage($fields);
    }
}
