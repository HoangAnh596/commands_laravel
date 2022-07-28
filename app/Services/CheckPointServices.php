<?php

namespace App\Services;

interface CheckPointServices
{
    public function getListCheckpoint($params = []);

    public function getMyCheckpoint($userId);

    public function getEmployeeAttributes();

    public function getAssessorAttributes();

    public function getManagerAttributes();

    public function searchReport($param = []);

    public function total($parma = []);

    public function exportExcel($dataExport, $campaign);
}
