<?php

namespace App\Console\Commands\Traits;

use App\Models\User;
use App\Models\DepartmentManager;
use Spatie\Permission\Models\Role;

trait AssignRolePermissionTrait
{
    public function assignRolePermission()
    {
        $roleDD = Role::where('name', 'DD')->first();
        $roleEmp = Role::where('name', 'EMP')->first();
        $roleHR = Role::where('name', 'HR')->first();

        // Assign role DD
        $usersDDId = DepartmentManager::select('user_id')->groupBy('user_id')->pluck('user_id');
        $usersDD = User::whereIn('id', $usersDDId)->get();
        foreach ($usersDD as $userDD) {
            $userDD->assignRole($roleDD);
            $userDD->assignRole($roleEmp);
        }

        // Assign role EMP
        $usersEmp = User::whereNotIn('id', $usersDDId)->get();
        foreach ($usersEmp as $userEmp) {
            $userEmp->syncRoles($roleEmp);
        }

        //Assign role HR
        $userHR = User::where('email', config('app.hr_email'))->first();
        if ($userHR) {
            $userHR->assignRole($roleHR);
        }
    }
}
