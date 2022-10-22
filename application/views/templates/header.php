<!DOCTYPE html>
<html lang="nl" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $title; ?></title>
	<!-- <link href="<?php // echo base_url('assets/css/' . $this->settings->getSiteValue('template') . '/main.min.css'); ?>" rel="stylesheet"> -->
	<link href="<?php echo base_url('assets/css/main.min.css'); ?>" rel="stylesheet">
    <script src="<?php echo base_url('assets/js/jquery-3.3.1.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/js/feather.min.js'); ?>"></script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <?php if(isset($css_gallery)) : ?>
    <link href="<?php echo base_url('assets/css/cssbox.css'); ?>" rel="stylesheet">
    <script src="<?php echo base_url('assets/js/jquery.lazy.min.js'); ?>"></script>
    <?php endif; ?>
    <?php if(isset($css_dropzone)) : ?>
    <link href="<?php echo base_url('assets/css/dropzone.css'); ?>" rel="stylesheet">
    <script src="<?php echo base_url('assets/js/dropzone.js'); ?>"></script>
    <?php endif; ?>
    <?php if(isset($css_fullcalendar)) : ?>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/fullcalendar/core/main.css') ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/css/fullcalendar/daygrid/main.css') ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/css/fullcalendar/timegrid/main.css') ?>" />
    <script src="<?php echo base_url('assets/js/fullcalendar/core/main.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/js/fullcalendar/core/nl.js') ?>"></script>
    <script src="<?php echo base_url('assets/js/fullcalendar/daygrid/main.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/js/fullcalendar/timegrid/main.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/js/fullcalendar/interaction/main.min.js') ?>"></script>

    <!-- Datetime period picker  https://www.daterangepicker.com/ -->
    <script type="text/javascript" src="<?php echo base_url('assets/js/fullcalendar/moment/moment.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/js/daterangepicker.min.js') ?>"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/daterangepicker.css') ?>" />
    <?php endif; ?>
</head>

<body class="h-100 bg-light">
<?php 
    // echo '<pre>';
    // var_dump($this->session->userdata);
    // echo '</pre>';
?>
    <!-- wrapper -->
    <div id="wrapper" class="d-flex flex-column h-100">
        <?php $this->load->view('templates/nav'); ?>
