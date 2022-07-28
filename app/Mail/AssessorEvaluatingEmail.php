<?php

namespace App\Mail;

use App\Models\Campaign;
use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AssessorEvaluatingEmail extends Mailable
{
    use SerializesModels;

    public $assessor;
    public $campaign;

    public function __construct(User $assessor, Campaign $campaign)
    {
        $this->assessor = $assessor;
        $this->campaign = $campaign;
    }

    public function build()
    {
        $subject = '[Checkpoint] Thông báo danh sách nhân viên cần đánh giá';
        return $this->from(config('app.mail_from'))
                    ->subject($subject)
                    ->view('emails.assessor.notify_assessor_evaluating');
    }
}
