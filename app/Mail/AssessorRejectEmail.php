<?php

namespace App\Mail;

use App\Models\CheckPoint;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AssessorRejectEmail extends Mailable
{
    use SerializesModels;

    public $checkPoint;

    public function __construct(CheckPoint $checkPoint)
    {
        $this->checkPoint = $checkPoint;
    }

    public function build()
    {
        $subject = '[Checkpoint] Thông báo về việc phiếu đánh giá của nhân viên không được phê duyệt';
        return $this->from(config('app.mail_from'))
                    ->subject($subject)
                    ->view('emails.assessor.rejected_emp_form');
    }
}
