<?php

namespace App\Mail;

use App\Models\CheckPoint;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ManagerRejectEmail extends Mailable
{
    use SerializesModels;

    public $checkPoint;

    public function __construct(CheckPoint $checkPoint)
    {
        $this->checkPoint = $checkPoint;
    }

    public function build()
    {
        $subject = '[Checkpoint] Thông báo về việc phiếu đánh giá của Quản lý trực tiếp đánh giá 
                        không được phê duyệt';
        return $this->from(config('app.mail_from'))
                    ->subject($subject)
                    ->view('emails.manager.rejected_assessor_form');
    }
}
