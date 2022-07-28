@php
use App\Library\Common;
$deadlineEmpComplete = $checkPoint->extra_emp_complete ? $checkPoint->extra_emp_complete : $checkPoint->campaign->deadline_emp_complete;
@endphp
<p>Dear anh/chị {{ $checkPoint->employee->firstname . ' ' .  $checkPoint->employee->lastname }},</p>
<br/>
<div>Hệ thống checkpoint VNEXT trân trọng thông báo Phiếu đánh giá của bạn không được Cán bộ quản lý trực tiếp phê duyệt với lý do dưới đây:</div>
<br/>
<div><i>{!! $checkPoint->assessor_comments !!}</i></div>
<p>Vui lòng hoàn thành lại phiếu đánh giá của mình trước {{ config('app.time_deadline') }} của ngày <strong>{{ \Carbon\Carbon::parse($deadlineEmpComplete)->format('d/m/Y') }}</strong></p>
<p>Link phiếu đánh giá: <a href="{{ Common::getAppUrl() . 'form-checkpoint/' . $checkPoint->id }}">{{ Common::getAppUrl() . 'form-checkpoint/' . $checkPoint->id }}</a></p>
<br/>
<div>Các trường hợp không tuân thủ so với deadline sẽ được coi là không hợp lệ.</div>
<br/>
<p>Trong quá trình đánh giá trên hệ thống, nếu anh/chị có gì chưa rõ, xin vui lòng liên hệ với bộ phận Nhân sự qua emai: <strong>{{ config('app.hr_email_support') }}</strong>
hoặc <strong>Ms Hạnh - skype: hanhlth11</strong> để được hướng dẫn cụ thể.</p>
<br/>
<p>Trân trọng.</p>
