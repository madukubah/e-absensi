<div class="card" style="background-color : rgba(255, 255, 255, 0.6) !important">
    <div class="mt-5 ml-5 mr-5 chart">
        <canvas id="pieChart" style="height:230px; min-height:230px"></canvas>
    </div>
    <div class="container ml-3">
        <span>Jumlah Hadir :</span><br>
        <span>Jumlah Tidak Hadir :</span>
    </div>
</div>
<script>
    var donutData = {
        labels: [
            'Hadir',
            'Tidak Hadir',
        ],
        datasets: [{
            data: [700, 500],
            backgroundColor: ['rgba(65, 193, 65, 1)', 'rgba(235,22,22,0.9)'],
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