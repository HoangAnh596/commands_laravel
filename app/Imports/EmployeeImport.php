<?php

namespace App\Imports;

use DB;
use Exception;
use App\Library\Common;
use App\Services\CheckPointServices;
use App\Services\UserServices;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeeImport implements ToCollection, WithHeadingRow
{
    private $campaignId;
    private $importError;
    private $messageError;
    private $managers;

    public function __construct($campaignId)
    {
        $this->campaignId = $campaignId;
        $this->importError = false;
        $this->managers = [];
        $this->messageError = '';
    }

    public function headingRow(): int
    {
        return 4;
    }

    public function collection(Collection $rows)
    {
        $userService = app(UserServices::class);
        $checkPointServices = app(CheckPointServices::class);
        $rows = $rows->reject(function ($item) {
            return !$item['ma_nhan_vien'];
        });

        DB::beginTransaction();
        try {
            foreach ($rows as $row) {
                if ($row->filter()->isNotEmpty()) {
                    $user = $userService->findOneByColumn('employee_code', $row['ma_nhan_vien']);
                    if (!$user) {
                        throw new Exception('Không tìm thấy nhân viên có mã ' .
                                                $row['ma_nhan_vien'] . ' trong hệ thống');
                    }

                    $department = $user->department;
                    if (!$department) {
                        throw new Exception('Nhân viên ' . $row['ma_nhan_vien'] . ' không có phòng ban');
                    }

                    $manager = $user->department->manageBy()->first() ?? null;
                    if (!$manager) {
                        throw new Exception('Nhân viên ' . $row['ma_nhan_vien'] . ' không có quản lý cấp 1');
                    }

                    $checkPointServices->firstOrCreate([
                        'campaign_id' => $this->campaignId,
                        'emp_id' => $user->id,
                        'manager_id' => $manager->id,
                    ]);

                    if (config('app.mode') == Common::MODE_STG) {
                        $manager->email = config('app.mail_test');
                    }

                    if (!in_array($manager, $this->managers)) {
                        $this->managers[] = $manager;
                    }
                }
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            $this->managers = [];
            $this->importError = true;
            $this->messageError = $e->getMessage();
        }
    }

    public function importError()
    {
        return $this->importError;
    }

    public function getMessageError()
    {
        return $this->messageError;
    }

    public function getListManager()
    {
        return $this->managers;
    }
}
