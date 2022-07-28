<?php

namespace App\Mail;

use App\Models\Campaign;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RemindEmpCompleteEmail extends Mailable
{
    use SerializesModels;

    public $employee;
    public $campaign;
    public $remainingDay;

    public function __construct($employee, Campaign $campaign, $remainingDay)
    {
        $this->employee = $employee;
        $this->campaign = $campaign;
        $this->remainingDay = $remainingDay;
    }

    public function build()
    {
        return $this->from(config('app.mail_from'))
                    ->subject('[Checkpoint] Nhắc nhở về việc hoàn thành phiếu đánh giá của nhân viên')
                    ->view('emails.employee.remind_completing_form');
    }
}
