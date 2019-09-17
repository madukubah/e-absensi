<div class="login-box">
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
            <label class="col-md-7" for="nama_lengkap">No HP: </label>
            <div class="input-group mb-3">
                <input type="text" class="form-control" name="identity" id="identity" placeholder="No HP">
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