<?php

class Excel_services
{
    function __construct()
    {
        //
    }

    public function excel_config($data)
    {
        $employees = $data->employee;
        $days = $data->days;
        $attendances = $data->attendances;
        $month = $data->month;
        $name = $data->name;
        $attendances_out = $data->get_out;
        require(APPPATH . 'PHPExcel-1.8/Classes/PHPExcel.php');
        require(APPPATH . 'PHPExcel-1.8/Classes/PHPExcel/Writer/Excel2007.php');

        $PHPExcel = new PHPExcel();

        ########################  DATA UMUM  ########################
        $PHPExcel->getProperties()->setCreator('Creator');
        $PHPExcel->getProperties()->setLastModifiedBy();
        $PHPExcel->getProperties()->setTitle('Creator');
        $PHPExcel->getProperties()->setSubject('Creator');
        $PHPExcel->getProperties()->setDescription('Creator');

        $PHPExcel->getActiveSheetIndex(0);
        $PHPExcel->getActiveSheet()->setTitle('title');

        ########################  ARRAY STYLE  ########################

        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => '0000'))));

        //Header
        $PHPExcel->getActiveSheet()->setCellValue('B2', 'Data Absensi Pegawai ' . $name . ' bulan ' . $month);

        $PHPExcel->getActiveSheet()->setCellValue('B4', 'No');
        $PHPExcel->getActiveSheet()->setCellValue('C4', 'Nama Pegawai');
        $PHPExcel->getActiveSheet()->setCellValue('D4', 'Kehadiran (Absen Datang)');


        for ($i = 0; $i < $days; $i++) {
            $column = chr(68 + $i);
            if ((68 + $i) > 90) {
                $column = 'A' . chr(65 + $i - 23);
            }

            $PHPExcel->getActiveSheet()->setCellValue($column . '5', ($i + 1));
            $PHPExcel->getActiveSheet()->getColumnDimension($column)->setWidth(2.8);
        }


        ##############################################################################
        // nama pegawai
        $row = 6;
        foreach ($employees as $key => $employee) {
            $PHPExcel->getActiveSheet()->setCellValue('B' . $row, ($row - 5));
            // $PHPExcel->getActiveSheet()->getStyle('B' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $PHPExcel->getActiveSheet()->setCellValue('C' . $row, $employee->name);

            $row++;
        }
        //data absensi
        for ($i = 1; $i <= $days; $i++) {
            $id = 0;
            $row = 6;
            foreach ($employees as $key => $employee) {
                $attendance = $attendances->$i;
                $column = chr(67 + $i);
                if ((67 + $i) > 90)
                    $column = 'A' . chr(65 + $i - 24);

                if (isset($attendance[$id])) {
                    if ($employee->name == $attendance[$id]->name) {
                        switch ($attendance[$id]->status) {
                            case 0:
                                $PHPExcel->getActiveSheet()->setCellValue($column . $row, 1);
                                break;
                            case 1:
                                $PHPExcel->getActiveSheet()->setCellValue($column . $row, 2);
                                break;
                            case 2:
                                $PHPExcel->getActiveSheet()->setCellValue($column . $row, 3);
                                break;
                        }
                        $id++;
                    } else {
                        $PHPExcel->getActiveSheet()->setCellValue($column . $row, 0);
                    }
                    $row++;
                } else {
                    $PHPExcel->getActiveSheet()->setCellValue($column . $row, 0);
                    $row++;
                }
            }
        }


        $PHPExcel->getActiveSheet()->setCellValue('B' . ++$row, 'No');
        $PHPExcel->getActiveSheet()->setCellValue('C' . $row, 'Nama Pegawai');
        $PHPExcel->getActiveSheet()->setCellValue('D' . $row++, 'Kehadiran (Absen Pulang)');

        for ($i = 0; $i < $days; $i++) {
            $column = chr(68 + $i);
            if ((68 + $i) > 90) {
                $column = 'A' . chr(65 + $i - 23);
            }

            $PHPExcel->getActiveSheet()->setCellValue($column . $row, ($i + 1));
            $PHPExcel->getActiveSheet()->getColumnDimension($column)->setWidth(2.8);
        }


        ##############################################################################
        // nama pegawai
        $row = $row + 1;
        $i = 1;
        foreach ($employees as $key => $employee) {
            $PHPExcel->getActiveSheet()->setCellValue('B' . $row, ($i++));
            // $PHPExcel->getActiveSheet()->getStyle('B' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $PHPExcel->getActiveSheet()->setCellValue('C' . $row, $employee->name);

            $row++;
        }

        //data absensi
        for ($i = 1; $i <= $days; $i++) {
            $id = 0;
            $row = $row - count($employees);
            foreach ($employees as $key => $employee) {
                $attendance = $attendances_out->attendances->$i;
                $column = chr(67 + $i);
                if ((67 + $i) > 90)
                    $column = 'A' . chr(65 + $i - 24);

                if (isset($attendance[$id])) {
                    if ($employee->name == $attendance[$id]->name) {
                        $PHPExcel->getActiveSheet()->setCellValue($column . $row, 1);
                        $id++;
                    } else {
                        $PHPExcel->getActiveSheet()->setCellValue($column . $row, 0);
                    }
                    $row++;
                } else {
                    $PHPExcel->getActiveSheet()->setCellValue($column . $row, 0);
                    $row++;
                }
            }
        }

        //info
        $PHPExcel->getActiveSheet()->setCellValue('AJ4', 'Keterangan');
        $PHPExcel->getActiveSheet()->setCellValue('AJ5', 'tidak hadir');
        $PHPExcel->getActiveSheet()->setCellValue('AJ6', 'hadir');
        $PHPExcel->getActiveSheet()->setCellValue('AJ7', 'sakit');
        $PHPExcel->getActiveSheet()->setCellValue('AJ8', 'izin');
        $PHPExcel->getActiveSheet()->setCellValue('AK5', ':');
        $PHPExcel->getActiveSheet()->setCellValue('AK6', ':');
        $PHPExcel->getActiveSheet()->setCellValue('AK7', ':');
        $PHPExcel->getActiveSheet()->setCellValue('AK8', ':');
        $PHPExcel->getActiveSheet()->setCellValue('AL5', 0);
        $PHPExcel->getActiveSheet()->setCellValue('AL6', 1);
        $PHPExcel->getActiveSheet()->setCellValue('AL7', 2);
        $PHPExcel->getActiveSheet()->setCellValue('AL8', 3);
        // var_dump(count($employees));
        // die;
        //count(employess == 21)
        //jumlah ABSEN PAGI
        $PHPExcel->getActiveSheet()->setCellValue('AJ11', 'Absen Pagi');
        $PHPExcel->getActiveSheet()->setCellValue('AJ12', 'Jumlah Tidak Hadir');
        $PHPExcel->getActiveSheet()->setCellValue('AJ13', 'Jumlah Hadir');
        $PHPExcel->getActiveSheet()->setCellValue('AJ14', 'Jumlah Sakit');
        $PHPExcel->getActiveSheet()->setCellValue('AJ15', 'Jumlah Izin');
        $PHPExcel->getActiveSheet()->setCellValue('AK12', ':');
        $PHPExcel->getActiveSheet()->setCellValue('AK13', ':');
        $PHPExcel->getActiveSheet()->setCellValue('AK14', ':');
        $PHPExcel->getActiveSheet()->setCellValue('AK15', ':');
        $PHPExcel->getActiveSheet()->setCellValue('AL12', 'COUNTIF(D6:AG' . (count($employees) + 5) . '; CONCATENATE("=";0))');
        $PHPExcel->getActiveSheet()->setCellValue('AL13', 'COUNTIF(D6:AG' . (count($employees) + 5) . '; CONCATENATE("=";1))');
        $PHPExcel->getActiveSheet()->setCellValue('AL14', 'COUNTIF(D6:AG' . (count($employees) + 5) . '; CONCATENATE("=";2))');
        $PHPExcel->getActiveSheet()->setCellValue('AL15', 'COUNTIF(D6:AG' . (count($employees) + 5) . '; CONCATENATE("=";3))');

        //JUMLAH ABSEN SORE
        $PHPExcel->getActiveSheet()->setCellValue('AJ18', 'Absen Sore');
        $PHPExcel->getActiveSheet()->setCellValue('AJ19', 'Jumlah Tidak Hadir');
        $PHPExcel->getActiveSheet()->setCellValue('AJ20', 'Jumlah Hadir');
        $PHPExcel->getActiveSheet()->setCellValue('AJ21', 'Jumlah Sakit');
        $PHPExcel->getActiveSheet()->setCellValue('AJ22', 'Jumlah Izin');
        $PHPExcel->getActiveSheet()->setCellValue('AK19', ':');
        $PHPExcel->getActiveSheet()->setCellValue('AK20', ':');
        $PHPExcel->getActiveSheet()->setCellValue('AK21', ':');
        $PHPExcel->getActiveSheet()->setCellValue('AK22', ':');
        $PHPExcel->getActiveSheet()->setCellValue('AL19', 'COUNTIF(D' . (count($employees) + 9) . ':AG' . (count($employees) + 29) . '; CONCATENATE("=";0))');
        $PHPExcel->getActiveSheet()->setCellValue('AL20', 'COUNTIF(D' . (count($employees) + 9) . ':AG' . (count($employees) + 29) . '; CONCATENATE("=";1))');
        $PHPExcel->getActiveSheet()->setCellValue('AL21', 'COUNTIF(D' . (count($employees) + 9) . ':AG' . (count($employees) + 29) . '; CONCATENATE("=";2))');
        $PHPExcel->getActiveSheet()->setCellValue('AL22', 'COUNTIF(D' . (count($employees) + 9) . ':AG' . (count($employees) + 29) . '; CONCATENATE("=";3))');

        ############    style aligment   ####################
        $PHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $PHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $PHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $PHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $PHPExcel->getActiveSheet()->getStyle('B4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $PHPExcel->getActiveSheet()->getStyle('B' . (count($employees) + 7))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $PHPExcel->getActiveSheet()->getStyle('C4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $PHPExcel->getActiveSheet()->getStyle('C4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $PHPExcel->getActiveSheet()->getStyle('C' . (count($employees) + 7))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $PHPExcel->getActiveSheet()->getStyle('C' . (count($employees) + 7))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $PHPExcel->getActiveSheet()->getStyle('D4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $PHPExcel->getActiveSheet()->getStyle('D' . (count($employees) + 7))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $PHPExcel->getActiveSheet()->getStyle('D:' . $column . '')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $PHPExcel->getActiveSheet()->getStyle('D:' . $column . '')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        #####################   style global width colum  #########################
        $PHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(1.45);
        $PHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(3.3);
        $PHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
        $PHPExcel->getActiveSheet()->getColumnDimension('AJ')->setWidth(17.43);
        $PHPExcel->getActiveSheet()->getColumnDimension('AK')->setWidth(2);
        $PHPExcel->getActiveSheet()->getColumnDimension('AL')->setWidth(8.43);

        #########################     style font  ############################
        $PHPExcel->getActiveSheet()->getStyle('B2')->getFont()->setBold(true);
        $PHPExcel->getActiveSheet()->getStyle('B2')->getFont()->setSize(16);
        $PHPExcel->getActiveSheet()->getStyle('B2')->getFont()->setName('Times New Roman');

        #################  style merge column   ##########################
        $PHPExcel->getActiveSheet()->mergeCells('B2:' . $column . '2');
        $PHPExcel->getActiveSheet()->mergeCells('B4:B5');
        $PHPExcel->getActiveSheet()->mergeCells('B' . (count($employees) + 7) . ':B' . (count($employees) + 8));
        $PHPExcel->getActiveSheet()->mergeCells('C4:C5');
        $PHPExcel->getActiveSheet()->mergeCells('C' . (count($employees) + 7) . ':C' . (count($employees) + 8));
        $PHPExcel->getActiveSheet()->mergeCells('D4:' . $column . '4');
        $PHPExcel->getActiveSheet()->mergeCells('D' . (count($employees) + 7) . ':' . $column . (count($employees) + 7));


        #################  style border column   ##########################
        $PHPExcel->getActiveSheet()->getStyle('B2:' . $column . '2')->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle('B4:' . $column . '5')->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle('B6:' . $column . (count($employees) + 5))->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle('B' . (count($employees) + 7) . ':' . $column . ($row - 1))->applyFromArray($styleArray);

        ###################################################################################
        $filename = 'Data Absensi ' . $name . ' Bulan ' . $month . '.xlsx';

        header('Content-Type: appliaction/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Chace-Control: max-age=0 ');

        $writer = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');

        $writer->save('php://output');
        exit;
    }
}
