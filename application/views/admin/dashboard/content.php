<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Daftar OPD Kabupaten Kolaka Timur</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Dashboard v1</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- Small boxes (Stat box) -->
      <div class="row justify-content-center">
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-info">
            <div class="inner">
              <h3><?= $badan ?></h3>

              <p>OPD Badan</p>
            </div>
            <div class="icon">
              <!-- <i class="ion ion-bag"></i> -->
            </div>
            <a href="<?= base_url('user/home/opd_category/2') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-1"></div>
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-success">
            <div class="inner">
              <h3><?= $dinas ?></h3>

              <p>OPD Dinas</p>
            </div>
            <div class="icon">
              <!-- <i class="ion ion-stats-bars"></i> -->
            </div>
            <a href="<?= base_url('user/home/opd_category/3') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-1"></div>
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-warning">
            <div class="inner">
              <h3><?= $sekretariat ?></h3>

              <p>OPD Sekretariat</p>
            </div>
            <div class="icon">
              <!-- <i class="ion ion-person-add"></i> -->
            </div>
            <a href="<?= base_url('user/home/opd_category/4') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
      <!-- /.row -->
      <!-- Main row -->

      <!-- /.row (main row) -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>

<script type="text/javascript">
  $(document).ready(function() {
    function sync_all( fingerprint_ids ) {
      $.each( fingerprint_ids, function( id ) {
        console.log( fingerprint_ids[id] )
            $.ajax({
                url: "<?= site_url() ?>api/attendance/sync/"+fingerprint_ids[id],
                success: function(result) {
                // result = jQuery.parseJSON( result );
                console.log( "<?= site_url() ?>api/attendance/sync/"+fingerprint_ids[id] + " " +result.message);
                }
            });
        });
    }

    function sync_all_( fingerprint_ids ) {
        $.ajax({
            url: "<?= site_url() ?>api/attendance/sync_all",
            success: function(result) {
            // result = jQuery.parseJSON( result );
            console.log( result.message);
            }
        });
    }
    // setInterval(function(){  sync(); }, 1000 *  );
    sync_all( <?php echo json_encode( $fingerprint_ids ) ?> );
  });
</script>