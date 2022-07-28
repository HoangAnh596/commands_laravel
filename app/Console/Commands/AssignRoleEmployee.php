<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class AssignRoleEmployee extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assign-role:emp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign employee role to users';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $roleEmp = Role::where('name', 'EMP')->first();
        $users = User::all();
        foreach ($users as $user) {
            $user->assignRole($roleEmp);
        }
        echo 'Assigned employee role to users';
    }
}
