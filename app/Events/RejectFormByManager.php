<?php

namespace App\Events;

use App\Models\CheckPoint;

class RejectFormByManager extends Event
{

    public $checkPoint;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(CheckPoint $checkPoint)
    {
        $this->checkPoint = $checkPoint;
    }
}
