<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmployeeImportEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $manager;
    public $campaign;

    public function __construct($manager, $campaign)
    {
        $this->manager = $manager;
        $this->campaign = $campaign;
    }

    public function build()
    {
        $subject = '[Checkpoint] Thông báo danh dách nhân viên đủ điều kiện tham gia đánh giá kỳ Checkpoint ' .
                        Carbon::parse($this->campaign->start_date)->format('m/Y');
        return $this->from(config('app.mail_from'))
                    ->subject($subject)
                    ->view('emails.manager.imported');
    }
}
