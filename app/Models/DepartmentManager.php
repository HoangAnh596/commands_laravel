<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartmentManager extends Model
{
    protected $table = "department_manager";

    protected $fillable = [
        'id',
        'department_id',
        'user_id',
        'role',
        'level'
    ];
}
