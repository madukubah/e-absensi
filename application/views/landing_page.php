<!-- Promo Block -->
  <div class="col-md-12" style="height:100vh; background: #f15f79;
      background: linear-gradient(to bottom, #f15f79 0%,#b24592 100%);">
      <div class="content" style="color:white;padding-top:200px; text-align:center">
        <h1>SERVICE ATTENDANCE!</h1>
      </div>
  </div>
<!-- End Promo Block -->

<script type="text/javascript">
  $(document).ready(function() {
    function sync_all( fingerprints ) {
      $.each( fingerprints, function( index ) {
        console.log( fingerprints[index].name + " " + fingerprints[index].id + + " " + fingerprints[index].ip_address )
            $.ajax({
                url: "<?= site_url() ?>api/attendance/sync/"+fingerprints[index].id,
                success: function(result) {
                // result = jQuery.parseJSON( result );
                console.log( "<?= site_url() ?>api/attendance/sync/"+fingerprints[index].id + " "+ fingerprints[index].name + " "+result.message);
                }
            });
        });
    }

    setInterval(function(){  sync_all( <?php echo json_encode( $fingerprints ) ?> ); }, 1000 * 3600 * 3 );
    // sync_all( <?php //echo json_encode( $fingerprints ) ?> );
  });
</script>