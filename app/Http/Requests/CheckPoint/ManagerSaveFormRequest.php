<?php

namespace App\Http\Requests\CheckPoint;

use Illuminate\Http\Request;
use App\Http\Controllers\Common\Controller;
use App\Rules\Checkpoint\CompleteFormRule;
use App\Http\Requests\CheckPoint\Traits\MessageValidationFormTrait;

class ManagerSaveFormRequest extends Controller
{
    use MessageValidationFormTrait;

    public function __construct(Request $request)
    {
        $isRejectRequest = request()->is('checkpoints/*/manager/reject');
        if (isset($request->manager_comments) && $isRejectRequest) {
            $request->replace(['manager_comments' => trim(str_replace("&nbsp;", '', $request->manager_comments))]);
        }
        $this->validate($request, $this->getRules(), $this->getMessages());
        parent::__construct($request);
    }

    public function getRules()
    {
        return [
            'manager_evaluate_ability' => [new CompleteFormRule(3), 'integer', 'min:1', 'max:10'],
            'manager_evaluate_activity' => 'integer|min:1|max:10',
            'manager_comments' => 'sometimes|required',
        ];
    }

    public function getMessages()
    {
        $fields = [
            'manager_evaluate_ability',
            'manager_evaluate_activity',
        ];
        return $this->generateMessage($fields);
    }
}
