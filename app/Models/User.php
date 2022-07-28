<?php
// @codingStandardsIgnoreLine
namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Lumen\Auth\Authorizable;

/**
 * @SuppressWarnings(PHPMD)
 */
class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasRoles;

    const JOB_FAMILY = ["DEVELOPER", "TESTER", "COMTOR", "BrSE", "BA", "SA", "PM", "QA"];

    protected $fillable = [
        'id',
        'username',
        'email',
        'firstname',
        'lastname',
        'department_id',
        'employee_code',
        'job_position',
        'job_rank',
        'job_family',
        'contract_type',
        'join_date',
        'created_at',
        'updated_at'
    ];

    protected $guard_name = 'api';

    public function manageDepartments()
    {
        return $this->belongsToMany(Department::class, 'department_manager', 'user_id', 'department_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function managers()
    {
        return $this->hasMany(DepartmentManager::class, "department_id", "department_id");
    }
}
