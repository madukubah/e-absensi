    <div class="register-box align-content-center">
        <?php
        if ($this->session->flashdata('alert')) {
            echo $this->session->flashdata('alert');
        } ?>
        <div class="card" style="width: 500px">
            <div class="card-body register-card-body">
                <div class="register-logo">
                    <b>Register</b>
                </div>

                <?php echo form_open(""); ?>
                <div class="row">
                    <div class="col-md-6">
                        <label>First name: </label>
                        <div class="input-group mb-3">
                            <?php echo form_input($first_name); ?>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-address-card"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="nama_lengkap">Last name: </label>
                        <div class="input-group mb-3">
                            <?php echo form_input($last_name); ?>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-address-card"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <label for="email">Email: </label>
                <div class="input-group mb-3">
                    <?php echo form_input($email); ?>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <label for="nomor_telepon">Phone: </label>
                <div class="input-group mb-3">
                    <?php echo form_input($phone); ?>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-address-book"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label for="password">Password: </label>
                        <div class="input-group mb-3">
                            <?php echo form_input($password); ?>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user-lock"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="password_confirm">Re-Password: </label>
                        <div class="input-group mb-3">
                            <?php echo form_input($password_confirm); ?>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-8"></div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Register</button>
                    </div>
                </div>
                </form>
                Sudah punya akun? <a href="<?= base_url('auth/') ?>login" class="text-center">Login</a>
            </div>
        </div>
    </div>