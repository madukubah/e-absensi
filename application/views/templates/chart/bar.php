    <div class="chart">
        <canvas id="bar_absensi" style="height:230px; min-height:230px"></canvas>
    </div>
    <script>
        var data_alpa = <?php echo json_encode( $count_attendance ) ?>;//'[28, 48, 40, 19, 86, 27, 90];
        var data_hadir = <?php echo json_encode( $absences ) ?>;//[65, 59, 80, 81, 56, 55, 40];
        var areaChartData = {
            labels: <?php echo json_encode( $days ) ?>,
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
            ]
        }

        //-------------
        //- BAR CHART -
        //-------------
        var barChartCanvas = $('#bar_absensi').get(0).getContext('2d')
        var barChartData = jQuery.extend(true, {}, areaChartData)
        var temp0 = areaChartData.datasets[0]
        var temp1 = areaChartData.datasets[1]
        barChartData.datasets[0] = temp1
        barChartData.datasets[1] = temp0

        var barChartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            datasetFill: false
        }

        var barChart = new Chart(barChartCanvas, {
            type: 'bar',
            data: barChartData,
            options: barChartOptions
        })
    </script>