@php
use App\Library\Common;
@endphp
<p>Dear anh/chị {{ $employee->firstname . ' ' . $employee->lastname }},</p>
<br/>
<div>Hệ thống checkpoint VNEXT trân trọng thông báo: Tính đến ngày {{ Common::dateCheckpointRequire() }} anh/chị đã đủ điều kiện tham gia kỳ checkpoint tháng {{ \Carbon\Carbon::parse($campaign->start_date)->format('m/Y') }}.</div>
<div>Anh/chị vui lòng thực hiện đánh giá tại hệ thống: <a href="{{ Common::getAppUrl() }}">{{ Common::getAppUrl() }}</a><strong> trước 17h30 ngày {{ \Carbon\Carbon::parse($campaign->deadline_emp_complete)->format('d/m/Y') }}</strong>.</div>
<br/>
<div>Vui lòng truy cập tài liệu <strong>Hướng dẫn sử dụng hệ thống checkpoint</strong> online tại đây:</div>
<div><a href="{{ config('app.url_document') }}">{{ config('app.url_document') }}</a></div>
<br/>
<div>Các trường hợp không tuân thủ so với deadline sẽ được coi là không hợp lệ.</div>
<br/>
<p>Trong quá trình đánh giá trên hệ thống, nếu anh/chị có gì chưa rõ, xin vui lòng liên hệ với bộ phận Nhân sự qua emai: <strong>{{ config('app.hr_email_support') }}</strong>
hoặc <strong>Ms Hạnh - skype: hanhlth11</strong> để được hướng dẫn cụ thể.</p>
<br/>
<p>Trân trọng.</p>
