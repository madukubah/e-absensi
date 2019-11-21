<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $page_title ?>
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Dashboard</li>
  </ol>
</section>
<!-- alert  -->
<?php
    if($this->session->flashdata('alert')){
        echo $this->session->flashdata('alert');
    }
?>
<!-- alert  -->
<!-- Main content -->
  <section class="content">
    <?php foreach( $users as $user ):  ?>
    <?php echo form_open_multipart();?>
        <div class="row">
            <div class="col-md-9">
                <div class="box">
                    <div class="box-body">
                    <!-- - -->
                    <div class="row">
                        <div class="col-md-3">
                            <label for="" class="control-label">Username</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control"  name="user_profile_phone" value="<?php echo set_value("user_username" , $user->user_username); ?>" readonly/>
                            <span style="color:red"><?php echo form_error("user_profile_phone"); ?></span>
                        </div>
                    </div>
                    <!--  -->
                    <!-- - -->
                    <div class="row">
                        <div class="col-md-3">
                            <label for="" class="control-label">Nama Depan</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control"  name="user_profile_fullname" value="<?php echo set_value("user_name" , $user->user_first_name." ".$user->user_last_name   );  ?>" readonly />
                            <span style="color:red"><?php echo form_error("user_profile_fullname"); ?></span>
                        </div>
                    </div>
                    <!--  -->
                    <!-- - -->
                    <div class="row">
                        <div class="col-md-3">
                            <label for="" class="control-label">Alamat</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control"  name="user_profile_address" value="<?php echo set_value("user_address",$user->user_address  ); ?>"readonly  />
                            <span style="color:red"><?php echo form_error("user_profile_address"); ?></span>
                        </div>
                    </div>
                    <!--  -->
                    <!-- - -->
                    <div class="row">
                        <div class="col-md-3">
                            <label for="" class="control-label">No Telp</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control"  name="user_profile_phone" value="<?php echo set_value("user_phone" , $user->user_phone); ?>" readonly/>
                            <span style="color:red"><?php echo form_error("user_profile_phone"); ?></span>
                        </div>
                    </div>
                    <!--  -->
                    <!-- - -->
                    <div class="row">
                        <div class="col-md-3">
                            <label for="" class="control-label">Email</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control"  name="user_profile_phone" value="<?php echo set_value("user_email" , $user->user_email); ?>" readonly/>
                            <span style="color:red"><?php echo form_error("user_profile_phone"); ?></span>
                        </div>
                    </div>
                    <!--  -->
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <img class="profile-user-img img-responsive img-box" src="<?php  echo $a =  ( empty($user->user_image) ) ?  base_url('assets-front').'/assets/img/theme/team-4-800x800.jpg' : base_url('uploads/users_photo/').$user->user_image ?>" >   
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>
        <!-- <div class="row">
            <div class="col-md-9">
                <div class="box">
                    <div class="box-body">
                        <a href="<?php echo site_url('user/profile/edit') ?>" class="btn btn-primary pull-right"><b>edit</b></a>
                    </div>
                </div>
            </div>
        </div> -->
    <?php echo form_close()?>
    <?php endforeach;  ?>
  </section>
</div>