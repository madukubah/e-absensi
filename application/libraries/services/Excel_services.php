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
        $year = $data->year;
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
        $PHPExcel->getActiveSheet()->setCellValue('B2', 'Data Absensi Pegawai ' . $name . ' bulan ' . $month . ' Tahun ' . $year);

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
        $count = [];
        for ($i = 1; $i <= $days; $i++) {
            $id = 0;
            $row = 6;
            foreach ($employees as $key => $employee) {
                if (!isset($count[$employee->id][0])) {
                    $count[$employee->id][0] = 0;
                    $count[$employee->id][1] = 0;
                    $count[$employee->id][2] = 0;
                    $count[$employee->id][3] = 0;
                }
                $attendance = $attendances->$i;
                $column = chr(67 + $i);
                if ((67 + $i) > 90)
                    $column = 'A' . chr(65 + $i - 24);

                if (isset($attendance[$id])) {
                    if ($employee->name == $attendance[$id]->name) {
                        switch ($attendance[$id]->status) {
                            case 0:
                                $PHPExcel->getActiveSheet()->setCellValue($column . $row, 1);
                                $count[$employee->id][1]++;
                                break;
                            case 1:
                                $PHPExcel->getActiveSheet()->setCellValue($column . $row, 2);
                                $count[$employee->id][2]++;
                                break;
                            case 2:
                                $PHPExcel->getActiveSheet()->setCellValue($column . $row, 3);
                                $count[$employee->id][3]++;
                                break;
                        }
                        $id++;
                    } else {
                        $PHPExcel->getActiveSheet()->setCellValue($column . $row, 0);
                        $count[$employee->id][0]++;
                    }
                    $row++;
                } else {
                    $PHPExcel->getActiveSheet()->setCellValue($column . $row, 0);
                    $count[$employee->id][0]++;
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
        $count_out = [];
        for ($i = 1; $i <= $days; $i++) {
            $id = 0;
            $row = $row - count($employees);
            foreach ($employees as $key => $employee) {
                if (!isset($count_out[$employee->id][0])) {
                    $count_out[$employee->id][0] = 0;
                    $count_out[$employee->id][1] = 0;
                    $count_out[$employee->id][2] = 0;
                    $count_out[$employee->id][3] = 0;
                }
                $attendance = $attendances_out->attendances->$i;
                $column = chr(67 + $i);
                if ((67 + $i) > 90)
                    $column = 'A' . chr(65 + $i - 24);

                if (isset($attendance[$id])) {
                    if ($employee->name == $attendance[$id]->name) {
                        switch ($attendance[$id]->status) {
                            case 0:
                                $PHPExcel->getActiveSheet()->setCellValue($column . $row, 1);
                                $count_out[$employee->id][1]++;
                                break;
                            case 1:
                                $PHPExcel->getActiveSheet()->setCellValue($column . $row, 2);
                                $count_out[$employee->id][2]++;
                                break;
                            case 2:
                                $PHPExcel->getActiveSheet()->setCellValue($column . $row, 3);
                                $count_out[$employee->id][3]++;
                                break;
                        }
                        $id++;
                    } else {
                        $PHPExcel->getActiveSheet()->setCellValue($column . $row, 0);
                        $count_out[$employee->id][0]++;
                    }
                    $row++;
                } else {
                    $PHPExcel->getActiveSheet()->setCellValue($column . $row, 0);
                    $count_out[$employee->id][0]++;
                    $row++;
                }
            }
        }

        //info
        $PHPExcel->getActiveSheet()->setCellValue('A' . chr(65 + $days - 24 + 6) . '4', 'Keterangan'); //11,14
        $PHPExcel->getActiveSheet()->setCellValue('A' . chr(65 + $days - 24 + 6) . '5', 'tidak hadir');
        $PHPExcel->getActiveSheet()->setCellValue('A' . chr(65 + $days - 24 + 6) . '6', 'hadir');
        $PHPExcel->getActiveSheet()->setCellValue('A' . chr(65 + $days - 24 + 6) . '7', 'sakit');
        $PHPExcel->getActiveSheet()->setCellValue('A' . chr(65 + $days - 24 + 6) . '8', 'izin');
        $PHPExcel->getActiveSheet()->setCellValue('A' . chr(65 + $days - 24 + 7) . '5', ':'); //1,71
        $PHPExcel->getActiveSheet()->setCellValue('A' . chr(65 + $days - 24 + 7) . '6', ':');
        $PHPExcel->getActiveSheet()->setCellValue('A' . chr(65 + $days - 24 + 7) . '7', ':');
        $PHPExcel->getActiveSheet()->setCellValue('A' . chr(65 + $days - 24 + 7) . '8', ':');
        $PHPExcel->getActiveSheet()->setCellValue('A' . chr(65 + $days - 24 + 8) . '5', 0); //4
        $PHPExcel->getActiveSheet()->setCellValue('A' . chr(65 + $days - 24 + 8) . '6', 1);
        $PHPExcel->getActiveSheet()->setCellValue('A' . chr(65 + $days - 24 + 8) . '7', 2);
        $PHPExcel->getActiveSheet()->setCellValue('A' . chr(65 + $days - 24 + 8) . '8', 3);

        $PHPExcel->getActiveSheet()->setCellValue('A' . chr(65 + $days - 24 + 1) . '4', 'Jumlah');
        $PHPExcel->getActiveSheet()->setCellValue('A' . chr(65 + $days - 24 + 1) . '5', 'Tidak Hadir');
        $PHPExcel->getActiveSheet()->setCellValue('A' . chr(65 + $days - 24 + 2) . '5', 'Hadir');
        $PHPExcel->getActiveSheet()->setCellValue('A' . chr(65 + $days - 24 + 3) . '5', 'Sakit');
        $PHPExcel->getActiveSheet()->setCellValue('A' . chr(65 + $days - 24 + 4) . '5', 'Izin');

        $PHPExcel->getActiveSheet()->setCellValue('A' . chr(65 + $days - 24 + 1) . (count($employees) + 7), 'Jumlah');
        $PHPExcel->getActiveSheet()->setCellValue('A' . chr(65 + $days - 24 + 1) . (count($employees) + 8), 'Tidak Hadir');
        $PHPExcel->getActiveSheet()->setCellValue('A' . chr(65 + $days - 24 + 2) . (count($employees) + 8), 'Hadir');
        $PHPExcel->getActiveSheet()->setCellValue('A' . chr(65 + $days - 24 + 3) . (count($employees) + 8), 'Sakit');
        $PHPExcel->getActiveSheet()->setCellValue('A' . chr(65 + $days - 24 + 4) . (count($employees) + 8), 'Izin');

        // var_dump(($employees[0]->id));
        // die;
        //count(employess == 21)
        // //jumlah ABSEN PAGI
        for ($i = 6; $i < (count($employees) + 6); $i++) {
            $PHPExcel->getActiveSheet()->setCellValue('A' . chr(65 + $days - 24 + 1) . $i, $count[$employees[$i - 6]->id][0]);
            $PHPExcel->getActiveSheet()->setCellValue('A' . chr(65 + $days - 24 + 2) . $i, $count[$employees[$i - 6]->id][1]);
            $PHPExcel->getActiveSheet()->setCellValue('A' . chr(65 + $days - 24 + 3) . $i, $count[$employees[$i - 6]->id][2]);
            $PHPExcel->getActiveSheet()->setCellValue('A' . chr(65 + $days - 24 + 4) . $i, $count[$employees[$i - 6]->id][3]);

            //rumus excel
            //     $PHPExcel->getActiveSheet()->setCellValue('A' . chr(65 + $days - 24 + 1) . $i, '.=COUNTIF(D' . $i . ':AG' . $i . '; CONCATENATE("=";0))');
            //     $PHPExcel->getActiveSheet()->setCellValue('A' . chr(65 + $days - 24 + 2) . $i, '.=COUNTIF(D' . $i . ':AG' . $i . '; CONCATENATE("=";1))');
            //     $PHPExcel->getActiveSheet()->setCellValue('A' . chr(65 + $days - 24 + 3) . $i, '.=COUNTIF(D' . $i . ':AG' . $i . '; CONCATENATE("=";2))');
            //     $PHPExcel->getActiveSheet()->setCellValue('A' . chr(65 + $days - 24 + 4) . $i, '.=COUNTIF(D' . $i . ':AG' . $i . '; CONCATENATE("=";3))');
        }

        // //JUMLAH ABSEN SORE
        for ($i = (count($employees) + 9); $i < (count($employees) + count($employees) + 9); $i++) {
            $PHPExcel->getActiveSheet()->setCellValue('A' . chr(65 + $days - 24 + 1) . $i, $count_out[$employees[$i - (count($employees) + 9)]->id][0]);
            $PHPExcel->getActiveSheet()->setCellValue('A' . chr(65 + $days - 24 + 2) . $i, $count_out[$employees[$i - (count($employees) + 9)]->id][1]);
            $PHPExcel->getActiveSheet()->setCellValue('A' . chr(65 + $days - 24 + 3) . $i, $count_out[$employees[$i - (count($employees) + 9)]->id][2]);
            $PHPExcel->getActiveSheet()->setCellValue('A' . chr(65 + $days - 24 + 4) . $i, $count_out[$employees[$i - (count($employees) + 9)]->id][3]);

            //rumus excel
            //     $PHPExcel->getActiveSheet()->setCellValue('A' . chr(65 + $days - 24 + 1) . $i, '.=COUNTIF(D' . $i . ':AG' . $i . '; CONCATENATE("=";0))');
            //     $PHPExcel->getActiveSheet()->setCellValue('A' . chr(65 + $days - 24 + 2) . $i, '.=COUNTIF(D' . $i . ':AG' . $i . '; CONCATENATE("=";1))');
            //     $PHPExcel->getActiveSheet()->setCellValue('A' . chr(65 + $days - 24 + 3) . $i, '.=COUNTIF(D' . $i . ':AG' . $i . '; CONCATENATE("=";2))');
            //     $PHPExcel->getActiveSheet()->setCellValue('A' . chr(65 + $days - 24 + 4) . $i, '.=COUNTIF(D' . $i . ':AG' . $i . '; CONCATENATE("=";3))');
        }
        ############    style aligment   ####################
        $PHPExcel->getActiveSheet()->getStyle('A' . chr(65 + $days - 24 + 1) . '4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $PHPExcel->getActiveSheet()->getStyle('A' . chr(65 + $days - 24 + 1) . '5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $PHPExcel->getActiveSheet()->getStyle('A' . chr(65 + $days - 24 + 2) . '5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $PHPExcel->getActiveSheet()->getStyle('A' . chr(65 + $days - 24 + 3) . '5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $PHPExcel->getActiveSheet()->getStyle('A' . chr(65 + $days - 24 + 4) . '5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $PHPExcel->getActiveSheet()->getStyle('A' . chr(65 + $days - 24 + 1) . (count($employees) + 7))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $PHPExcel->getActiveSheet()->getStyle('A' . chr(65 + $days - 24 + 1) . (count($employees) + 8))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $PHPExcel->getActiveSheet()->getStyle('A' . chr(65 + $days - 24 + 2) . (count($employees) + 8))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $PHPExcel->getActiveSheet()->getStyle('A' . chr(65 + $days - 24 + 3) . (count($employees) + 8))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $PHPExcel->getActiveSheet()->getStyle('A' . chr(65 + $days - 24 + 4) . (count($employees) + 8))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
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
        $PHPExcel->getActiveSheet()->getColumnDimension('A' . chr(65 + $days - 24 + 6))->setWidth(11.14);
        $PHPExcel->getActiveSheet()->getColumnDimension('A' . chr(65 + $days - 24 + 7))->setWidth(1.71);
        $PHPExcel->getActiveSheet()->getColumnDimension('A' . chr(65 + $days - 24 + 8))->setWidth(4);
        $PHPExcel->getActiveSheet()->getColumnDimension('A' . chr(65 + $days - 24 + 1))->setWidth(10.86);
        $PHPExcel->getActiveSheet()->getColumnDimension('A' . chr(65 + $days - 24 + 2))->setWidth(10.86);
        $PHPExcel->getActiveSheet()->getColumnDimension('A' . chr(65 + $days - 24 + 3))->setWidth(10.86);
        $PHPExcel->getActiveSheet()->getColumnDimension('A' . chr(65 + $days - 24 + 4))->setWidth(10.86);

        #########################     style font  ############################
        $PHPExcel->getActiveSheet()->getStyle('B2')->getFont()->setBold(true);
        $PHPExcel->getActiveSheet()->getStyle('B2')->getFont()->setSize(16);
        $PHPExcel->getActiveSheet()->getStyle('B2')->getFont()->setName('Times New Roman');

        #################  style merge column   ##########################
        $PHPExcel->getActiveSheet()->mergeCells('A' . chr(65 + $days - 24 + 1) . '4:' . 'A' . chr(65 + $days - 24 + 4) . '4');
        $PHPExcel->getActiveSheet()->mergeCells('B2:' . $column . '2');
        $PHPExcel->getActiveSheet()->mergeCells('B4:B5');
        $PHPExcel->getActiveSheet()->mergeCells('B' . (count($employees) + 7) . ':B' . (count($employees) + 8));
        $PHPExcel->getActiveSheet()->mergeCells('C4:C5');
        $PHPExcel->getActiveSheet()->mergeCells('C' . (count($employees) + 7) . ':C' . (count($employees) + 8));
        $PHPExcel->getActiveSheet()->mergeCells('D4:' . $column . '4');
        $PHPExcel->getActiveSheet()->mergeCells('D' . (count($employees) + 7) . ':' . $column . (count($employees) + 7));


        #################  style border column   ##########################
        $PHPExcel->getActiveSheet()->getStyle('A' . chr(65 + $days - 24 + 1) . '4:' . 'A' . chr(65 + $days - 24 + 4) . (count($employees) + 5))->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle('A' . chr(65 + $days - 24 + 1) . (count($employees) + 7) . ':' . 'A' . chr(65 + $days - 24 + 4) . (count($employees) + count($employees) + 8))->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle('B2:' . $column . '2')->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle('B4:' . $column . '5')->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle('B6:' . $column . (count($employees) + 5))->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle('B' . (count($employees) + 7) . ':' . $column . ($row - 1))->applyFromArray($styleArray);

        ###################################################################################
        $filename = 'Data Absensi ' . $name . ' Bulan ' . $month . ' Tahun ' . $year . '.xlsx';

        header('Content-Type: appliaction/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Chace-Control: max-age=0 ');

        $writer = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');

        $writer->save('php://output');
        exit;
    }
}
