<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Services\UserServices;
use App\Http\Controllers\Common\Controller;
use App\Services\DepartmentManagerServices;
use App\Http\Resources\User as UserResources;
use App\Http\Resources\Checkpoint as CheckpointResource;

class UserController extends Controller
{
    public $userServices;
    public $departmentManagerServices;

    public function __construct(
        UserServices $userServices,
        DepartmentManagerServices $departmentManagerServices
    ) {
        $this->userServices = $userServices;
        $this->departmentManagerServices = $departmentManagerServices;
    }

    public function index()
    {
        $user = auth()->user();
        $isDD = $user->hasRole('DD');
        $deptManagerIds = [];
        if ($isDD) {
            $deptManager = $this->departmentManagerServices
                ->allQuery(['user_id' => $user->id])
                ->pluck('department_id')
                ->toArray();
            $deptManagerIds = $deptManager;
        }
        array_push($deptManagerIds, 3);
        $data = $this->userServices
            ->allQuery()
            ->whereIn('department_id', $deptManagerIds)
            ->orWhere('id', auth()->user()->id)->get();
        return UserResources::collection($data);
    }

    public function getInfo()
    {
        $user = auth()->user();
        $data = $this->userServices->getInfo($user->id);
        return new UserResources($data);
    }

    /**
     * @SuppressWarnings("unused")
     */
    public function getListCheckpoint(Request $request, $id)
    {
        $checkpoints = $this->userServices->getListCheckpoint($id);
        return CheckpointResource::collection($checkpoints);
    }
}
