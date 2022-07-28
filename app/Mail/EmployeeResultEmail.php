<?php

namespace App\Mail;

use Carbon\Carbon;
use App\Models\CheckPoint;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmployeeResultEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $checkPoint;

    public function __construct(CheckPoint $checkPoint)
    {
        $this->checkPoint = $checkPoint;
    }

    public function build()
    {
        $subject = '[Checkpoint] Thông báo kết quả đánh giá của nhân viên trong kỳ Checkpoint ' .
                        Carbon::parse($this->checkPoint->campaign->start_date)->format('m/Y');
        return $this->from(config('app.mail_from'))
                    ->subject($subject)
                    ->view('emails.employee.result');
    }
}
