<?php

namespace App\Console\Commands\Traits;

use Carbon\Carbon;
use App\Library\Common;
use App\Models\CheckPoint;
use App\Jobs\RemindEmpComplete;
use App\Jobs\RemindManagerAssign;
use App\Jobs\RemindManagerApproval;
use App\Jobs\RemindAssessorComplete;

trait ReminderTrait
{
    /**
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(CyclomaticComplexity)
     */
    public function remind($type)
    {
        $currentCampaign = $this->campaignServices->getCurrentCampaign();
        if (!$currentCampaign || $currentCampaign->status == 0
                || $currentCampaign->isOutDated()) {
            echo "No campaign is running";
            return;
        }

        if ($type == Common::REMIND_EMP_COMPLETE) {
            $deadline = 'deadline_emp_complete';
            $relationship = 'employee';
            $conditions = [
                'campaign_id' => $currentCampaign->id,
                'status' => CheckPoint::STATUS_INPROGRESS
            ];
        } elseif ($type == Common::REMIND_ASSESSOR_COMPLETE) {
            $deadline = 'deadline_assessor_complete';
            $relationship = 'assessor';
            $conditions = [
                'campaign_id' => $currentCampaign->id,
                'status' => CheckPoint::STATUS_REVIEWING
            ];
        } elseif ($type == Common::REMIND_MANAGER_APPROVE) {
            $deadline = 'deadline_manager_approve';
            $relationship = 'manager';
            $conditions = [
                'campaign_id' => $currentCampaign->id,
                'status' => CheckPoint::STATUS_APPROVING
            ];
        } elseif ($type == Common::REMIND_MANAGER_ASSIGN) {
            $deadline = 'deadline_manager_assign';
            $relationship = 'manager';
            $conditions = [
                'campaign_id' => $currentCampaign->id,
            ];
        }

        $now = Carbon::now()->format('Y-m-d');
        $deadlineDate = Carbon::parse($currentCampaign->$deadline)->format('Y-m-d');
        $remainingDay = Carbon::parse($currentCampaign->$deadline)->diffInDays(Carbon::parse($now));

        if (($remainingDay == 3 || $remainingDay == 1) && $deadlineDate > $now) {
            $users = [];
            $query = $this->checkPointServices->allQuery($conditions);
            if ($type == Common::REMIND_MANAGER_ASSIGN) {
                $query = $query->whereNull('assessor_id');
            }
            $checkPoints = $query->get();

            if ($checkPoints->count() > 0) {
                $checkPoints->load([$relationship => function ($query) {
                    $query->select(['id', 'email', 'firstname', 'lastname']);
                }]);

                foreach ($checkPoints as $cp) {
                    $user = $cp->$relationship;
                    if (config('app.mode') == Common::MODE_STG) {
                        $user->email = config('app.mail_test');
                    }

                    if (!in_array($user, $users)) {
                        $user->checkpoint_id = $cp->id;
                        $users[] = $user;
                    }
                }

                switch ($type) {
                    case Common::REMIND_EMP_COMPLETE:
                        dispatch((new RemindEmpComplete($users, $currentCampaign, $remainingDay))
                                ->delay(Carbon::now()->addSeconds(10)));
                        echo('Sending email remind employee complete their checkpoint form');
                        break;
                    case Common::REMIND_ASSESSOR_COMPLETE:
                        dispatch((new RemindAssessorComplete($users, $currentCampaign, $remainingDay))
                                ->delay(Carbon::now()->addSeconds(10)));
                        echo('Sending email remind assessor complete their checkpoint form');
                        break;
                    case Common::REMIND_MANAGER_APPROVE:
                        dispatch((new RemindManagerApproval($users, $currentCampaign, $remainingDay))
                                ->delay(Carbon::now()->addSeconds(10)));
                        echo('Sending email remind manager approve the checkpoint form');
                        break;
                    case Common::REMIND_MANAGER_ASSIGN:
                        dispatch((new RemindManagerAssign($users, $currentCampaign, $remainingDay))
                                ->delay(Carbon::now()->addSeconds(10)));
                        echo('Sending email remind manager assign assessor to employee');
                        break;
                    default:
                        break;
                }
            }
        }
    }
}
