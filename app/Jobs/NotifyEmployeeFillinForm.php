<?php

namespace App\Jobs;

use App\Models\Campaign;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmployeeFillinFormEmail;

class NotifyEmployeeFillinForm extends Job
{
    public $employees;
    public $campaign;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($employees, Campaign $campaign)
    {
        $this->employees = $employees;
        $this->campaign = $campaign;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->employees as $employee) {
            Mail::to($employee->email)->send(new EmployeeFillinFormEmail($employee, $this->campaign));
        }
    }
}
