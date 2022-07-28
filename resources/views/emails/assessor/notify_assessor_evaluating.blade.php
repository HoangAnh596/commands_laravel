@php
use App\Library\Common;
@endphp
<p>Dear anh/chị {{ $assessor->firstname . ' ' . $assessor->lastname }},</p>
<br/>
<div>Hệ thống checkpoint VNEXT trân trọng gửi lại anh/chị danh sách các nhân viên cần phải hoàn thành đánh giá trước {{ config('app.time_deadline') }} của ngày <strong>{{ \Carbon\Carbon::parse($campaign->deadline_assessor_complete)->format('d/m/Y') }}</strong>.</div>
<p>Anh/chị vui lòng theo dõi và hoàn thành đánh giá theo đúng deadline đã đề ra theo link danh sách dưới đây</p>
<p>Link danh sách nhân viên: <a href="{{ Common::getAppUrl() }}" target="_blank">{{ Common::getAppUrl() }}</a></p>
<div>Các trường hợp không tuân thủ so với deadline sẽ được coi là không hợp lệ.</div>
<br/>
<p>Trong quá trình đánh giá trên hệ thống, nếu anh/chị có gì chưa rõ, xin vui lòng liên hệ với bộ phận Nhân sự qua emai: <strong>{{ config('app.hr_email_support') }}</strong>
hoặc <strong>Ms Hạnh - skype: hanhlth11</strong> để được hướng dẫn cụ thể.</p>
<br/>
<p>Trân trọng.</p>
