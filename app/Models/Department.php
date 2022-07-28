<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'department';

    protected $fillable = [
        'id',
        'parent_id',
        'name',
        'code',
        'd_type',
        'total_member',
        'description',
        'status',
    ];

    public function manageBy()
    {
        return $this->belongsToMany(User::class, 'department_manager', 'department_id', 'user_id');
    }
}
