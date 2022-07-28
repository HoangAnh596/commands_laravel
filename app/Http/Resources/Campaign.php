<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Campaign extends JsonResource
{
    /**
     *
     * @SuppressWarnings("unused")
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'deadline_manager_assign' => $this->deadline_manager_assign,
            'deadline_manager_approve' => $this->deadline_manager_approve,
            'deadline_assessor_complete' => $this->deadline_assessor_complete,
            'deadline_emp_complete' => $this->deadline_emp_complete,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'modified_by' => $this->modified_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
