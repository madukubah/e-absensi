<div class="card p-2" style="background-color : rgba(255, 255, 255, 0.6) !important">
    <h5 class="justify-content-center text-center" >Absen Masuk</h5>
    <div class="mt-5 ml-5 mr-5 chart">
        <canvas id="pieChart" style="height:230px; min-height:230px"></canvas>
    </div>
    <div class="container ml-5">
        <span>Jumlah Hadir : <?= ($sum_attendances) ?></span><br>
        <span>Jumlah Sakit : <?= ($sum_sick) ?></span><br>
        <span>Jumlah izin : <?= ($sum_permission) ?></span><br>
        <span>Jumlah Tidak Hadir : <?= $sum_absences ?> </span>
    </div>
    <br>
</div>
<script>
    var donutData = {
        labels: [
            'Hadir',
            'Tidak Hadir',
            'Sakit',
            'Izin',
        ],
        datasets: [{
            data: [<?= ($sum_attendances) ?>, <?= $sum_absences ?>, <?= $sum_sick ?>, <?= $sum_permission ?>],
            backgroundColor: ['rgba(65, 193, 65, 1)', 'rgba(235,22,22,0.9)', 'rgba(239, 239, 26, 1)', 'rgba(26, 111, 239, 1)'],
        }]
    }
    var donutOptions = {
        maintainAspectRatio: false,
        responsive: true,
    }
    //-------------
    //- PIE CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
    var pieData = donutData;
    var pieOptions = {
        maintainAspectRatio: false,
        responsive: true,
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    var pieChart = new Chart(pieChartCanvas, {
        type: 'pie',
        data: pieData,
        options: pieOptions
    })
</script>