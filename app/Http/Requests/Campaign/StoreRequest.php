<?php

namespace App\Http\Requests\Campaign;

use Illuminate\Http\Request;

class StoreRequest extends BaseCampaignRequest
{
    public function __construct(Request $request)
    {
        $this->validate($request, $this->getRules(), parent::getMessages());
        parent::__construct($request);
    }

    public function getRules()
    {
        return [
            'title' => 'required|max:500',
            'start_date' => 'required|date|after_or_equal:' . date('Y-m-d'),
            'deadline_manager_assign' => 'required|date|after:start_date',
            'deadline_emp_complete' => 'required|date|after:deadline_manager_assign',
            'deadline_assessor_complete' => 'required|date|after:deadline_emp_complete',
            'deadline_manager_approve' => 'required|date|after:deadline_assessor_complete',
            'end_date' => 'required|date|after:deadline_manager_approve',
        ];
    }
}
