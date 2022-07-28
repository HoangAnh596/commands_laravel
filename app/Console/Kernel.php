<?php

namespace App\Console;

use App\Console\Commands\AssignRoleEmployee;
use App\Console\Commands\AssignRolePermission;
use App\Console\Commands\NotifyListAssessorCompletingForm;
use App\Console\Commands\NotifyListEmpCompletingForm;
use App\Console\Commands\RemindAssessorCompleteCommand;
use App\Console\Commands\RemindEmpCompleteCommand;
use App\Console\Commands\RemindManagerApprovalCommand;
use App\Console\Commands\RemindManagerAssignCommand;
use App\Console\Commands\ResetCachePermissionCommand;
use App\Console\Commands\NotifyCheckPointManager;
use App\Console\Commands\SyncDataDepartmentCommand;
use App\Console\Commands\SyncDataUserCommand;
use App\Console\Commands\UpdatePointFinal;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        SyncDataUserCommand::class,
        SyncDataDepartmentCommand::class,
        ResetCachePermissionCommand::class,
        RemindManagerAssignCommand::class,
        RemindEmpCompleteCommand::class,
        RemindAssessorCompleteCommand::class,
        RemindManagerApprovalCommand::class,
        NotifyListEmpCompletingForm::class,
        NotifyListAssessorCompletingForm::class,
        AssignRolePermission::class,
        NotifyCheckPointManager::class,
        UpdatePointFinal::class,
        AssignRoleEmployee::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @SuppressWarnings("unused")
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //
    }
}
