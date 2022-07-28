<?php

return [
    'welcome' => 'Chào mừng bạn đã tới VNEXT CHECKPOINT',
    'errors' => [
        'not_authorized' => 'Bạn không có quyền truy cập vào tài nguyên này',
        'not_found' => ':entry không tìm thấy',
        'server_error' => 'Rất tiếc, đã xảy ra lỗi. Vui lòng thử lại sau !',
        'validation' => [
            'required' => ':field_title là bắt buộc',
            'integer' => ':field_title phải là số nguyên',
            'array' => ':field_title phải là mảng các phần tử',
            'min' => 'Giá trị nhỏ nhất của :field_title là :min',
            'max' => 'Giá trị lớn nhất của :field_title là :max',
        ],
        'campaigns' => [
            'date' => ':field_name không hợp lệ',
            'date_after' => 'Vui lòng chọn :field_name sau ngày :date',
            'date_after_or_equal' => 'Vui lòng chọn :field_name từ ngày :date',
            'store' => [
                'title' => [
                    'required' => 'Tiêu đề của kỳ checkpoint là bắt buộc',
                    'max' => 'Tiêu đề của kỳ checkpoint có tối đa 500 kí tự',
                ],
                'start_date' => [
                    'required' => 'Ngày bắt đầu kỳ đánh giá là bắt buộc',
                ],
                'deadline_manager_assign' => [
                    'required' => 'Deadline cho việc assign người đánh giá cho nhân viên là bắt buộc',
                ],
                'deadline_emp_complete' => [
                    'required' => 'Deadline cho nhân viên hoàn thành phiếu đánh giá là bắt buộc',
                ],
                'deadline_assessor_complete' => [
                    'required' => 'Deadline cho QLTT review và hoàn thành đánh giá nhân viên là bắt buộc',
                ],
                'deadline_manager_approve' => [
                    'required' => 'Deadline cho QL cấp 1 phê duyệt kết quả form đánh giá của nhân viên là bắt buộc',
                ],
                'end_date' => [
                    'required' => 'Ngày kết thúc kỳ đánh giá là bắt buộc',
                ],
            ],
            'import_file' => [
                'campaign_id' => [
                    'required' => 'Không có kỳ đánh giá nào được chọn',
                ],
                'file_data' => [
                    'required' => 'Không có file nào được chọn',
                ],
                'file_type' => [
                    'required' => 'Vui lòng chọn loại file để import',
                    'in' => 'Vui lòng chọn đúng loại file để import'
                ],
                'file_name' => [
                    'required' => 'Tên file là bắt buộc',
                ]
            ],
        ],
        'checkpoints' => [
            'manager_assign' => [
                'assessor_id' => [
                    'required' => 'Vui lòng thêm Quản lý trực tiếp',
                    'integer' => 'ID của Quản lý trực tiếp phải là số nguyên',
                ],
                'checkpoint_id' => [
                    'required' => 'Vui lòng thêm danh sách nhân viên để assign cho Quản lý trực tiếp',
                    'array' => 'Nhân viên assign cho Quản lý trực tiếp phải là mảng các phần tử',
                ]
            ]
        ],
    ],
];
