    <div class="card p-2" style="background-color : rgba(255, 255, 255, 0.6) !important">
        <h5 class="justify-content-center text-center" >Absen Masuk</h5>

        <div class="mt-5 ml-5 mr-5 chart">
            <canvas id="bar_absensi" style="height:230px; min-height:230px"></canvas>
        </div>
        <div class="container ml-5">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <a href="<?= base_url('home/view/0') . '?fingerprint_id=' . $fingerprint_id . '&date=' . $date . '&month=' . $month ?>">Jumlah Hadir : <?= ($sum_attendances) ?></a><br>
                    <a href="<?= base_url('home/view/1') . '?fingerprint_id=' . $fingerprint_id . '&date=' . $date . '&month=' . $month ?>">Jumlah Sakit : <?= ($sum_sick) ?></a><br>
                    <a href="<?= base_url('home/view/2') . '?fingerprint_id=' . $fingerprint_id . '&date=' . $date . '&month=' . $month ?>">Jumlah izin : <?= ($sum_permission) ?></a><br>
                    <a href="<?= base_url('home/view/3') . '?fingerprint_id=' . $fingerprint_id . '&date=' . $date . '&month=' . $month ?>">Jumlah Tidak Hadir : <?= $sum_absences ?> </a>
                </div>
                <div class="col-md-6 col-sm-12">
                    <span>Jumlah Pegawai : <?= ($employee_count) ?></span><br>
                </div>
            </div>
        </div>
        <br>
    </div>
    <script>
        var data_alpa = <?php echo json_encode($count_attendance) ?>; //'[28, 48, 40, 19, 86, 27, 90];
        var data_izin = <?php echo json_encode($permission) ?>; //'[28, 48, 40, 19, 86, 27, 90];
        var data_sakit = <?php echo json_encode($sick) ?>; //'[28, 48, 40, 19, 86, 27, 90];
        var data_hadir = <?php echo json_encode($absences) ?>; //[65, 59, 80, 81, 56, 55, 40];
        var areaChartData = {
            labels: <?php echo json_encode($days) ?>,
            datasets: [{
                    label: 'Tidak Hadir',
                    backgroundColor: 'rgba(235,22,22,0.9)',
                    borderColor: 'rgba(235,22,22,0.8)',
                    pointRadius: false,
                    pointColor: '#3b8bba',
                    pointStrokeColor: 'rgba(60,141,188,1)',
                    pointHighlightFill: '#fff',
                    pointHighlightStroke: 'rgba(60,141,188,1)',
                    data: data_hadir
                },
                {
                    label: 'Hadir',
                    backgroundColor: 'rgba(65, 193, 65, 1)',
                    borderColor: 'rgba(65, 193, 65, 1)',
                    pointRadius: false,
                    pointColor: 'rgba(210, 214, 222, 1)',
                    pointStrokeColor: '#c1c7d1',
                    pointHighlightFill: '#fff',
                    pointHighlightStroke: 'rgba(220,220,220,1)',
                    data: data_alpa
                },
                {
                    label: 'Sakit',
                    backgroundColor: 'rgba(239, 239, 26, 1)',
                    borderColor: 'rgba(239, 239, 26, 1)',
                    pointRadius: false,
                    pointColor: 'rgba(210, 214, 222, 1)',
                    pointStrokeColor: '#c1c7d1',
                    pointHighlightFill: '#fff',
                    pointHighlightStroke: 'rgba(220,220,220,1)',
                    data: data_sakit
                },
                {
                    label: 'Izin',
                    backgroundColor: 'rgba(26, 111, 239, 1)',
                    borderColor: 'rgba(26, 111, 239, 1)',
                    pointRadius: false,
                    pointColor: 'rgba(210, 214, 222, 1)',
                    pointStrokeColor: '#c1c7d1',
                    pointHighlightFill: '#fff',
                    pointHighlightStroke: 'rgba(220,220,220,1)',
                    data: data_izin
                },
            ]
        }

        //-------------
        //- BAR CHART -
        //-------------
        var barChartCanvas = $('#bar_absensi').get(0).getContext('2d')
        var barChartData = jQuery.extend(true, {}, areaChartData)
        var temp0 = areaChartData.datasets[0]
        var temp1 = areaChartData.datasets[1]
        var temp2 = areaChartData.datasets[2]
        var temp3 = areaChartData.datasets[3]
        barChartData.datasets[0] = temp1
        barChartData.datasets[1] = temp0
        barChartData.datasets[2] = temp2
        barChartData.datasets[3] = temp3

        var barChartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            datasetFill: false,
            scales: {
                xAxes: [{
                    ticks: {
                        beginAtZero: true,
                        stepSize: 10,
                        max: 70
                    },
                }]
            }
        }

        var barChart = new Chart(barChartCanvas, {
            type: 'horizontalBar',
            data: barChartData,
            options: barChartOptions
        })
    </script>