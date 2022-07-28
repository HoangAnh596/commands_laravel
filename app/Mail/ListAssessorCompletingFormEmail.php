<?php

namespace App\Mail;

use App\Models\Campaign;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ListAssessorCompletingFormEmail extends Mailable
{
    use SerializesModels;

    public $manager;
    public $campaign;

    public function __construct($manager, Campaign $campaign)
    {
        $this->manager = $manager;
        $this->campaign = $campaign;
    }

    public function build()
    {
        $subject = '[Checkpoint] Thông báo danh sách nhân viên đã được Cán bộ quản lý trực tiếp 
                        hoàn thành đánh giá';
        return $this->from(config('app.mail_from'))
                    ->subject($subject)
                    ->view('emails.manager.list_assessor_completing_form');
    }
}
