<style>
    label.cabinet{
	display: block;
	cursor: pointer;
}

label.cabinet input.file{
	position: relative;
	height: 100%;
	width: auto;
	opacity: 0;
	-moz-opacity: 0;
  filter:progid:DXImageTransform.Microsoft.Alpha(opacity=0);
  margin-top:-30px;
}

#upload-demo{
	width: 250px;
	height: 250px;
  padding-bottom:25px;
}
figure figcaption {
    position: absolute;
    bottom: 0;
    color: #fff;
    width: 100%;
    padding-left: 9px;
    padding-bottom: 5px;
    text-shadow: 0 0 10px #000;
}
</style>
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
        <div class="row">
            <div class="col-md-9">
            <?php echo form_open_multipart();?>
                <div class="box">
                    <div class="box-body">
                    <!-- - -->
                    <div class="row">
                        <div class="col-md-3">
                            <label for="" class="control-label">Username</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control"   value="<?php echo $user->username ?>" readonly />
                        </div>
                    </div>
                    <!--  -->
                    <!-- - -->
                    <div class="row">
                        <div class="col-md-3">
                            <label for="" class="control-label">Email</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control"   value="<?php echo $user->email ?>" readonly />
                        </div>
                    </div>
                    <!--  -->
                    <!-- - -->
                    <div class="row">
                        <div class="col-md-3">
                            <label for="" class="control-label">Nama Depan</label>
                        </div>
                        <div class="col-md-8">
                            <?php echo form_input( $first_name ) ?>
                            <span style="color:red"><?php echo form_error("first_name"); ?></span>
                        </div>
                    </div>
                    <!--  -->
                    <!-- - -->
                    <div class="row">
                        <div class="col-md-3">
                            <label for="" class="control-label">Nama Belakang</label>
                        </div>
                        <div class="col-md-8">
                            <?php echo form_input( $last_name ) ?>
                            <span style="color:red"><?php echo form_error("last_name"); ?></span>
                        </div>
                    </div>
                    <!--  -->
                   
                    <!-- - -->
                    <div class="row">
                        <div class="col-md-3">
                            <label for="" class="control-label">No Telp</label>
                        </div>
                        <div class="col-md-8">
                            <?php echo form_input( $phone ) ?>
                            <span style="color:red"><?php echo form_error("phone"); ?></span>
                        </div>
                    </div>
                    <!--  -->
                    </div>
                </div>
                <div class="box">
                    <div class="box-header">
                        edit password
                    </div>
                    <div class="box-body">
                        <!-- - -->
                        <div class="row">
                            <div class="col-md-3">
                                <label for="" class="control-label">Password lama </label>
                            </div>
                            <div class="col-md-8">
                                <?php echo form_input( $old_password ) ?>
                                <span style="color:red"><?php echo form_error("old_password"); ?></span>
                            </div>
                        </div>
                        <!--  -->
                        <!-- - -->
                        <div class="row">
                            <div class="col-md-3">
                                <label for="" class="control-label">Password</label>
                            </div>
                            <div class="col-md-8">
                                <?php echo form_input( $password ) ?>
                                <span style="color:red"><?php echo form_error("password"); ?></span>
                            </div>
                        </div>
                        <!--  -->
                        <!-- - -->
                        <div class="row">
                            <div class="col-md-3">
                                <label for="" class="control-label">konfirasi Password</label>
                            </div>
                            <div class="col-md-8">
                                <?php echo form_input( $password_confirm ) ?>
                                <span style="color:red"><?php echo form_error("password_confirm"); ?></span>
                            </div>
                        </div>
                        <!--  -->
                    </div>
                </div>
                <div class="box">
                    <div class="box-body">
                        <button type="submit" class="btn  pull-right btn-success">Simpan</button>
                    </div>
                </div>
            <?php echo form_close()?>
            </div>
            <div class="col-md-3">
                <div class="box box-primary">
                    <div class="box-body box-profile">
                    <label class="cabinet center-block">
                        <figure>
                            <img src="<?php echo base_url('assets/cropie/dummy_user.png') ?>" id="item-img-output" class="gambar profile-user-img img-responsive img-box" >
                            <figcaption><i class="fa fa-camera"></i></figcaption>
                        </figure>
                            <input type="file" class="item-img file center-block" name="file_photo"/>
                    </label>
                    <img class="profile-user-img img-responsive img-box" src="<?php  echo $a =  ( empty($user->image) ) ?  base_url(FAVICON_IMAGE)  : base_url('uploads/users_photo/').$user->image ?>" >   
                    <br>
                        <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#ubah_foto">
                            Ubah Foto
                        </button>
                        <div class="modal fade" id="ubah_foto"  role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <?php echo form_open_multipart("user/profile/upload_photo");?>
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Ubah Foto</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    File <input type="file" name="user_image" size="20" />
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-success">Upload</button>
                                </div>
                                </div>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                    
                    </div>
                </div>
            </div>
            <!--  -->
            <div class="col-md-3">
                <div class="box">
                    <div class="box-body">
                        <?php echo form_open_multipart("user/profile/upload_photo");?>
                            <!--  -->
                            <div class="row">
                                <div class="col-xs-12">
                                    <label class="cabinet center-block">
                                        <figure>
                                            <img src="<?php  echo $a =  ( empty($user->image) ) ?  base_url('assets/cropie/dummy_user.png')  : base_url('uploads/users_photo/').$user->image ?> " id="item-img-output" class="gambar img-responsive img-thumbnail" >
                                            <figcaption><i class="fa fa-camera"></i></figcaption>
                                    </figure>
                                        <input type="file" class="item-img file center-block" name="file_photo"/>
                                    </label>
                                </div>
                            </div>
                            <textarea style="display:none" class="form-control" id="image"  name="image" ></textarea>
                            <br>
                            
                            <button type="submit"  class="btn btn-primary btn-block">Ganti Foto</button>
                        <?php echo form_close(); ?>
                    </div>
                </div>
                </div>
            <!--  -->
        </div>
  </section>
