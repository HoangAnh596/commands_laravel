<?php

namespace App\Services\Impl;

use App\Library\Common;
use App\Models\CheckPoint;
use App\Repositories\CheckPointRepository;
use App\Services\CheckPointServices;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class CheckPointServicesImpl extends BaseServicesImpl implements CheckPointServices
{
    public function repository()
    {
        return CheckPointRepository::class;
    }

    public function getListCheckpoint($params = [])
    {
        return $this->repository->getListCheckpoint($params);
    }

    public function getMyCheckpoint($userId)
    {
        return $this->repository->getMyCheckpoint($userId);
    }

    public function getEmployeeAttributes()
    {
        return $this->repository->getEmployeeAttributes();
    }

    public function getAssessorAttributes()
    {
        return $this->repository->getAssessorAttributes();
    }

    public function getManagerAttributes()
    {
        return $this->repository->getManagerAttributes();
    }

    public function searchReport($params = [])
    {
        return $this->repository->searchReport($params);
    }

    public function total($params = [])
    {
        $data = $this->repository->total($params);
        return $this->getTotal($data->toArray());
    }

    public function getTotal($data)
    {
        $total = array_reduce($data, function ($carry, $item) {
            return $carry + (int)$item['amount'];
        }, 0);
        $result = [
            'total' => $total,
            'new' => 0,
            'reviewing' => 0,
            'inprogress' => 0,
            'approving' => 0,
            'done' => 0,
        ];
        foreach ($data as $item) {
            $amount = (int)$item['amount'];
            $status = (int)$item['status'];
            if ($status === CheckPoint::STATUS_NEW) {
                $result['new'] += $amount;
            } elseif ($status === CheckPoint::STATUS_DONE) {
                $result['done'] += $amount;
            } elseif ($status === CheckPoint::STATUS_REVIEWING) {
                $result['reviewing'] += $amount;
            } elseif ($status === CheckPoint::STATUS_APPROVING) {
                $result['approving'] += $amount;
            } else {
                $result['inprogress'] += $amount;
            }
        }
        return $result;
    }

    /**
     * @SuppressWarnings("PMD.ExcessiveMethodLength")
     */
    public function exportExcel($dataExport, $campaign)
    {
        $title = "Danh s??ch checkpoint_" . $campaign->start_date . "_" . $campaign->end_date;
        $column = [
            'STT',
            'M?? NV',
            'H??? v?? t??n',
            'B??? ph???n',
            'Ch???c danh',
            'Th???i gian l??m vi???c',
            'Tr???ng th??i',
            'Ng?????i ph??? tr??ch ',
            '??i???m',
            'K???t qu???',
            'Ghi ch??',
            '????? xu???t c???a NV',
            '?? ki???n c???a c??n b??? QLTT',
            '?? ki???n c???a c??n b??? qu???n l?? c???p m???t',
            "Nhi???m v??? ch??nh (NV ????nh gi??)",
            'M???c ti??u c??ng vi???c (NV ????nh gi??)',
            'K???t qu??? c??ng vi???c (NV ????nh gi??)'
        ];
        $data = [[$title], $column];
        if (!empty($dataExport)) {
            foreach ($dataExport as $key => $item) {
                if (!empty($item->employee)) {
                    $data[] = [
                        $key + 1,
                        $item->employee->employee_code,
                        $item->employee->firstname . ' ' . $item->employee->lastname,
                        $item->department_name,
                        $item->job_rank,
                        Common::formatTimeWorking($item->employee->join_date),
                        Common::getLabelStatusCheckpoint($item->status),
                        $item->assessor ? $item->assessor->username : '',
                        (int)$item->status === CheckPoint::STATUS_DONE ? $item->emp_total_final : '',
                        (int)$item->status === CheckPoint::STATUS_DONE ?
                            Common::getLabelResultCheckpoint($item->emp_total_final) : '',
                        $item->note !== null ? $item->note : '',
                        nl2br(strip_tags($item->emp_opinions)),
                        nl2br(strip_tags($item->assessor_opinions)),
                        nl2br(strip_tags($item->manager_opinions)),
                        nl2br(strip_tags($item->emp_assignment)),
                        nl2br(strip_tags($item->emp_target)),
                        nl2br(strip_tags($item->emp_result))
                    ];
                }
            }
        }

        $mergeCell = ["A1:Q1"];
        $autoSize = range('A', 'Q');
        $freeStyle = $this->styleExportCommon(2, count($data));
        $widthColumns = array(
            [
                'column' => 'M',
                'width' => 50
            ],
            [
                'column' => 'N',
                'width' => 50
            ],
            [
                'column' => 'O',
                'width' => 50
            ],
            [
                'column' => 'P',
                'width' => 50
            ],
            [
                'column' => 'Q',
                'width' => 50
            ],
            [
                'column' => 'L',
                'width' => 50
            ],
            [
                'column' => 'K',
                'width' => 30
            ],
            [
                'column' => 'I',
                'width' => 15
            ],
            [
                'column' => 'J',
                'width' => 15
            ],
            [
                'column' => 'D',
                'width' => 15
            ],
            [
                'column' => 'B',
                'width' => 15
            ],
            [
                'column' => 'C',
                'width' => 20
            ],
        );
        return Common::createFileExcel(
            $data,
            'xlsx',
            $title,
            $mergeCell,
            $freeStyle,
            [],
            [],
            $autoSize,
            [],
            $widthColumns
        );
    }

    public function styleExportCommon($startRow, $endRow)
    {
        return [
            [
                "cell" => "A1",
                'style' => [
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'font' => array(
                        'bold' => true
                    )
                ]
            ],
            [
                "cell" => "A2:Q2",
                "style" => [
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => '16D39A',
                        ],
                    ]
                ]
            ],
            [
                "cell" => "A$startRow:Q$endRow",
                "style" => [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000']
                        ],
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true
                    ],
                ]
            ]
        ];
    }
}
