<?php

return [
    'welcome' => 'Welcome to CHE-VNEXT',
    'errors' => [
        'not_authorized' => 'You are not allowed to access this resource',
        'not_found' => 'Entry for :entry not found',
        'server_error' => 'Oops! An Error Occurred',
        'validation' => [
            'required' => ':field_title is required',
            'integer' => ':field_title must be integer',
            'array' => ':field_title must be array value',
            'min' => 'The minimum :field_title is :min',
            'max' => 'The maximum :field_title is :max',
        ],
        'campaigns' => [
            'date' => 'The :field_name is not a valid date',
            'date_after' => 'Please select :field_name greater than :date',
            'store' => [
                'title' => [
                    'required' => 'Title of campaign is required',
                    'max' => 'The maximum length of a title is 500 characters',
                ],
                'start_date' => [
                    'required' => 'Start date is required',
                ],
                'deadline_manager_assign' => [
                    'required' => 'Deadline for assigning an assessor to an employee is required',
                ],
                'deadline_emp_complete' => [
                    'required' => 'Deadline for completing the employee\'s checkpoint form is required',
                ],
                'deadline_assessor_complete' => [
                    'required' => 'Deadline for evaluating the employee\'s checkpoint form is required',
                ],
                'deadline_manager_approve' => [
                    'required' => 'Deadline for approving the employee\'s checkpoint form is required',
                ],
                'end_date' => [
                    'required' => 'End date is required',
                ],
            ],
            'import_file' => [
                'campaign_id' => [
                    'required' => 'No campaign is selected',
                ],
                'file_data' => [
                    'required' => 'No file data',
                ],
                'file_type' => [
                    'required' => 'File type wasn\'t selected',
                    'in' => 'You  must upload the right file type'
                ],
                'file_name' => [
                    'required' => 'Please enter file name',
                ]
            ],
        ],
        'checkpoints' => [
            'manager_assign' => [
                'assessor_id' => [
                    'required' => 'Please enter an assessor',
                    'integer' => 'ID of assessor must be integer',
                ],
                'checkpoint_id' => [
                    'required' => 'Please enter list employee to assign assessor',
                    'array' => 'Assigned employees must be a list',
                ]
            ]
        ],
    ],
];
