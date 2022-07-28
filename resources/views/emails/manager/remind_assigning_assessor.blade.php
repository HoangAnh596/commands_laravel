@php
use App\Library\Common;
@endphp
<p>Dear anh/chị {{ $manager->firstname . ' ' . $manager->lastname }},</p>
<br/>
<div>Hệ thống checkpoint VNEXT trân trọng thông báo:</div>
<br/>
<div>Theo kế hoạch Checkpoint {{ \Carbon\Carbon::parse($campaign->start_date)->format('m/Y') }} từ bộ phận Nhân sự, chỉ còn <strong>{{ $remainingDay }} ngày </strong>để Cán bộ quản lý bộ phận chỉ định Cán bộ quản lý trực tiếp đánh giá nhân viên.</div>
<p>Anh/chị vui lòng hoàn thành việc chỉ định này theo đúng deadline trước {{ config('app.time_deadline') }} của ngày <strong>{{ \Carbon\Carbon::parse($campaign->deadline_manager_assign)->format('d/m/Y') }}</strong></p>
<p>Link chỉ định người đánh giá: <a href="{{ Common::getAppUrl() }}" target="_blank" rel="noopener noreferrer">{{ Common::getAppUrl() }}</a></p>
<br/>
<div>Các trường hợp không tuân thủ so với deadline sẽ được coi là không hợp lệ.</div>
<p>Trong quá trình đánh giá trên hệ thống, nếu anh/chị có gì chưa rõ, xin vui lòng liên hệ với bộ phận Nhân sự qua emai: <strong>{{ config('app.hr_email_support') }}</strong> 
hoặc <strong>Ms Hạnh - skype: hanhlth11</strong> để được hướng dẫn cụ thể.</p>
<br/>
<p>Trân trọng.</p>
