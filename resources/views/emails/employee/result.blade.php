@php
use App\Library\Common;
@endphp
<p>Dear anh/chị {{ $checkPoint->employee->firstname . ' ' . $checkPoint->employee->lastname }},</p>
<br/>
<div>Hệ thống checkpoint VNEXT trân trọng gửi lại kết quả đánh giá của bạn trong kỳ Checkpoint
    {{ \Carbon\Carbon::parse($checkPoint->campaign->start_date)->format('m/Y') }}:</div>
<br/>
<div>-   Họ và tên: <strong>{{ $checkPoint->employee->firstname . ' ' . $checkPoint->employee->lastname }}</strong></div>
<div>-   Bộ phận: <strong>{{ $checkPoint->employee->department->name }}</strong></div>
<div>-   Job rank: <strong>{{ $checkPoint->employee->job_rank }}</strong></div>
<div>-   Thời gian chính thức: <strong>{{ Common::formatTimeWorking($checkPoint->employee->join_date) }}</strong></div>
<div>-   Người đánh giá: <strong>{{ $checkPoint->assessor->firstname . ' ' . $checkPoint->assessor->lastname }}</strong></div>
<div>-   Điểm: <strong>{{ $checkPoint->emp_total_final }}</strong></div>
<div>-   Xếp loại: <strong>{{ Common::getLabelResultCheckpoint($checkPoint->emp_total_final) }}</strong></div>
<br/>
<div>Vui lòng truy cập vào đường link dưới đây để xem lại kết quả đánh giá trong kỳ Checkpoint {{ \Carbon\Carbon::parse($checkPoint->campaign->start_date)->format('m/Y') }}.</div>
<div>Link phiếu đánh giá <a href="{{ Common::getAppUrl() . 'form-checkpoint/' . $checkPoint->id }}">{{ Common::getAppUrl() . 'form-checkpoint/' . $checkPoint->id }}</a></div>
<br/>
<div>Cảm ơn bạn đã nỗ lực và cống hiến công sức trong suốt 06 tháng vừa qua.</div>
<div>Chúc bạn sức khỏe, hoàn thành tốt công việc và đạt được những mục tiêu đã đề ra.</div>
<br/>
<p>Trân trọng.</p>
