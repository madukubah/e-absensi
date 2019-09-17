
<?php
$data = (isset($data) && $data != NULL) ? $data : '';
$data_param = ($data != '') ? (isset($data->$param) ? $data->$param : '')   : '';
?>
<button type="button" class="btn btn-<?= $button_color; ?> btn-sm" data-toggle="modal" style="margin-left: 5px;" data-target="#<?= $modal_id . $data_param; ?>">
    <?= $name; ?>
</button>
<!-- Modal Delete-->
<div class="modal fade" id="<?php echo $modal_id . $data_param ?>" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <?php echo form_open($url); ?>
            <div class="modal-header">
                <h5 class="modal-title"><?php echo $name ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!--  -->

                <?php
                $_data["form_data"] = $form_data;
                $_data["data"] = $data;
                $this->load->view('templates/form/plain_form', $_data);
                ?>
                <!--  -->
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn  btn-success">Ok</button>
                <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Batal</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<!--  -->