<?php

namespace App\Jobs;

use App\Models\Campaign;
use Illuminate\Support\Facades\Mail;
use App\Mail\ListEmpCompletingFormEmail;

class NotifyListEmpCompletingForm extends Job
{
    public $assessors;
    public $campaign;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($assessors, Campaign $campaign)
    {
        $this->assessors = $assessors;
        $this->campaign = $campaign;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->assessors as $assessor) {
            Mail::to($assessor->email)->send(new ListEmpCompletingFormEmail($assessor, $this->campaign));
        }
    }
}
