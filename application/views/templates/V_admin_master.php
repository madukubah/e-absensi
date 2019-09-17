<?php defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('templates/_admin_parts/head');
$this->load->view('templates/_admin_parts/header');
$this->load->view('templates/_admin_parts/sidebar_menu');
?>
<?php echo $the_view_content; ?>
<?php $this->load->view('templates/_admin_parts/footer'); ?>
