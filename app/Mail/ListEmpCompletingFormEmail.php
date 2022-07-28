<?php

namespace App\Mail;

use App\Models\Campaign;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ListEmpCompletingFormEmail extends Mailable
{
    use SerializesModels;

    public $assessor;
    public $campaign;

    public function __construct($assessor, Campaign $campaign)
    {
        $this->assessor = $assessor;
        $this->campaign = $campaign;
    }

    public function build()
    {
        return $this->from(config('app.mail_from'))
                    ->subject('[Checkpoint] Thông báo danh sách nhân viên đã hoàn thành phiếu đánh giá')
                    ->view('emails.assessor.list_emp_completing_form');
    }
}
