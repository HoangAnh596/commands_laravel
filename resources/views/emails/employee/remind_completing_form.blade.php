@php
use App\Library\Common;
@endphp
<p>Dear anh/chị {{ $employee->firstname . ' ' . $employee->lastname }},</p>
<br/>
<div>Hệ thống checkpoint VNEXT trân trọng thông báo:</div>
<br/>
<div>Theo kế hoạch Checkpoint {{ \Carbon\Carbon::parse($campaign->start_date)->format('m/Y') }}
    từ bộ phận Nhân sự, chỉ còn <strong>{{ $remainingDay }} ngày</strong> để nhân viên hoàn thành Phiếu đánh giá của mình.</div>
<p>Anh/chị vui lòng hoàn thành phiếu đánh giá theo đúng deadline trước {{ config('app.time_deadline') }} của ngày <strong>{{ \Carbon\Carbon::parse($campaign->deadline_emp_complete)->format('d/m/Y') }}</strong></p>
<p>Link phiếu đánh giá: <a href="{{ Common::getAppUrl() . 'form-checkpoint/' . $employee->checkpoint_id }}">{{ Common::getAppUrl() . 'form-checkpoint/' . $employee->checkpoint_id }}</a></p>
<div>Các trường hợp không tuân thủ so với deadline sẽ được coi là không hợp lệ.</div>
<br/>
<p>Trong quá trình đánh giá trên hệ thống, nếu anh/chị có gì chưa rõ, xin vui lòng liên hệ với bộ phận Nhân sự qua emai: <strong>{{ config('app.hr_email_support') }}</strong>
hoặc <strong>Ms Hạnh - skype: hanhlth11</strong> để được hướng dẫn cụ thể.</p>
<br/>
<p>Trân trọng.</p>
