<?php

namespace App\Mail;

use App\Models\Campaign;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RemindManagerApprovalEmail extends Mailable
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
        $subject = '[Checkpoint] Nhắc nhở về việc hoàn thành phê duyệt kết quả đánh giá 
                        nhân viên của Cán bộ quản lý bộ phận';
        return $this->from(config('app.mail_from'))
                    ->subject($subject)
                    ->view('emails.manager.remind_manager_approval');
    }
}
