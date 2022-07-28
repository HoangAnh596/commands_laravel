<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    const STATUS_OPEN = 1;
    const STATUS_CLOSE = 0;

    protected $table = 'campaigns';
    protected $fillable = [
        'title',
        'status',
        'start_date',
        'end_date',
        'deadline_manager_assign',
        'deadline_manager_approve',
        'deadline_assessor_complete',
        'deadline_emp_complete',
        'status',
    ];

    protected $hidden = [];

    public function checkpoints()
    {
        return $this->hasMany(CheckPoint::class, 'campaign_id', 'id');
    }

    public function isOutDated()
    {
        $now = Carbon::now()->format('Y-m-d');
        $endDate = Carbon::parse($this->end_date)->format('Y-m-d');
        return $now > $endDate;
    }
}
