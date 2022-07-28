<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeePlan extends Model
{
    protected $table = 'employee_plans';
    protected $fillable = [
        'checkpoint_id',
        'emp_assignment',
        'emp_criterion',
        'emp_deadline',
        'emp_priority',
    ];
}
