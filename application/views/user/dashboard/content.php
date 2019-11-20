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

<!-- Main content -->
<section class="content">

  <div class="row"> 
  <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-yellow">
        <div class="inner">
          <h3><?php echo $page_title ?></h3>

          <p>Data Testing</p>
        </div>
        <div class="icon">
          <i class="ion ion-person"></i>
        </div>
        <a href="" class="small-box-footer">Lihat <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-primary">
        <div class="inner">
          <h3><?php echo $page_title ?><sup style="font-size: 20px"></sup></h3>

          <p>Data Uji</p>
        </div>
        <div class="icon">
          <i class="ion ion-person"></i>
        </div>
        <a href="" class="small-box-footer">Lihat <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    
    <!-- ./col -->
    <!-- <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-red">
        <div class="inner">
          <h3>65</h3>

          <p>Tidak diterima</p>
        </div>
        <div class="icon">
          <i class="fa fa-hand-paper-o"></i>
        </div>
        <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div> -->
    <!-- ./col -->
  </div>
  </section>
<!-- /.content -->
</div>


<script type="text/javascript">
  $(document).ready(function() {
    function sync_all( fingerprint_ids ) {
      // var fingerprint_id = $("#fingerprint").val();
      // fingerprint_ids = jQuery.parseJSON( fingerprint_ids );
      $.each( fingerprint_ids, function( id ) {
        console.log( id )
            $.ajax({
                url: "<?= site_url() ?>api/attendance/sync/"+fingerprint_ids[0],
                success: function(result) {
                // result = jQuery.parseJSON( result );
                console.log(result.message);
                }
            });
        });
    //   console.log(fingerprint_ids[0]);
    }
    // setInterval(function(){  sync(); }, 1000 *  );
    sync_all( <?php echo json_encode( $fingerprint_ids ) ?> );
    console.log( "asdfd" );
  });
</script>