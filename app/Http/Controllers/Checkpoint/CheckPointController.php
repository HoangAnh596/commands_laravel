<?php

namespace App\Http\Controllers\Checkpoint;

use App\Models\CheckPoint;
use App\Events\RejectFormByAssessor;
use App\Events\RejectFormByManager;
use App\Services\CampaignServices;
use App\Services\DepartmentManagerServices;
use App\Services\UserServices;
use App\Http\Controllers\Common\Controller;
use App\Http\Requests\CheckPoint\AssessorSaveFormRequest;
use App\Http\Resources\Checkpoint as CheckpointResource;
use App\Http\Requests\CheckPoint\StoreRequest;
use App\Http\Requests\CheckPoint\EmpSaveFormRequest;
use App\Http\Requests\CheckPoint\ManagerAssignRequest;
use App\Http\Requests\CheckPoint\ManagerSaveFormRequest;
use App\Jobs\NotifyAssessorEvaluating;
use App\Jobs\NotifyEmployeeFillinForm;
use App\Jobs\NotifyEmployeeResult;
use App\Library\Common;
use App\Models\EmployeePlan;
use App\Models\EmployeeTraining;
use App\Services\CheckPointServices;
use Carbon\Carbon;
use DB;
use Exception;

/**
 * @SuppressWarnings(PHPMD)
 */
class CheckPointController extends Controller
{
    private $checkpointServices;
    private $userServices;
    private $campaignServices;
    public $currentCampaign;
    public $departmentManagerServices;

    public function __construct(
        CheckPointServices $checkPointServices,
        UserServices $userServices,
        CampaignServices $campaignServices,
        DepartmentManagerServices $departmentManagerServices
    ) {
        $this->checkpointServices = $checkPointServices;
        $this->userServices = $userServices;
        $this->campaignServices = $campaignServices;
        $this->currentCampaign = $this->campaignServices->getCurrentCampaign();
        $this->departmentManagerServices = $departmentManagerServices;
    }

    public function index()
    {
//        $this->authorize('showList', CheckPoint::class);
        $user = auth()->user();
        $isDD = $user->hasRole('DD');
        $isPM = $user->hasRole('PM');
        $params = request()->all();
        if (!isset($params['campaign_id']) && $this->currentCampaign) {
            $params['campaign_id'] = $this->currentCampaign->id;
        }
        if ($isDD || $isPM) {
            $params['department_manager_id'] = $this->departmentManagerServices->getDepartmentIdByManager();
        }

        $data = $this->checkpointServices->getListCheckpoint($params);

        if (isset($params['is_export'])) {
            $campaign = $this->campaignServices->find($params['campaign_id']);
            $dataExp = CheckpointResource::collection($data);
            return $this->checkpointServices->exportExcel($dataExp->collection->toArray(), $campaign);
        }
        return CheckpointResource::collection($data);
    }

    public function myCheckPoint()
    {
        $this->authorize('showMyCheckpoint', CheckPoint::class);
        $userId = auth()->user()->id;
        $data = $this->checkpointServices->getMyCheckpoint($userId);
        return CheckpointResource::collection($data);
    }

