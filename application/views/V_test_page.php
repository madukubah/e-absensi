<div id="infoMessage"><?php echo $message;?></div>

<?php echo form_open_multipart("");?>

      <p>
            <?php echo form_input($id_category);?>
      </p>
      <p>
            <?php echo form_input($category_name);?>
      </p>
      <p>
            <?php echo form_input($category_description);?>
      </p>

      <p><?php echo form_submit('submit',  "kirim" );?></p>

<?php echo form_close();?>
