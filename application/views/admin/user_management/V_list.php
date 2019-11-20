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
    <div class="box">
        <div class="box-header">
          <h3 class="box-title"><?php echo $page_title ?></h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead class="thin-border-bottom">
                    <tr >
                        <th style="width:50px">No</th>
                        <th>Username</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>no HP</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php 
                    $no =1;

                    foreach( $users as $user ):
                        if(  $user->id == 1 ) continue;
                    ?>
                    <tr <?php if($user->active == 0) echo "style='background-color: #f7c8c8 !important'" ?>>
                        <td>
                            <?php echo $no?>
                        </td>
                    <td>
                            <?php echo $user->username?>
                        </td>
                        <td>
                            <?php echo $user->first_name." ".$user->last_name  ?>
                        </td>
                        
                        <td>
                            <?php echo $user->_email?>
                        </td>
                        <td>
                            <?php echo $user->phone?>
                        </td>
                        <td>
                            <!-- <button class="btn btn-white btn-info btn-bold btn-xs" data-toggle="modal" data-target="#editModal<?php echo $user->user_id;?>">
                                <i class="ace-icon fa fa-edit bigger-120 blue"></i>
                            </button> -->
                            <a href="<?php echo site_url('admin/user_management/index/').$user->id_user;?>" class="btn-sm btn-primary">Detail</a>
                            <button class="btn btn-white btn-danger btn-bold btn-xs" data-toggle="modal" data-target="#deleteModal<?php echo $user->id_user?>">
                                <i class="ace-icon fa fa-trash bigger-120 red"></i>
                            </button>
                        </td>
                    </tr>
                    <!-- user -->
                        <!-- Modal Delete-->
                        <div class="modal fade" id="deleteModal<?php echo  $user->id_user;?>" role="dialog">
                            <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <?php echo form_open("admin/admin/deleteUser");?>
                                <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">#Delete User</h4>
                                </div>
                                <div class="modal-body">
                                <div class="alert alert-danger">Are you sure want delete "<b><?php echo $user->user_username?></b>?" ?</div>
                                </div>
                                <div class="modal-footer">
                                <input type="hidden" class="form-control" value="<?php echo  $user->id_user ?>" name="id_user" required="required">
                                <input type="hidden" class="form-control" value="<?php echo  $user->user_username?>" name="user_username" required="required">
                                <button type="submit" class="btn btn-danger">Ya</button>
                                <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-large"></i>&nbsp;Batal</button>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                            </div>
                        </div>
                        <!--  -->
                    <?php 
                    $no++;
                    endforeach;?>
                    </tbody>
                </table>
            </div>    
      </div>
    </div>

    
  </section>
</div>