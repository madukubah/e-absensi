<div class="content">
    <div class="container-fluid">
        <div class="mt-5 clearfix">
            <div class="col-sm-6 col-md-4 col-lg-3 col-xl-3 float-right mr-5">
                <div class="login-box float-left" style="width:100%">
                    <div class="card">
                        <div class="card-body login-card-body">
                            <div class="login-logo">
                                <a href="<?= base_url() ?>index2.html"><b>Login</b></a>
                            </div>

                            <?php
                            if ($this->session->flashdata('alert')) {
                                echo $this->session->flashdata('alert');
                            } ?>
                            <?php echo form_open(""); ?>
                            <label class="col-md-7" for="nama_lengkap">Email</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="identity" id="identity" placeholder="Email">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-7" for="nama_lengkap">Password: </label>
                                <p class="mb-1 col-md-5">
                                    <a href="#">Lupa password?</a>
                                </p>
                            </div>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control" name="user_password" id="user_password" placeholder="password">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-8"></div>
                                <div class="col-4">
                                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                                </div>
                            </div>
                            </form>

                            <p class="mt-4 mb-4 text-center">
                                Tidak punya akun? <a href="<?= base_url('auth/') ?>register" class="text-center">Register</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-7 float-left">
                <div class="col-md-10 col-lg-10 col-xl-10 mt-5 float-right">
                    <div>
                        <h3 class="row justify-content-center text-center">Grafik Kehadiran Pegawai Seluruh SKPD</h3>
                        <div class="chart">
                            <canvas id="barChart" style="height:230px; min-height:230px"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var areaChartData = {
        labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum\'at', 'Sabtu', 'Minggu'],
        datasets: [{
                label: 'Tidak Hadir',
                backgroundColor: 'rgba(235,22,22,0.9)',
                borderColor: 'rgba(235,22,22,0.8)',
                pointRadius: false,
                pointColor: '#3b8bba',
                pointStrokeColor: 'rgba(60,141,188,1)',
                pointHighlightFill: '#fff',
                pointHighlightStroke: 'rgba(60,141,188,1)',
                data: [28, 48, 40, 19, 86, 27, 90]
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
                data: [65, 59, 80, 81, 56, 55, 40]
            },
        ]
    }

    //-------------
    //- BAR CHART -
    //-------------
    var barChartCanvas = $('#barChart').get(0).getContext('2d')
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