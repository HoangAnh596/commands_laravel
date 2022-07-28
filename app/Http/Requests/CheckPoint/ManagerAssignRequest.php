<?php

namespace App\Http\Requests\CheckPoint;

use Illuminate\Http\Request;
use App\Http\Controllers\Common\Controller;
use App\Rules\Checkpoint\AssignAssessorRule;

class ManagerAssignRequest extends Controller
{
    public function __construct(Request $request)
    {
        $this->validate($request, $this->getRules(), $this->getMessages());
        parent::__construct($request);
    }

    public function getRules()
    {
        return [
            'assessor_id' => 'required|integer',
            'checkpoint_id' => ['required', new AssignAssessorRule()],
        ];
    }

    public function getMessages()
    {
        return [
            'assessor_id.required' => trans('messages.errors.checkpoints.manager_assign.assessor_id.required'),
            'assessor_id.integer' => trans('messages.errors.checkpoints.manager_assign.assessor_id.integer'),
            'checkpoint_id.required' => trans('messages.errors.checkpoints.manager_assign.checkpoint_id.required'),
            'checkpoint_id.array' => trans('messages.errors.checkpoints.manager_assign.checkpoint_id.array'),
        ];
    }
}
