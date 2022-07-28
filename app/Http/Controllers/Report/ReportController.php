<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Common\Controller;
use App\Services\CampaignServices;
use App\Services\CheckPointServices;
use App\Services\DepartmentManagerServices;
use App\Services\DepartmentServices;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected $checkpointServices;
    protected $departmentServices;
    protected $campaignServices;
    public $departmentManagerServices;

    public function __construct(
        CheckPointServices $checkPointServices,
        DepartmentServices $departmentServices,
        CampaignServices $campaignServices,
        DepartmentManagerServices $departmentManagerServices
    ) {
        $this->checkpointServices = $checkPointServices;
        $this->departmentServices = $departmentServices;
        $this->campaignServices = $campaignServices;
        $this->departmentManagerServices = $departmentManagerServices;
    }

    public function index()
    {
        $user = auth()->user();
        $isRoleHrOrDD = $user->hasRole(['DD', 'HR']);
        if (!$isRoleHrOrDD) {
            throw new AuthorizationException("403");
        }
        $campaign = $this->campaignServices->getCurrentCampaign();
        if (empty($campaign)) {
            return response()->json([
                'data' => []
            ]);
        }
        $campaignId = request()->get('campaign_id') ?? $campaign->id;

        $dataRp['chart'] = $this->checkpointServices->searchReport([
            'campaign_id' => $campaignId,
            'is_chart' => 1
        ]);

        $dataRp['table'] = $this->checkpointServices->searchReport([
            'campaign_id' => $campaignId
        ]);
        $departments = $this->departmentServices->departmentRepo->all();
        if ($user->hasRole('HR')) {
            $data['back_office'] = $this->reportByBackOffice($dataRp, $departments);
            $data['software'] = $this->reportBySoftware($dataRp, $departments);
        }
        if ($user->hasRole('DD')) {
            $data['department'] = $this->reportByDepartment($campaignId, $departments);
        }
        return response()->json(['data' => $data]);
    }

    public function reportByBackOffice($dataReport, $departments)
    {
        $departmentBO = [];
        foreach ($departments as $item) {
            if (in_array($item->name, ['HR', 'ADMIN', 'PR', 'QA', 'KAIZEN', 'FINANCE-ACC', 'TRAINING', 'IT'])) {
                array_push($departmentBO, $item);
            }
        }
        $data = $this->formatData($dataReport, $departmentBO);
        return $data;
    }

    public function reportBySoftware($dataReport, $departments)
    {
        $deptSoftware = [];
        foreach ($departments as $item) {
            if (in_array($item->name, ['D1', 'D2', 'D3', 'D5', 'G6'])) {
                array_push($deptSoftware, $item);
            }
        }
        $data = $this->formatData($dataReport, $deptSoftware);
        return $data;
    }

    /**
     * Report by department for assessor_id
     * @param $campaignId
     * @param $departments
     * @return mixed
     */
    public function reportByDepartment($campaignId, $departments)
    {
        $deptManager = $this->departmentManagerServices->allQuery(['user_id' => auth()->user()->id])->get();
        $departmentIds = [];
        if (!empty($deptManager)) {
            foreach ($deptManager as $item) {
                $departmentIds[] = $item->department_id;
            }
        } else {
            return response()->json([
                'message' => 'Department manager null'
            ], 503);
        }


        $dataReport['chart'] = $this->checkpointServices->searchReport([
            'campaign_id' => $campaignId,
            'department_ids' => $departmentIds,
            'is_chart' => 1
        ]);
        $dataReport['table'] = $this->checkpointServices->searchReport([
            'campaign_id' => $campaignId,
            'department_ids' => $departmentIds
        ]);


        $dataFormat = $this->formatData($dataReport, $departments);
        $departmentManager = $this->departmentServices->departmentRepo->whereIn('id', $departmentIds)->get();
        $departmentNames = [];
        foreach ($departmentManager as $item) {
            $departmentNames[] = $item->name;
        }
        $data['chart'] = [];
        foreach ($dataFormat['chart'] as $deptName => $item) {
            if (in_array($deptName, $departmentNames)) {
                $data['chart'][$deptName] = $item;
            }
        }
        $data['table'] = $dataFormat['table'];
        return $data;
    }

    public function formatData($dataReport, $departments)
    {
        $data['chart'] = [];
        $dataTmp['table'] = [];

        foreach ($departments as $dept) {
            $data['chart'][$dept->name] = [
                'Xuất sắc' => 0,
                'Tốt' => 0,
                'Khá' => 0,
                'Đạt' => 0,
                'Chưa đạt' => 0,
            ];
            if (!empty($dataReport['chart'])) {
                foreach ($dataReport['chart'] as $rp) {
                    if ($rp->department_id === $dept->id) {
                        $data['chart'][$dept->name][$rp->result] = $rp->quantity;
                    }
                }
            }
            if (!empty($dataReport['table'])) {
                foreach ($dataReport['table'] as $item) {
                    if ($item->department_id === $dept->id) {
                        $dataTmp['table'][] = [
                            'checkpoint_id' => $item->checkpoint_id,
                            'employee_code' => $item->employee_code,
                            'department_name' => $item->department_name,
                            'fullname' => $item->firstname . ' ' . $item->lastname,
                            'email' => $item->email,
                            'result' => $item->result,
                            'emp_total_final' => $item->emp_total_final,
                            'department_id' => $item->department_id
                        ];
                    }
                }
            }
        }
        $collectTable = collect($dataTmp['table']);
        $collectTable = $collectTable->sortByDesc(function ($collect) {
            return $collect['emp_total_final'];
        });
        $data['table'] = [];
        foreach ($collectTable as $item) {
            $data['table'][] = $item;
        }
        return $data;
    }
}
