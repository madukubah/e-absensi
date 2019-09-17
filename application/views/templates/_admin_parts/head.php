<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="<?php echo APP_DESC ?>" />
    <meta name="author" content="<?php echo APP_AUTHOR ?>">

    <title><?php echo APP_NAME ?></title>

    <link rel="shortcut icon" type="image/png" href="<?php echo base_url() . FAVICON_IMAGE; ?>" />

    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/') ?>plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/') ?>plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/') ?>plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/') ?>plugins/jqvmap/jqvmap.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/') ?>dist/css/adminlte.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/') ?>plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/') ?>plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="<?= base_url('assets/') ?>plugins/summernote/summernote-bs4.css">

    <link rel="stylesheet" href="<?= base_url('assets/') ?>plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">

    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini layout-fixed" onload="menuActive('<?php echo $this->router->fetch_class() . '_' . $this->router->fetch_method() ?> ')" >
<div class="wrapper">