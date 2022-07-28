<?php

namespace App\Repositories\Impl;

use App\Models\CheckPoint;
use App\Models\User;
use App\Repositories\CheckPointRepository;
use Illuminate\Support\Facades\DB;

class CheckPointRepositoryImpl extends BaseRepositoryImpl implements CheckPointRepository
{
    public function model()
    {
        return CheckPoint::class;
    }

    public function getListCheckpoint($params = [])
    {
        $pageSize = isset($params['pageSize']) ? $params['pageSize'] : config('app.per_page');
        $query = $this->search($params);
        $query->with(['assessor', 'manager', 'employee', 'plans', 'training']);
        if (isset($params['is_export'])) {
            return $query->get();
        }
        return $query->paginate($pageSize);
    }

    /**
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function search($params = [])
    {
        $user = auth()->user();
        $query = $this->model->newQuery();
        $query
            ->leftJoin('users', 'users.id', '=', 'checkpoints.emp_id')
            ->leftJoin('department', 'department.id', '=', 'users.department_id')
            ->select(
                'checkpoints.id as checkpoint_id',
                'checkpoints.emp_id',
                'checkpoints.emp_assignment',
                'checkpoints.emp_target',
                'checkpoints.emp_result',
                'checkpoints.status',
                'checkpoints.emp_total_final',
                'checkpoints.note',
                'checkpoints.emp_opinions',
                'checkpoints.assessor_id',
                'checkpoints.manager_id',
                'checkpoints.created_at',
                'checkpoints.assessor_opinions',
                'checkpoints.manager_opinions',
                'department.name as department_name',
                'users.id as user_id',
                'users.employee_code',
                'users.firstname',
                'users.lastname',
                'users.job_rank',
                'users.contract_type',
                'users.join_date',
                'users.department_id'
            )
            ->when(isset($params['campaign_id']), function ($query) use ($params) {
                $query->where('checkpoints.campaign_id', '=', $params['campaign_id']);
            })
            ->when(isset($params['assessor_id']), function ($query) use ($user, $params) {
                /* $query->where('checkpoints.manager_id', $user->id)
                    ->where('checkpoints.assessor_id', $params['assessor_id']); */
                $query->where('checkpoints.assessor_id', $params['assessor_id']);
            })
            ->when(isset($params['user_id']), function ($query) use ($params) {
                $query->where('checkpoints.emp_id', '=', $params['user_id']);
            })
            ->when(isset($params['department_id']), function ($query) use ($params) {
                $query->where('department.id', '=', $params['department_id']);
            })
            ->when(isset($params['contract_type']), function ($query) use ($params) {
                $query->where('users.contract_type', '=', $params['contract_type']);
            })
            ->when(isset($params['status']), function ($query) use ($params) {
                $query->where('checkpoints.status', '=', $params['status']);
            })
            ->when(isset($params['job_rank']), function ($query) use ($params) {
                if ($params['job_rank'] === "OTHER") {
                    $query->whereNotIn("users.job_family", User::JOB_FAMILY);
                    $query->orWhereNull("users.job_family");
                } else {
                    $query->where('users.job_family', '=', $params['job_rank']);
                }
            })
            ->when(isset($params['search_key']), function ($query) use ($params) {
                $searchKey = mb_strtolower($params['search_key']);
                $query->where(function ($subQuery) use ($searchKey) {
                    $subQuery->whereRaw('lower(users.username) like binary ?', ["%{$searchKey}%"])
                        ->orWhereRaw(
                            'lower(CONCAT(users.firstname, " ", users.lastname)) like binary ?',
                            ["%{$searchKey}%"]
                        );
                });
            })
            ->when(isset($params['department_manager_id']), function ($query) use ($user, $params) {
                $query->where(function ($subQuery) use ($params, $user) {
                    $subQuery->whereIn('users.department_id', $params['department_manager_id'])
                        ->orWhere('checkpoints.manager_id', '=', $user->id)
                        ->orWhere('checkpoints.assessor_id', '=', $user->id);
                });
            });

        if (isset($params['sort'])) {
            $arrSort = explode('-', $params['sort']);
            if (in_array('emp_total_final', $arrSort)) {
                $query->orderBy('status', 'desc');
            }
            if (count($arrSort) > 1) {
                $query->orderBy($arrSort[1], 'ASC');
            } else {
                $query->orderBy($arrSort[0], 'DESC');
            }
        }
        return $query;
    }

    public function getMyCheckpoint($userId)
    {
        $params['user_id'] = $userId;
        $query = $this->search($params);
        $query
            ->leftJoin('campaigns', 'campaigns.id', '=', 'checkpoints.campaign_id')
            ->addSelect(
                'campaigns.start_date',
                'campaigns.end_date',
                'campaigns.id as campaign_id'
            )
            ->with('assessor', 'manager');
        return $query->get();
    }

    public function getEmployeeAttributes()
    {
        return $this->model->getEmployeeAttributes();
    }

    public function getAssessorAttributes()
    {
        return $this->model->getAssessorAttributes();
    }

    public function getManagerAttributes()
    {
        return $this->model->getManagerAttributes();
    }

    public function searchReport($params = [])
    {
        $query = $this->model->newQuery();
        $query
            ->leftJoin('users', 'users.id', '=', 'checkpoints.emp_id')
            ->leftJoin('department', 'department.id', '=', 'users.department_id')
            ->select(DB::raw("CASE
                WHEN(checkpoints.emp_total_final>=9.25) THEN 'Xuất sắc'
                WHEN(checkpoints.emp_total_final>=8.5) THEN 'Tốt'
                WHEN(checkpoints.emp_total_final>=7) THEN 'Khá'
                WHEN(checkpoints.emp_total_final>=5.5) THEN 'Đạt'
                ELSE 'Chưa đạt' END AS result"))
            ->addSelect('department.id as department_id')
            ->when(isset($params['campaign_id']), function ($query) use ($params) {
                $query->where('checkpoints.campaign_id', '=', $params['campaign_id']);
            })
            ->when(isset($params['department_id']), function ($query) use ($params) {
                $query->where('department.id', '=', $params['department_id']);
            })
            ->when(isset($params['department_ids']), function ($query) use ($params) {
                $query->whereIn('department.id', $params['department_ids']);
            })
            ->where('checkpoints.status', '=', CheckPoint::STATUS_DONE);
        if (isset($params['is_chart'])) {
            $query
                ->addSelect(DB::raw("COUNT(*) as quantity"))
                ->groupBY('department.id', 'result');
        } else {
            $query->addSelect(
                'users.*',
                'department.name as department_name',
                'checkpoints.id as checkpoint_id',
                'checkpoints.emp_total_final'
            )->orderByDesc('checkpoints.emp_total_final');
        }
        return $query->get();
    }

    public function total($params)
    {
        $query = $this->search($params);
        $query
            ->select('checkpoints.status')
            ->addSelect(DB::raw("COUNT(checkpoints.id) as amount"))
            ->groupBy('checkpoints.status');
        return $query->get();
    }
}