    public function store(StoreRequest $request)
    {
        $params = $this->checkpointServices->getFillableFields();
        $params = $request->getParams($params);
        $checkpoint = $this->checkpointServices->create($params);
        return response()->json([
            'data' => $checkpoint,
        ]);
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function assignAssessor(ManagerAssignRequest $request)
    {
        $errors = [];
        $employees = [];
        $params = $request->getParams(['assessor_id', 'checkpoint_id']);
        $params['checkpoint_id'] = explode(',', $params['checkpoint_id']);
        $assessor = $this->userServices->find($params['assessor_id']);
        foreach ($params['checkpoint_id'] as $checkPointId) {
            try {
                $checkpoint = $this->checkpointServices->findOrFail($checkPointId);
                $assessorOld = $this->userServices->find($checkpoint->assessor_id);
                if ($assessor->id === $checkpoint->emp_id) {
                    $errors[] = [
                        "message" => "can't evaluate its checkpoint!",
                        "employee_name" => $assessor->username
                    ];
                    return response()->json([
                        'success' => false,
                        'data' => $errors,
                    ]);
                }

                $this->authorize('assignAssessor', $checkpoint);
                $params['status'] = CheckPoint::STATUS_INPROGRESS;
                $checkpoint = $this->checkpointServices->update($checkPointId, $params);
                $checkpoint->load(['employee']);
                if ($assessorOld && $assessorOld->hasRole('PM')) {
                    $assessorOld->removeRole('PM');
                }
                $assessor->assignRole('PM');

                $employee = $checkpoint->employee;
                if (config('app.mode') == Common::MODE_STG) {
                    $employee->email = config('app.mail_test');
                }
                if (!in_array($employee, $employees)) {
                    $employee->checkpoint_id = $checkpoint->id;
                    $employees[] = $employee;
                }
            } catch (Exception $e) {
                $errors[] = [
                    'checkpoint_id' => $checkPointId,
                    'employee_code' => isset($checkpoint) ? $checkpoint->employee->employee_code : '',
                    'employee_name' => isset($checkpoint) ?
                        $checkpoint->employee->firstname . ' ' . $checkpoint->employee->lastname : '',
                    'message' => $e->getMessage(),
                ];
            }
        }

        if (config('app.mode') == Common::MODE_STG) {
            $assessor->email = config('app.mail_test');
        }

        dispatch((new NotifyAssessorEvaluating([$assessor], $this->currentCampaign))
            ->delay(\Carbon\Carbon::now()->addSeconds(30)));

        dispatch((new NotifyEmployeeFillinForm($employees, $this->currentCampaign))
            ->delay(\Carbon\Carbon::now()->addSeconds(60)));

        return response()->json([
            'success' => count($errors) ? false : true,
            'data' => $errors,
        ]);
    }

    public function saveFormByEmployee(EmpSaveFormRequest $request, $id)
    {
        $checkpoint = $this->updateFormEmployee($request, $id);
        return new CheckpointResource($checkpoint->load('plans', 'training'));
    }

    public function sendFormByEmployee(EmpSaveFormRequest $request, $id)
    {
        $checkpoint = $this->updateFormEmployee($request, $id);
        $checkpoint->status = CheckPoint::STATUS_REVIEWING;
        $checkpoint->save();

        //TODO: send email to assessor
        return new CheckpointResource($checkpoint->load('plans', 'training'));
    }

    protected function updateFormEmployee(EmpSaveFormRequest $request, $id)
    {
        $checkpoint = $this->checkpointServices->findOrFail($id);
        $this->authorize('saveFormEmp', $checkpoint);
        $params = $request->getParams($this->checkpointServices->getEmployeeAttributes());
        $employeePlan = isset($params['emp_plan']) ? json_decode($params['emp_plan'], true) : [];
        $employeeTraining = isset($params['emp_training']) ? json_decode($params['emp_training'], true) : [];
        unset($params['emp_plan']);
        unset($params['emp_training']);
        $checkpoint = $this->checkpointServices->update($id, $params);
        $employeePoint = $checkpoint->calculateEmpPoint();
        $checkpoint->emp_total_final = $employeePoint;
        $checkpoint->save();

        if (count($employeePlan)) {
            try {
                DB::beginTransaction();
                EmployeePlan::where('checkpoint_id', $id)->delete();
                foreach ($employeePlan as $valuePlan) {
                    $valuePlan['checkpoint_id'] = $id;
                    EmployeePlan::create($valuePlan);
                }
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                \Log::error($e);
            }
        }

        if (count($employeeTraining)) {
            try {
                DB::beginTransaction();
                EmployeeTraining::where('checkpoint_id', $id)->delete();
                foreach ($employeeTraining as $valueTraining) {
                    $valueTraining['checkpoint_id'] = $id;
                    EmployeeTraining::create($valueTraining);
                }
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                \Log::error($e);
            }
        }

        $checkpoint = $checkpoint->loadRelationship();
        return $checkpoint;
    }

    public function saveFormByAssessor(AssessorSaveFormRequest $request, $id)
    {
        $checkpoint = $this->updateFormAssessor($request, $id);
        return new CheckpointResource($checkpoint);
    }

    public function sendFormByAssessor(AssessorSaveFormRequest $request, $id)
    {
        $checkpoint = $this->updateFormAssessor($request, $id);
        $user = auth()->user();
        if ($user->hasAllRoles(['DD', 'PM']) ||
            ($user->id == $checkpoint->manager_id && $user->id == $checkpoint->assessor_id)) {
            $checkpoint->status = CheckPoint::STATUS_DONE;
            dispatch((new NotifyEmployeeResult($checkpoint->load([
                'employee' => function ($query) {
                    $query->with('department');
                },
                'campaign',
                'assessor'
            ])))->delay(\Carbon\Carbon::now()->addSeconds(30)));
        } else {
            $checkpoint->status = CheckPoint::STATUS_APPROVING;
        }

        if ($checkpoint->assessor_reject != null) {
            $checkpoint->assessor_reject = config('app.status.off');
        }

        $checkpoint->save();
        return new CheckpointResource($checkpoint);
    }

    public function rejectFormByAssessor(AssessorSaveFormRequest $request, $id)
    {
        $checkpoint = $this->updateFormAssessor($request, $id);
        $checkpoint = $checkpoint->load([
            'campaign',
            'employee',
            'plans' => function ($query) {
                $query->select(
                    'checkpoint_id',
                    'emp_assignment',
                    'emp_criterion',
                    'emp_deadline',
                    'emp_priority'
                );
            },
            'training' => function ($query) {
                $query->select(
                    'checkpoint_id',
                    'emp_target_training',
                    'emp_demand_training',
                    'emp_content_training',
                    'emp_format_training',
                    'emp_time_training'
                );
            }
        ]);
        $empCompleteDate = Carbon::parse($checkpoint->campaign->deadline_emp_complete);
        $now = Carbon::now();
        if ($now->gt($empCompleteDate)) {
            $extraDateStart = $now->addDays(2)->format('Y-m-d');
            $checkpoint->extra_emp_complete = $extraDateStart;
        }

        $checkpointAttributes = $checkpoint->attributesToArray();
        $employeeAttributes = $this->checkpointServices->getEmployeeAttributes();
        $historyEmployee = Common::filterElementArray($checkpointAttributes, $employeeAttributes);
        $historyEmployee['plans'] = $checkpoint->plans;
        $historyEmployee['training'] = $checkpoint->training;

        $checkpoint->history_emp = json_encode($historyEmployee);
        $checkpoint->status = CheckPoint::STATUS_INPROGRESS;
        $checkpoint->assessor_reject = config('app.status.on');
        $checkpoint->save();

        //Fire event sending email to employee when assessor reject form
        event(new RejectFormByAssessor($checkpoint));
        return new CheckpointResource($checkpoint);
    }

    protected function updateFormAssessor(AssessorSaveFormRequest $request, $id)
    {
        $checkpoint = $this->checkpointServices->findOrFail($id);
        $this->authorize('saveFormAssessor', $checkpoint);
        $params = $request->getParams($this->checkpointServices->getAssessorAttributes());
        if (auth()->user()->hasRole('DD')) {
            $params = array_merge($params, $request->getParams($this->checkpointServices->getManagerAttributes()));
        }
        $checkpoint = $this->checkpointServices->update($id, $params);
        $employeePoint = $checkpoint->calculateEmpPointFinal();
        $checkpoint->emp_total_final = $employeePoint;
        $checkpoint->save();
        $checkpoint = $checkpoint->loadRelationship();
        return $checkpoint;
    }

    public function saveFormByManager(ManagerSaveFormRequest $request, $id)
    {
        $checkpoint = $this->updateFormByManager($request, $id, 1);
        return new CheckpointResource($checkpoint);
    }

    public function approveFormByManager(ManagerSaveFormRequest $request, $id)
    {
        $checkpoint = $this->updateFormByManager($request, $id, 2);
        $checkpoint->status = CheckPoint::STATUS_DONE;

        if ($checkpoint->manager_reject != null) {
            $checkpoint->manager_reject = config('app.status.off');
        }

        $checkpoint->save();
        dispatch((new NotifyEmployeeResult($checkpoint->load([
            'employee' => function ($query) {
                $query->with('department');
            },
            'campaign',
            'assessor'
        ])))->delay(\Carbon\Carbon::now()->addSeconds(30)));
        return new CheckpointResource($checkpoint);
    }

    public function rejectFormByManager(ManagerSaveFormRequest $request, $id)
    {
        $checkpoint = $this->updateFormByManager($request, $id, 3);
        $checkpoint = $checkpoint->load(['assessor', 'campaign']);
        $assessorCompleteDate = Carbon::parse($checkpoint->campaign->deadline_assessor_complete);
        $now = Carbon::now();
        if ($now->gt($assessorCompleteDate)) {
            $extraDateStart = $now->addDays(2)->format('Y-m-d');
            $checkpoint->extra_assessor_complete = $extraDateStart;
        }

        $checkpointAttributes = $checkpoint->attributesToArray();
        $assessorAttributes = $this->checkpointServices->getAssessorAttributes();
        $historyAssessor = Common::filterElementArray($checkpointAttributes, $assessorAttributes);

        $checkpoint->history_assessor = json_encode($historyAssessor);
        $checkpoint->status = CheckPoint::STATUS_REVIEWING;
        $checkpoint->manager_reject = config('app.status.on');
        $checkpoint->save();

        //Fire event sending email to assessor when manager reject form
        event(new RejectFormByManager($checkpoint));
        return new CheckpointResource($checkpoint);
    }

    protected function updateFormByManager(ManagerSaveFormRequest $request, $id, $action)
    {
        $checkpoint = $this->checkpointServices->findOrFail($id);
        switch ($action) {
            case 1:
                $actionAuthorization = 'saveFormManager';
                break;
            case 2:
                $actionAuthorization = 'approveFormManager';
                break;
            case 3:
                $actionAuthorization = 'rejectFormManager';
                break;
            default:
                $actionAuthorization = 'saveFormManager';
                break;
        }

        $params = $this->checkpointServices->getManagerAttributes();
        $note = request()->get('note');
        if (isset($note)) {
            $actionAuthorization = 'saveNoteManager';
            $params = ['note'];
        }

        $this->authorize($actionAuthorization, $checkpoint);
        $params = $request->getParams($params);
        $checkpoint = $this->checkpointServices->update($id, $params);
        $employeePoint = $checkpoint->calculateEmpPointFinal();
        $checkpoint->emp_total_final = $employeePoint;
        $checkpoint->save();
        $checkpoint = $checkpoint->loadRelationship();
        return $checkpoint;
    }

    public function show($id)
    {
        $checkpoint = $this->checkpointServices->find($id);
        $this->authorize('view', $checkpoint);
        $checkpoint = $checkpoint->loadRelationship();
        return new CheckpointResource($checkpoint);
    }

    public function total()
    {
        $params = request()->all();
        $user = auth()->user();
        if (!empty($this->currentCampaign)) {
            $params['campaign_id'] = $this->currentCampaign->id;
        }
        if ($user->hasRole('DD') || $user->hasRole('PM')) {
            $params['department_manager_id'] = $this->departmentManagerServices->getDepartmentIdByManager();
        }
        $params['sort'] = null;
        return $this->checkpointServices->total($params);
    }
}
