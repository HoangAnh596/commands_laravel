<?php

namespace App\Console\Commands;

use App\Models\CheckPoint;
use Illuminate\Console\Command;
use App\Services\CheckPointServices;

class UpdatePointFinal extends Command
{
    public $checkPointServices;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cp:update-point';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update point final';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(CheckPointServices $checkPointServices)
    {
        parent::__construct();
        $this->checkPointServices = $checkPointServices;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $cp = $this->checkPointServices->allQuery(['status' => CheckPoint::STATUS_DONE])->get();
        foreach ($cp as $checkpoint) {
            $employeePoint = $checkpoint->calculateEmpPointFinal();
            $checkpoint->emp_total_final = $employeePoint;
            $checkpoint->save();
        }
    }
}
