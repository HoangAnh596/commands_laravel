<?php

namespace App\Mail;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Campaign;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmployeeFillinFormEmail extends Mailable
{
    use SerializesModels;

    public $campaign;
    public $employee;

    public function __construct(User $employee, Campaign $campaign)
    {
        $this->employee = $employee;
        $this->campaign = $campaign;
    }

    public function build()
    {
        $subject = '[Checkpoint] Thông báo kế hoạch và thời gian kỳ Checkpoint ' .
                        Carbon::parse($this->campaign->start_date)->format('m/Y');
        return $this->from(config('app.mail_from'))
                    ->subject($subject)
                    ->view('emails.employee.notify_fill_in_form');
    }
}
