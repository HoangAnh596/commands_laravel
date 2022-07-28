<?php

namespace App\Mail;

use App\Models\Campaign;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RemindAssessorCompleteEmail extends Mailable
{
    use SerializesModels;

    public $assessor;
    public $campaign;
    public $remainingDay;

    public function __construct($assessor, Campaign $campaign, $remainingDay)
    {
        $this->assessor = $assessor;
        $this->campaign = $campaign;
        $this->remainingDay = $remainingDay;
    }

    public function build()
    {
        return $this->from(config('app.mail_from'))
                    ->subject('[Checkpoint] Nhắc nhở về việc hoàn thành phiếu đánh giá nhân viên của Quản lý trực tiếp')
                    ->view('emails.assessor.remind_completing_form');
    }
}
