<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Campaign;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RemindingManagerAssignEmail extends Mailable
{
    use SerializesModels;

    public $manager;
    public $campaign;
    public $remainingDay;

    public function __construct($manager, Campaign $campaign, $remainingDay)
    {
        $this->manager = $manager;
        $this->campaign = $campaign;
        $this->remainingDay = $remainingDay;
    }

    public function build()
    {
        $subject = '[Checkpoint] Nhắc nhở về việc hoàn thành chỉ định Quản lý trực tiếp đánh giá cho các nhân viên';
        return $this->from(config('app.mail_from'))
                    ->subject($subject)
                    ->view('emails.manager.remind_assigning_assessor');
    }
}
