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
                        <?php echo ( isset( $contents )  ) ? $contents : '' ;  ?>
                        <!--  -->
                        <!--  -->
                        <?php echo ( isset( $pagination_links )  ) ? $pagination_links : '' ;  ?>
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
                            </div>
                        </div>
                    </div>
                    <div class=" col-md-12 ">
                        <div class="card">
                            <div class="card-body">
                                <a href="<?php echo site_url('user/profile/edit') ?>" class="btn btn-block btn-md btn-primary waves-effect">Edit</a>
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