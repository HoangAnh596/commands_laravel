<?php

namespace App\Rules\Checkpoint;

use Illuminate\Contracts\Validation\ImplicitRule;

class UniqueRule implements ImplicitRule
{
    protected $campaignId;
    protected $managerId;
    protected $employeeId;

    public function __construct($campaignId, $managerId, $employeeId)
    {
        $this->campaignId = $campaignId;
        $this->managerId = $managerId;
        $this->employeeId = $employeeId;
    }

    /**
     *
     * @SuppressWarnings("unused")
     * @return boolean
     */
    public function passes($attribute, $value)
    {
        return true;
    }

    public function message()
    {
        return 'Group campagin_id, manager_id, employee_id is unique';
    }
}
