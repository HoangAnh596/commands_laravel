<?php

namespace App\Jobs;

use App\Mail\EmployeeImportEmail;
use Illuminate\Support\Facades\Mail;

class SendEmailImportEmployee extends Job
{
    public $managers;
    public $campaign;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($managers, $campaign)
    {
        $this->managers = $managers;
        $this->campaign = $campaign;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->managers as $manager) {
            Mail::to($manager->email)->send(new EmployeeImportEmail($manager, $this->campaign));
        }
    }
}
