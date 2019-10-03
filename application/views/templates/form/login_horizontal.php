<?php
if ($this->session->flashdata('alert')) {
    echo '<script>
    $(function () {
  
      alert("Login Gagal");
      
    })
  </script>';
} ?>
    <?= form_open()?>
        <div class="row mb-2 ">
            <div class="col">
                <?= $form ?>
            </div>
            <div class="col-4" style="margin-top:3px">
                <button type="submit" class="btn btn-primary btn-sm" data-toggle="modal" style="margin-left: 5px;">
                    Login
                </button>
            </div>
        </div>
    </form>
    <!-- - -->
