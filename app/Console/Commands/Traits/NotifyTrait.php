<?php

namespace App\Console\Commands\Traits;

use Carbon\Carbon;
use App\Library\Common;
use App\Models\CheckPoint;
use App\Jobs\NotifyListEmpCompletingForm;
use App\Jobs\NotifyListAssessorCompletingForm;

trait NotifyTrait
{
    /**
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(CyclomaticComplexity)
     */
    public function notify($type)
    {
        $currentCampaign = $this->campaignServices->getCurrentCampaign();
        if (!$currentCampaign || $currentCampaign->status == 0
                || $currentCampaign->isOutDated()) {
            echo "No campaign is running";
            return;
        }

        if ($type == Common::LIST_EMP_COMPLETING_FORM) {
            $relationship = 'assessor';
            $conditions = [
                'campaign_id' => $currentCampaign->id,
                'status' => CheckPoint::STATUS_REVIEWING
            ];
        } elseif ($type == Common::LIST_ASSESSOR_COMPLETING_FORM) {
            $relationship = 'manager';
            $conditions = [
                'campaign_id' => $currentCampaign->id,
                'status' => CheckPoint::STATUS_APPROVING
            ];
        }

        $checkPoints = $this->checkPointServices->allQuery($conditions)->get();

        if ($checkPoints->count() > 0) {
            $users = [];
            $checkPoints->load([$relationship => function ($query) {
                $query->select(['id', 'email', 'firstname', 'lastname']);
            }]);

            foreach ($checkPoints as $cp) {
                $user = $cp->$relationship;
                if (config('app.mode') == Common::MODE_STG) {
                    $user->email = config('app.mail_test');
                }

                if (!in_array($user, $users)) {
                    $users[] = $user;
                }
            }

            switch ($type) {
                case Common::LIST_EMP_COMPLETING_FORM:
                    dispatch((new NotifyListEmpCompletingForm($users, $currentCampaign))
                                ->delay(Carbon::now()->addSeconds(10)));
                    echo('Send list employee completing form to assessor');
                    break;
                case Common::LIST_ASSESSOR_COMPLETING_FORM:
                    dispatch((new NotifyListAssessorCompletingForm($users, $currentCampaign))
                                ->delay(Carbon::now()->addSeconds(10)));
                    echo('Send list assessor completing form to manager');
                    break;
                default:
                    break;
            }
        }
    }
}
