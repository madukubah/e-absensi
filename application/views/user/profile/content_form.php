<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h5 class="m-0 text-dark"><?php echo $block_header ?></h5>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
        <div class="row ">
            <!-- header -->
            <div class=" col-md-12 ">
                <div class="card">
                    <div class="card-header">
                        <div class="row clearfix">
                            <div class="col-md-12">
                                    <!-- alert  -->
                                    <?php
                                        echo $alert;
                                    ?>
                                    <!-- alert  -->
                            </div>
                        </div>
                        <!--  -->
                        <div class="row clearfix" >
                            <div class="col-md-6">
                            <h2>
                                <?php echo strtoupper($header)?>
                            </h2>
                            </div>
                        </div>
                        <!--  -->
                    </div>
                </div>
            </div>
            <!--  -->
            <div class=" col-md-8 ">
                <div class="card">
                    <div class="card-body">
                        <!--  -->
                        <?php echo form_open();  ?>
                        <?php echo ( isset( $contents )  ) ? $contents : '' ;  ?>
                        
                        <button class="btn btn-bold btn-success btn-sm " style="margin-left: 5px;" type="submit" >
                            Simpan
                        </button>

                        <?php echo form_close()  ?>
                        <!--  -->
                    </div>
                </div>
            </div>
            <!-- photo -->
            <div class=" col-md-4 ">
                <div class="row clearfix">
                    <div class=" col-md-12 ">
                            <div class="card">
                                <div class="card-body">
                                    <?php if( ! $user->image == "" ) :?>
                                        <img class=" img-fluid " src="<?php echo base_url('uploads/users_photo/').$user->image ?>">
                                    <?php else: ?>
                                        <img class="img-fluid" src="<?php echo base_url('assets/')?>img/user.png" alt="User" />
                                    <?php endif; ?>
                                    <br>
                                    <br>
                                    <?php echo ( isset( $edit_photo )  ) ? $edit_photo : '' ;  ?>
                                    
                                </div>
                            </div>
                    </div>
                </div>
            </div>
            <!--  -->
        </div>
    </div>
  </section>
</div>