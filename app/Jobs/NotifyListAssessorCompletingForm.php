<?php

namespace App\Jobs;

use App\Models\Campaign;
use Illuminate\Support\Facades\Mail;
use App\Mail\ListAssessorCompletingFormEmail;

class NotifyListAssessorCompletingForm extends Job
{
    public $managers;
    public $campaign;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($managers, Campaign $campaign)
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
            Mail::to($manager->email)->send(new ListAssessorCompletingFormEmail($manager, $this->campaign));
        }
    }
}
