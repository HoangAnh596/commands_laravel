<?php

namespace App\Http\Controllers\Campaign;

use App\Http\Controllers\Common\Controller;
use App\Http\Requests\Campaign\EditRequest;
use App\Http\Requests\Campaign\ImportRequest;
use App\Http\Requests\Campaign\StoreRequest;
use App\Imports\EmployeeImport;
use App\Services\CampaignServices;
use App\Services\CheckPointServices;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Resources\Campaign as CampaignResource;
use App\Jobs\SendEmailImportEmployee;
use App\Library\Common;
use App\Models\Campaign;

class CampaignController extends Controller
{
    protected $campaignServices;

    protected $checkpointServices;

    public function __construct(
        CampaignServices $campaignServices,
        CheckPointServices $checkPointServices
    ) {
        $this->campaignServices = $campaignServices;
        $this->checkpointServices = $checkPointServices;
    }

    public function getCurrentCampaign()
    {
        $currentCp = $this->campaignServices->getCurrentCampaign();
        $checkExists = $this->checkpointServices->allQuery(['campaign_id' => $currentCp->id ?? null])->exists();
        return response()->json([
            'data' => [
                'is_imported' => $checkExists,
                'campaign' => $currentCp
            ]
        ]);
    }

    public function all()
    {
        // $this->authorize('list', Campaign::class);
        $campaigns = $this->campaignServices->getListCampaigns();
        return CampaignResource::collection($campaigns);
    }

    public function show($id)
    {
        $this->authorize('view', Campaign::class);
        $campaign = $this->campaignServices->find($id);
        return new CampaignResource($campaign);
    }

    public function store(StoreRequest $request)
    {
        $this->authorize('create', Campaign::class);
        $params = $this->campaignServices->getFillableFields();
        $params = $request->getParams($params);
        $campaign = $this->campaignServices->create($params);
        return new CampaignResource($campaign);
    }

    public function update(EditRequest $request, $id)
    {
        $this->authorize('update', Campaign::class);
        $params = $this->campaignServices->getFillableFields();
        $params = $request->getParams($params);
        $campaign = $this->campaignServices->update($id, $params);
        return new CampaignResource($campaign);
    }

    public function delete($id)
    {
        $this->authorize('delete', Campaign::class);
        $result = $this->campaignServices->delete($id);
        return response()->json([
            'data' => [
                'id' => $id,
                'message' => $result
            ],
        ]);
    }

    public function import(ImportRequest $request)
    {
        $this->authorize('import', Campaign::class);
        $params = $request->getParams(['file_data', 'file_type', 'file_name', 'campaign_id']);
        $campaign = $this->campaignServices->findOrFail($params['campaign_id']);
        $params['file_data'] = explode(',', $params['file_data']);
        $data = base64_decode($params['file_data'][1]);
        $fileName = $params['file_name'] . '.' . $params['file_type'];
        file_put_contents(storage_path('/app/imports/') . $fileName, $data);
        $fileImport = storage_path('/app/imports/') . $fileName;
        $employeeImport = new EmployeeImport($params['campaign_id']);
        Excel::import($employeeImport, $fileImport, null, config('app.import_file_type')[$params['file_type']]);
        unlink(storage_path('/app/imports/') . $fileName);
        $importError = $employeeImport->importError();
        if ($importError) {
            return response()->json([
                'success' => false,
                'message' => $employeeImport->getMessageError(),
            ], 503);
        }

        // Send email to managers
        $managers = $employeeImport->getListManager();
        dispatch((new SendEmailImportEmployee($managers, $campaign))
            ->delay(\Carbon\Carbon::now()->addSeconds(30)));

        return response()->json([
            'data' => [
                'success' => true,
                'message' => 'Import thành công danh sách nhân viên cho kì checkpoint',
            ]
        ]);
    }
}
