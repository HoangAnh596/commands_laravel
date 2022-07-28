<?php
// @codingStandardsIgnoreFile
namespace App\Http\Requests\Campaign;

use App\Services\CampaignServices;
use Illuminate\Http\Request;
use App\Http\Controllers\Common\Controller;

class BaseCampaignRequest extends Controller
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function getMessages()
    {
        return [
            'title.required' => trans('messages.errors.campaigns.store.title.required'),
            'title.max' => trans('messages.errors.campaigns.store.title.max'),
            'start_date.required' => trans('messages.errors.campaigns.store.start_date.required'),
            'start_date.date' => trans('messages.errors.campaigns.date', ['field_name' => 'Ngày bắt đầu']),
            'start_date.after_or_equal' => trans(
                'messages.errors.campaigns.date_after_or_equal',
                ['field_name' => 'Ngày bắt đầu', 'date' => date('Y-m-d')]
            ),
            'deadline_manager_assign.required' => trans(
                'messages.errors.campaigns.store.deadline_manager_assign.required'
            ),
            'deadline_manager_assign.date' => trans(
                'messages.errors.campaigns.date',
                ['field_name' => 'Thời hạn chỉ định CBQL trực tiếp cho nhân viên']
            ),
            'deadline_manager_assign.after' => trans(
                'messages.errors.campaigns.date_after',
                [
                    'field_name' => 'Thời hạn chỉ định CBQL trực tiếp cho nhân viên',
                    'date' => date('Y-m-d')
                ]
            ),
            'deadline_manager_assign.after_or_equal' => trans(
                'messages.errors.campaigns.date_after_or_equal',
                ['field_name' => 'Thời hạn chỉ định CBQL trực tiếp cho nhân viên', 'date' => date('Y-m-d')]
            ),
            'deadline_emp_complete.required' => trans('messages.errors.campaigns.store.deadline_emp_complete.required'),
            'deadline_emp_complete.date' => trans(
                'messages.errors.campaigns.date',
                ['field_name' => 'Thời hạn nhân viên hoàn thành form đánh giá']
            ),
            'deadline_emp_complete.after' => trans(
                'messages.errors.campaigns.date_after',
                [
                    'field_name' => 'Thời hạn nhân viên hoàn thành form đánh giá',
                    'date' => request()->get('deadline_manager_assign')
                ]
            ),
            'deadline_emp_complete.after_or_equal' => trans(
                'messages.errors.campaigns.date_after_or_equal',
                ['field_name' => 'Thời hạn nhân viên hoàn thành form đánh giá', 'date' => date('Y-m-d')]
            ),
            'deadline_assessor_complete.required' => trans(
                'messages.errors.campaigns.store.deadline_assessor_complete.required'
            ),
            'deadline_assessor_complete.date' => trans(
                'messages.errors.campaigns.date',
                ['field_name' => 'Thời hạn CBQL trực tiếp hoàn thành form đánh giá NV']
            ),
            'deadline_assessor_complete.after' => trans(
                'messages.errors.campaigns.date_after',
                [
                    'field_name' => 'Thời hạn CBQL trực tiếp hoàn thành form đánh giá NV',
                    'date' => request()->get('deadline_emp_complete')
                ]
            ),
            'deadline_assessor_complete.after_or_equal' => trans(
                'messages.errors.campaigns.date_after_or_equal',
                [
                    'field_name' => 'Thời hạn CBQL trực tiếp hoàn thành form đánh giá NV',
                    'date' => request()->get('deadline_emp_complete')
                ]
            ),
            'deadline_manager_approve.required' => trans(
                'messages.errors.campaigns.store.deadline_manager_approve.required'
            ),
            'deadline_manager_approve.date' => trans(
                'messages.errors.campaigns.date',
                ['field_name' => 'Thời hạn CBQL cấp 1 phê duyệt form đánh giá của NV']
            ),
            'deadline_manager_approve.after' => trans(
                'messages.errors.campaigns.date_after',
                [
                    'field_name' => 'Thời hạn CBQL cấp 1 phê duyệt form đánh giá của NV',
                    'date' => request()->get('deadline_assessor_complete')
                ]
            ),
            'deadline_manager_approve.after_or_equal' => trans(
                'messages.errors.campaigns.date_after_or_equal',
                [
                    'field_name' => 'Thời hạn CBQL cấp 1 phê duyệt form đánh giá của NV',
                    'date' => request()->get('deadline_assessor_complete')
                ]
            ),
            'end_date.required' => trans('messages.errors.campaigns.store.end_date.required'),
            'end_date.date' => trans('messages.errors.campaigns.date', ['field_name' => 'Ngày kết thúc']),
            'end_date.after' => trans(
                'messages.errors.campaigns.date_after',
                ['field_name' => 'Ngày kết thúc', 'date' => request()->get('deadline_manager_approve')]
            ),
            'end_date.after_or_equal' => trans(
                'messages.errors.campaigns.date_after_or_equal',
                ['field_name' => 'Ngày kết thúc', 'date' => request()->get('deadline_manager_approve')]
            ),
        ];
    }
}
