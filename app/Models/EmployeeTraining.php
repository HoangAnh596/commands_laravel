<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeTraining extends Model
{
    protected $table = 'employee_training';
    protected $fillable = [
        'checkpoint_id',
        'emp_target_training',
        'emp_demand_training',
        'emp_content_training',
        'emp_format_training',
        'emp_time_training',
    ];
}
