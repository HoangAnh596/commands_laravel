<?php

namespace App\Console\Commands;

use App\Services\DepartmentServices;
use Illuminate\Console\Command;

class SyncDataDepartmentCommand extends Command
{
    protected $departmentServices;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync-data/department';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync data department from system hrm';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(DepartmentServices $departmentServices)
    {
        $this->departmentServices = $departmentServices;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->departmentServices->syncAllData();
        echo('Sync data department successfully');
    }
}
