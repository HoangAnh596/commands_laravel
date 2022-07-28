@php
use App\Library\Common;
@endphp
<p>Dear {{ $manager->firstname . ' ' . $manager->lastname }},</p>
<br/>
<div>Hệ thống checkpoint VNEXT trân trọng gửi lại anh/chị danh sách các nhân viên đã được Cán bộ quản lý trực tiếp hoàn thành phiếu đánh giá.</div>
<p>Anh/chị vui lòng hoàn thành đánh giá và phê duyệt kết quả trước {{ config('app.time_deadline') }} của ngày <strong>{{ \Carbon\Carbon::parse($campaign->deadline_manager_approve)->format('d/m/Y') }}</strong></p>
<p>Link phiếu đánh giá: <a href="{{ Common::getAppUrl() }}" target="_blank">{{ Common::getAppUrl() }}</a></p>
<br/>
<div>Các trường hợp không tuân thủ so với deadline sẽ được coi là không hợp lệ.</div>
<br/>
<p>Trong quá trình đánh giá trên hệ thống, nếu anh/chị có gì chưa rõ, xin vui lòng liên hệ với bộ phận Nhân sự qua emai: <strong>{{ config('app.hr_email_support') }}</strong> 
hoặc <strong>Ms Hạnh - skype: hanhlth11</strong> để được hướng dẫn cụ thể.</p>
<br/>
<p>Trân trọng.</p>
