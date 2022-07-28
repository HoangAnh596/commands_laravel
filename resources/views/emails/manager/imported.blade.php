@php
use App\Library\Common;
@endphp
<p>Dear anh/chị quản lý bộ phận,</p>
<br/>
<div>Hệ thống checkpoint VNEXT trân trọng gửi lại anh/chị kế hoạch và danh sách các nhân viên đủ điều kiện tham gia kỳ Đánh giá checkpoint 
    {{ \Carbon\Carbon::parse($campaign->start_date)->format('m/Y') }}:</div>
<br/>
<div>- Thời gian checkpoint: <strong>{{ \Carbon\Carbon::parse($campaign->start_date)->format('d/m/Y') }}</strong> đến <strong>{{ \Carbon\Carbon::parse($campaign->end_date)->format('d/m/Y') }}</strong></div>
<div>- Các mốc thời gian deadline:</div>
<ul style="padding-left: 10px; padding-top: 0px; padding-bottom: 0px; margin: 0px !important">
    <li>Deadline cho việc chỉ định Quản lý trực tiếp đánh giá nhân viên: <strong>{{ \Carbon\Carbon::parse($campaign->deadline_manager_assign)->format('d/m/Y') }}</strong></li>
    <li>Deadline cho việc nhân viên hoàn thành phiếu đánh giá: <strong>{{ \Carbon\Carbon::parse($campaign->deadline_emp_complete)->format('d/m/Y') }}</strong></li>
    <li>Deadline cho việc Quản lý trực tiếp đánh giá nhân viên: <strong>{{ \Carbon\Carbon::parse($campaign->deadline_assessor_complete)->format('d/m/Y') }}</strong></li>
    <li>Deadline cho việc Quản lý cấp 1 phê duyệt kết quả đánh giá nhân viên: <strong>{{ \Carbon\Carbon::parse($campaign->deadline_manager_approve)->format('d/m/Y') }}</strong></li>
</ul>
<div>- Đối tượng đủ điều kiện tham gia: VNEXTers kí hợp đồng chính thức từ đủ 06 tháng trở lên, tính đến hết {{ Common::dateCheckpointRequire() }}</div>
<p>Anh/chị vui lòng xác nhận và thực hiện chỉ định Quản lý trực tiếp đánh giá nhân viên theo đúng deadline đã đề ra của bộ phận Nhân sự theo link dưới đây.</p>
<p>Link danh sách nhân viên của bộ phận tại <a href="{{ Common::getAppUrl() }}" target="_blank">đây</a></p>
<br/>
<div>Các trường hợp không tuân thủ so với deadline sẽ được coi là không hợp lệ.</div>
<p>Trong quá trình đánh giá trên hệ thống, nếu anh/chị có gì chưa rõ, xin vui lòng liên hệ với bộ phận Nhân sự qua emai: <strong>{{ config('app.hr_email_support') }}</strong> 
hoặc <strong>Ms Hạnh - skype: hanhlth11</strong> để được hướng dẫn cụ thể.</p>
<br/>
<p>Trân trọng.</p>
