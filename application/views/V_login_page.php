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
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-7 float-left">
                <div class="col-md-10 col-lg-10 col-xl-10 mt-5 float-right">
                    <div>
                        <h3 class="row justify-content-center text-center">Grafik Kehadiran Pegawai</h3>
                        <?= $chart?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