</div>


<!--  -->
<div class="modal " id="cropImagePop" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">
                    GAMBAR</h4>
            </div>
            <div class="modal-body">
                <div id="upload-demo" style="width:350px"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="cropImageBtn" class="btn btn-primary">Crop</button>
            </div>
        </div>
    </div>
</div>
<!--  -->
<script src="<?php echo base_url();?>assets/jquery.js"></script>
<script src="<?php echo base_url();?>assets/cropie/croppie.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>assets/cropie/croppie.css">

<script type="text/javascript">
    var $uploadCrop,
    tempFilename,
    rawImg,
    imageId;
    function readFile(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                rawImg = e.target.result;
                $('.upload-demo').addClass('ready');
                $('#cropImagePop').modal('show');
                $uploadCrop.croppie('bind', {
                    url: e.target.result
                }).then(function(){
                    console.log('jQuery bind complete');
                });
            }
            reader.readAsDataURL(input.files[0]);
        }
        else {
            swal("Sorry - you're browser doesn't support the FileReader API");
        }
    }
    
    $uploadCrop = $('#upload-demo').croppie({
        enableExif: true,
        viewport: {
            width: 360/1.8,height: 360/1.8
        },
        boundary: {
                width: 460/1.8,height: 460/1.8
        },
    });
    

    $('.item-img').on('change', function () {
        imageId = $(this).data('id'); tempFilename = $(this).val(); 

        $('#cancelCropBtn').data('id', imageId); 

        readFile(this); 
    });

    $('#cropImageBtn').on('click', function (ev) {
        $uploadCrop.croppie('result', {
            type: 'base64',
            format: 'jpg',
            size: {width: 360/1.7,height: 360/1.7}
        }).then(function (resp) {
            $('#item-img-output').attr('src', resp);
            $('#image').val(resp);
            $('#cropImagePop').modal('hide');
        });
    });
    // End upload preview image
    
</script>


