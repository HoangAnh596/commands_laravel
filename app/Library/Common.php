<?php
// @codingStandardsIgnoreFile

namespace App\Library;

use Carbon\Carbon;
use App\Models\CheckPoint;
use App\Services\CampaignServices;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * @SuppressWarnings(PHPMD)
 * **/
class Common
{
    const MODE_STG = 'stg';
    const MODE_PROD = 'prod';
    const REMIND_EMP_COMPLETE = 1;
    const REMIND_ASSESSOR_COMPLETE = 2;
    const REMIND_MANAGER_APPROVE = 3;
    const REMIND_MANAGER_ASSIGN = 4;
    const LIST_EMP_COMPLETING_FORM = 5;
    const LIST_ASSESSOR_COMPLETING_FORM = 6;

    public static function getLabelResultCheckpoint($point)
    {
        switch (true) {
            case $point >= 9.25:
                return 'Xuất sắc';
            case $point >= 8.5:
                return 'Tốt';
            case $point >= 7:
                return 'Khá';
            case $point >= 5.5:
                return 'Đạt';
            case $point < 5.5:
                return 'Chưa đạt';
            default:
                return '';
        }
    }

    public static function getLabelStatusCheckpoint($status)
    {
        switch ((int)$status) {
            case CheckPoint::STATUS_NEW:
                return 'Mới';
            case CheckPoint::STATUS_INPROGRESS:
                return 'Đang xử lý';
            case CheckPoint::STATUS_REVIEWING:
                return 'Đang chấm điểm';
            case CheckPoint::STATUS_APPROVING:
                return 'Đang duyệt';
            case CheckPoint::STATUS_DONE:
                return 'Đã hoàn thành';
            default:
                return '';
        }
    }

    public static function formatTimeWorking($dateJoin)
    {
        $currentTime = new \DateTime();
        $date = new \DateTime($dateJoin);
        $interval = date_diff($date, $currentTime);
        if (!empty($interval->format('%y'))) {
            return $interval->format('%y năm %m tháng');
        }
        return $interval->format('%m tháng');
    }
    /**
     * @param $data
     * @param $fileType
     * @param $fileName
     * @param array $mergeCell
     * @param boolean $strict
     * @throws BadRequestHttpException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @SuppressWarnings(PHPMD.ExitExpression)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public static function createFileExcel(
        $data,
        $fileType,
        $fileName,
        $mergeCell = [],
        $freeStyles = [],
        $formulas = [],
        $formatCodes = [],
        $autoSizes = [],
        $hyperLinks = [],
        $widthColumns = [],
        $heightColumns = []
    ) {
        $fileType = strtolower($fileType);
        $allowType = ["csv", "xlsx", "xls"];
        if (!in_array($fileType, $allowType)) {
            throw new BadRequestHttpException('File type export invalid!');
        }
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator('Vnext-Kaizen')
            ->setTitle($fileName)
            ->setSubject($fileName);
        $worksheet = $spreadsheet->getActiveSheet();
        $worksheet->getStyle("A1:ZZ1")->getFont()->setBold(true);
        $arrayColumn = range('0', count($data[0]));
        foreach ($arrayColumn as $column) {
            $worksheet->getColumnDimensionByColumn($column)->setAutoSize(true);
        }
        $worksheet->fromArray($data);
        if (count($freeStyles)) {
            foreach ($freeStyles as $free) {
                isset($free['style']) && $worksheet->getStyle($free['cell'])->applyFromArray($free['style']);
                isset($free['url']) && $worksheet->getCell($free['cell'])->getHyperlink()->setUrl($free['url']);
                if (isset($free['autoSize'])) {
                    foreach (range($free['cell'][0], $free['cell'][1]) as $cell) {
                        $spreadsheet->getActiveSheet()->getStyle(strtoupper($cell))->getAlignment()->setWrapText(true);
                    }
                }
            }
        }

        $worksheet->setShowGridlines(false);

        if (count($mergeCell)) {
            foreach ($mergeCell as $cell) {
                $worksheet->mergeCells($cell);
            }
        }
        if (count($formulas)) {
            foreach ($formulas as $cell) {
                $spreadsheet->getActiveSheet()
                    ->setCellValue($cell['cell'], $cell['formula']);
            }
        }
        if (count($formatCodes)) {
            foreach ($formatCodes as $cell) {
                $spreadsheet
                    ->getActiveSheet()
                    ->getStyle($cell['cell'])
                    ->getNumberFormat()
                    ->setFormatCode($cell['code']);
            }
        }
        if (count($autoSizes)) {
            foreach ($autoSizes as $col) {
                $spreadsheet->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getStyle($col)->getAlignment()->setWrapText(true);
            }
        }
        if (count($hyperLinks)) {
            foreach ($hyperLinks as $hyper) {
                $spreadsheet->getActiveSheet()
                    ->getCell($hyper['cell'])
                    ->getHyperlink()
                    ->setUrl($hyper['url']);
            }
        }
        if (count($widthColumns)) {
            foreach ($widthColumns as $column) {
                $worksheet->getColumnDimension($column['column'])->setWidth($column['width'])->setAutoSize(false);
            }
        }

        if (count($heightColumns)) {
            foreach ($heightColumns as $row) {
                $worksheet->getRowDimension($row['row'])->setRowHeight($row['height']);
            }
        }
        self::sendHeaderExport($fileName, $fileType, $spreadsheet);
    }

    /**
     * @param $fileName
     * @param $fileType
     * @param $spreadsheet
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @SuppressWarnings(PHPMD.ExitExpression)
     */
    private static function sendHeaderExport($fileName, $fileType, $spreadsheet)
    {
        header('Content-Encoding: UTF-8');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=$fileName.$fileType");
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = IOFactory::createWriter($spreadsheet, ucwords($fileType));
        if ($fileType === 'csv') {
            $writer->setUseBOM(true);
        }
        $writer->save('php://output');
        exit;
    }

    public static function filterElementArray(array $inputs, array $filters)
    {
        foreach ($inputs as $key => $value) {
            if (!in_array($key, $filters)) {
                unset($inputs[$key]);
            }
        }
        return $inputs;
    }

    public static function getAppUrl()
    {
        if (config('app.mode') == self::MODE_PROD) {
            return config('app.app_url_prod');
        }
        return config('app.app_url');
    }

    public static function validateDate($date, $format = 'Y-m-d')
    {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    public static function dateCheckpointRequire()
    {
        $campaignSrv = app(CampaignServices::class);
        $currentCampaign = $campaignSrv->getCurrentCampaign();
        $startDate = Carbon::parse($currentCampaign->start_date);

        if ($startDate->month == 6 || $startDate->month == 5) {
            return "30/06/{$startDate->year}";
        }

        if ($startDate->month == 12 || $startDate->month == 11) {
            return "31/12/{$startDate->year}";
        }

        return config('app.checkpoint_period');
    }
}
