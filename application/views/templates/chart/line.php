<div class="card" style="background-color : rgba(255, 255, 255, 0.6) !important">
    <div class="chart">
        <canvas id="lineChart" style="height:250px; min-height:250px"></canvas>
    </div>
</div>
<script>
    var data_hadir = <?php echo json_encode( $count_attendance ) ?>;//'[28, 48, 40, 19, 86, 27, 90];
    var data_alpa = [];//[65, 59, 80, 81, 56, 55, 40];
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
    var areaChartOptions = {
        maintainAspectRatio: false,
        responsive: true,
        legend: {
            display: false
        },
        scales: {
            xAxes: [{
                gridLines: {
                    display: false,
                }
            }],
            yAxes: [{
                gridLines: {
                    display: false,
                }
            }]
        }
    }
    //-------------
    //- LINE CHART -
    //--------------
    var lineChartCanvas = $('#lineChart').get(0).getContext('2d')
    var lineChartOptions = jQuery.extend(true, {}, areaChartOptions)
    var lineChartData = jQuery.extend(true, {}, areaChartData)
    lineChartData.datasets[0].fill = false;
    lineChartData.datasets[1].fill = false;
    lineChartOptions.datasetFill = false

    var lineChart = new Chart(lineChartCanvas, {
        type: 'line',
        data: lineChartData,
        options: lineChartOptions
    })
</script>