<?php

namespace App\Http\Requests\Campaign;

use App\Services\CampaignServices;
use Illuminate\Http\Request;
use App\Http\Controllers\Common\Controller;

class EditRequest extends BaseCampaignRequest
{
    protected $campaignServices;

    public function __construct(Request $request, CampaignServices $campaignServices)
    {
        $this->campaignServices = $campaignServices;
        $this->validate($request, $this->getRules(), parent::getMessages());
        parent::__construct($request);
    }

    public function getRules()
    {
        $id = request()->route('id');
        $campaign = $this->campaignServices->findOrFail($id)->toArray();
        $valFields = [
            'start_date' => date('Y-m-d', strtotime(request()->get('start_date'))),
            'deadline_manager_assign' => 'after:start_date',
            'deadline_emp_complete' => 'after:deadline_manager_assign',
            'deadline_assessor_complete' => 'after:deadline_emp_complete',
            'deadline_manager_approve' => 'after:deadline_assessor_complete',
            'end_date' => 'after:deadline_manager_approve'
        ];

        foreach (array_keys($valFields) as $field) {
            if (strtotime(request()->get($field)) !== strtotime($campaign[$field])) {
                $valFields[$field] = 'after_or_equal:' . date('Y-m-d');
            }
        }
        return [
            'title' => 'required|max:500',
            'start_date' => 'required|date|after_or_equal:' . $valFields['start_date'],
            'deadline_manager_assign' => 'required|date|' . $valFields['deadline_manager_assign'],
            'deadline_emp_complete' => 'required|date|' . $valFields['deadline_emp_complete'],
            'deadline_assessor_complete' => 'required|date|' . $valFields['deadline_assessor_complete'],
            'deadline_manager_approve' => 'required|date|' . $valFields['deadline_manager_approve'],
            'end_date' => 'required|date|' . $valFields['end_date'],
        ];
    }
}
