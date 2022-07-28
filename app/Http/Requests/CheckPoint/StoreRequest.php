<?php

namespace App\Http\Requests\CheckPoint;

use Illuminate\Http\Request;
use App\Rules\Checkpoint\UniqueRule;
use App\Http\Controllers\Common\Controller;

class StoreRequest extends Controller
{
    public function __construct(Request $request)
    {
        $this->validate($request, $this->getRules(), $this->getMessages());
        parent::__construct($request);
    }

    public function getRules()
    {
        return [
            'campaign_id' => 'required',
            'emp_id' => 'required',
            'manager_id' => [
                'required',
                new UniqueRule(request()->get('campaign_id'), request()->get('manager_id'), request()->get('emp_id'))
            ],
        ];
    }

    public function getMessages()
    {
        return [];
    }
}
