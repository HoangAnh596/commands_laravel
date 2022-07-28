<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;
use App\Console\Commands\Traits\AssignRolePermissionTrait;

class AssignRolePermission extends Command
{

    use AssignRolePermissionTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:reassign';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reassign roles and permissions to users';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('model_has_roles')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $this->assignRolePermission();
        echo("Successfully assign roles and permissions to users");
    }
}
