<?php

namespace App\Http\Requests\Campaign;

use Illuminate\Http\Request;
use App\Http\Controllers\Common\Controller;

class ImportRequest extends Controller
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
            'file_data' => 'required',
            'file_type' => 'required|in:xlsx,csv',
            'file_name' => 'required',
        ];
    }

    public function getMessages()
    {
        return [
            'campaign_id.required' => trans('messages.errors.campaigns.import_file.campaign_id.required'),
            'file_data.required' => trans('messages.errors.campaigns.import_file.file_data.required'),
            'file_type.required' => trans('messages.errors.campaigns.import_file.file_type.required'),
            'file_type.in' => trans('messages.errors.campaigns.import_file.file_type.in'),
            'file_name.required' => trans('messages.errors.campaigns.import_file.file_name.required'),
        ];
    }
}
