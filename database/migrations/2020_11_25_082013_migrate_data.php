<?php

use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Database\Migrations\Migration;
use App\Console\Commands\Traits\AssignRolePermissionTrait;

// @codingStandardsIgnoreLine
class MigrateData extends Migration
{

    use AssignRolePermissionTrait;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->migratePermissions();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('model_has_roles')->truncate();
        \DB::table('role_has_permissions')->truncate();
        \DB::table('model_has_permissions')->truncate();
        \DB::table('permissions')->truncate();
        \DB::table('roles')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function migratePermissions()
    {
        Artisan::call('sync-data/member');
        Artisan::call('sync-data/department');

        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $roles = config('auth.roles');

        $permissions = array_merge(
            array_values(config('auth.permissions.campaign')),
            array_values(config('auth.permissions.checkpoint')),
            array_values(config('auth.permissions.report'))
        );

        foreach ($permissions as $name) {
            Permission::firstOrCreate(['name' => $name]);
        }

        foreach ($roles as $name => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $name]);
            foreach ($rolePermissions as $permission) {
                $role->givePermissionTo(config('auth.' . $permission));
            }
        }

        $this->assignRolePermission();
    }
}
