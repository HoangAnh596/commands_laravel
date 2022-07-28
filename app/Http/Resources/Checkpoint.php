<?php

namespace App\Http\Resources;

use App\Library\Common;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class Checkpoint extends JsonResource
{
    /**
     * @SuppressWarnings("unused")
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $result = [];
        $user = auth()->user();
        $roles = ['DD' => $this->manager_id, 'PM' => $this->assessor_id, 'EMP' => $this->emp_id];
        $arrRoles = [];
        foreach ($roles as $key => $value) {
            if ($user->id === $value) {
                $arrRoles[] = $key;
            }
        }
        $cheManagers = Arr::pluck($this->employee->managers, 'user_id');
        if (in_array($user->id, $cheManagers)) {
            $arrRoles[] = 'DD';
        }

        $attributes = [
            'user_id',
            'employee_code',
            'job_rank',
            'contract_type',
            'join_date',
            'assessor',
            'note',
            'department_name',
            'start_date',
            'end_date',
        ];

        foreach ($attributes as $attr) {
            if (isset($this->$attr) && $this->$attr) {
                if ($attr == 'start_date' || $attr == 'end_date') {
                    $result[$attr] = date("d-m-Y", strtotime($this->$attr));
                } elseif ($attr == 'join_date') {
                    $result['date_join'] = Common::formatTimeWorking($this->$attr);
                } else {
                    $result[$attr] = $this->$attr;
                }
            }
        }

        if (isset($this->firstname) && isset($this->lastname)) {
            $result['fullname'] = $this->firstname . ' ' . $this->lastname;
        }

        if (isset($this->campaign) && $this->campaign) {
            $campaignStartMonth = date("m", strtotime($this->campaign->start_date));
            $campaignStartYear = date("Y", strtotime($this->campaign->start_date));
            $result['checkpoint_date'] = "Tháng {$campaignStartMonth} năm {$campaignStartYear}";
            $result['campaign_start'] = $this->campaign->start_date;
            $result['campaign_status'] = $this->campaign->status;
            unset($this->campaign);
        }

        return array_merge(parent::toArray($request), [
            'role' => $arrRoles,
            'role_list' => array_unique(array_merge($user->getRoleNames()->toArray(), $arrRoles)),
            'emp_point_tmp' => $this->calculateEmpPoint(),
        ], $result);
    }
}
