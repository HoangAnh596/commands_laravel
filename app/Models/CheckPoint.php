<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckPoint extends Model
{
    const STATUS_NEW = 1;
    const STATUS_INPROGRESS = 2;
    const STATUS_REVIEWING = 3;
    const STATUS_APPROVING = 4;
    const STATUS_DONE = 5;

    protected $table = 'checkpoints';
    protected $guarded = ['id'];

    protected $employeeAttributes = [
        'emp_assignment',
        'emp_target',
        'emp_result',
        'emp_evaluate_process',
        'emp_evaluate_quality',
        'emp_evaluate_complex',
        'emp_evaluate_responsibility',
        'emp_evaluate_policy',
        'emp_opinions',
        'emp_plan',
        'emp_training',
    ];

    protected $assessorAttributes = [
        'assessor_evaluate_process',
        'assessor_evaluate_quality',
        'assessor_evaluate_complex',
        'assessor_evaluate_responsibility',
        'assessor_evaluate_policy',
        'assessor_opinions',
        'assessor_comments',
    ];

    protected $managerAttributes = [
        'manager_evaluate_ability',
        'manager_evaluate_activity',
        'manager_opinions',
        'note',
        'manager_comments',
    ];

    public function assessor()
    {
        return $this->belongsTo(User::class, 'assessor_id', 'id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id', 'id');
    }

    public function getEmployeeAttributes()
    {
        return $this->employeeAttributes;
    }

    public function getAssessorAttributes()
    {
        return $this->assessorAttributes;
    }

    public function getManagerAttributes()
    {
        return $this->managerAttributes;
    }

    public function plans()
    {
        return $this->hasMany(EmployeePlan::class, 'checkpoint_id', 'id');
    }

    public function training()
    {
        return $this->hasMany(EmployeeTraining::class, 'checkpoint_id', 'id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'emp_id', 'id');
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id', 'id');
    }

    public function calculateEmpPoint()
    {
        $employeePoint = 0;

        $userId = auth()->user()->id;

        if ((int)$this->status === self::STATUS_INPROGRESS) {
            $employeePoint = $this->calculateWithEmp($employeePoint);
        }
        if ((int)$this->status === self::STATUS_REVIEWING) {
            if ($this->assessor_id === $userId) {
                $employeePoint = $this->calculateWithAssessor($employeePoint);
            } else {
                $employeePoint = $this->calculateWithEmp($employeePoint);
            }
        }

        if ((int)$this->status >= self::STATUS_APPROVING) {
            $employeePoint += $this->calculateWithAssessor($employeePoint);
            if ($this->manager_id === $userId && (int)$this->status === self::STATUS_APPROVING) {
                $employeePoint += $this->manager_evaluate_ability * config('app.percentage_ability');
                $employeePoint += $this->manager_evaluate_activity * config('app.percentage_activity');
            }
            if ((int)$this->status === self::STATUS_DONE) {
                $employeePoint += $this->manager_evaluate_ability * config('app.percentage_ability');
                $employeePoint += $this->manager_evaluate_activity * config('app.percentage_activity');
            }
        }
        $employeePoint = round($employeePoint, 2);
        return $employeePoint;
    }

    public function calculateWithEmp($employeePoint = 0)
    {
        $employeePoint += ($this->emp_evaluate_process) * config('app.percentage_process');
        $employeePoint += ($this->emp_evaluate_quality) * config('app.percentage_quality');
        $employeePoint += ($this->emp_evaluate_complex) * config('app.percentage_complex');
        $employeePoint += ($this->emp_evaluate_responsibility) * config('app.percentage_responsibility');
        $employeePoint += ($this->emp_evaluate_policy) * config('app.percentage_policy');
        return $employeePoint;
    }

    public function calculateWithAssessor($employeePoint = 0)
    {
        $employeePoint += ($this->emp_evaluate_process + $this->assessor_evaluate_process) * 0.5
            * config('app.percentage_process');
        $employeePoint += ($this->emp_evaluate_quality + $this->assessor_evaluate_quality) * 0.5
            * config('app.percentage_quality');
        $employeePoint += ($this->emp_evaluate_complex + $this->assessor_evaluate_complex) * 0.5
            * config('app.percentage_complex');
        $employeePoint += ($this->emp_evaluate_responsibility + $this->assessor_evaluate_responsibility)
            * 0.5 * config('app.percentage_responsibility');
        $employeePoint += ($this->emp_evaluate_policy + $this->assessor_evaluate_policy) * 0.5
            * config('app.percentage_policy');
        return $employeePoint;
    }

    public function loadRelationship()
    {
        return $this->load([
            'employee' => function ($query) {
                $query->with('department');
            },
            'employee.managers',
            'assessor',
            'manager',
            'plans',
            'training'
        ]);
    }

    public function calculateEmpPointFinal()
    {
        $employeePoint = 0;
        $employeePoint = $this->calculateWithAssessor($employeePoint);
        $employeePoint += $this->manager_evaluate_ability * config('app.percentage_ability');
        $employeePoint += $this->manager_evaluate_activity * config('app.percentage_activity');
        $employeePoint = round($employeePoint, 2);
        return $employeePoint;
    }
}
