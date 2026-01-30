<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="icon" type="image/png" href="<?php echo base_url(); ?>assets/img/favicon.png">
    <title><?php echo $title;?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
   <link href="<?php echo base_url(); ?>assets/font-awesome/css/font-awesome.css" rel="stylesheet">
	<!-- <link href="<?php echo base_url(); ?>assets/css/plugins/chosen/chosen.css" rel="stylesheet"> -->
    <!-- <link href="<?php echo base_url(); ?>assets/css/plugins/toastr/toastr.min.css" rel="stylesheet"> -->
    <!-- <link href="<?php echo base_url(); ?>assets/js/plugins/gritter/jquery.gritter.css" rel="stylesheet"> -->
    <link href="<?php echo base_url(); ?>assets/css/animate.css" rel="stylesheet">
	<!-- <link href="<?php echo base_url(); ?>assets/css/plugins/iCheck/custom.css" rel="stylesheet"> -->
    <link href="<?php echo base_url(); ?>assets/css/style.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet">
	<!-- <link href="<?php echo base_url(); ?>assets/css/plugins/toastr/toastr.min.css" rel="stylesheet"> -->
	<link href="<?php echo base_url(); ?>assets/css/custom.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>assets/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
	<!-- <link href="<?php echo base_url(); ?>assets/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>assets/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>assets/css/plugins/select2/select2.min.css" rel="stylesheet">  -->
	<!-- <link href="<?php echo base_url(); ?>assets/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet"> -->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css" rel="stylesheet">
	<script src="<?php echo base_url(); ?>assets/js/jquery-3.1.1.min.js"></script>
	<script>var baseurl='<?php echo base_url();?>';</script>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/daterangepicker.css" />
	<style>
	body.mini-navbar #page-wrapper
	{
		margin:0 0 0 201px !important;
	}
	body.mini-navbar .navbar-static-side
	{
		width:201px !important;
	}
	body.mini-navbar .navbar-default .nav > li > a 
	{
    font-size: 12px;
    }
	
	.landing-page li.pricing-title
	{
		font-size:16px;
	}
	.landing-page .pricing-plan.selected 
	{
		transform: scale(1.1);
		background: #fff;
	}
	#menu_logo
	{
		margin-top:20px;
	}
	#menu_logo .fa 
	{
		font-size: 40px;
		border: 1px solid #1ab394;
		border-radius: 50%;
		padding: 23px 12px;
		width: 86px;
		height: 86px;
		color:#1ab394;
    }
	</style>

	
</head>
<body style="background-color: #FFF0D1"> 

<!-- Button trigger modal -->
<!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#staticBackdrop">
  Launch static backdrop modal
</button> -->



	<div id="wrapper">

        <div class="row">
        <nav class="navbar navbar-expand-lg navbar-light headerBg" role="navigation">
		
            <a class="navbar-brand logo" href="#">
                <img src="<?php echo base_url()?>assets/img/logoHeader.png" class="img-responsive"> 
            </a>
			
            <div class="collapse navbar-collapse backgroundMenu">
                <ul class="navbar-nav m-auto">
                    <li class="nav-item ">
                        <a class="nav-link " href="<?php echo base_url()?>">Dashboard </a>
                    </li>
                     <li class="nav-item ">
                        <a class="nav-link " href="<?php echo base_url()?>MasterManagement">Master </a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link" href="<?php echo base_url()?>production-planning">Planning Calendar Upload</a>
                    </li> 
                    <li class="nav-item ">
                        <a class="nav-link" href="<?php echo base_url()?>production-planning-calendar">Calendar View</a>
                    </li> 
                    <li class="nav-item ">
                        <a class="nav-link" href="<?php echo base_url()?>production-planning/allocation">Indent Allocation</a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link" href="<?php echo base_url()?>production-planning/dragDrop">Drag Drop</a>
                    </li>
                     <li class="nav-item ">
                        <a class="nav-link" href="<?php echo base_url()?>production-planning/planning-approval">Planning Approval</a>
                    </li>
                </ul>
            </div>

            <ul class="nav navbar-top-links navbar-right">
                <li>
                    <i class="fa fa-user" style="color:#A3243C; font-size:30px;"></i>
                    <!-- <img src="<?php echo base_url()?>assets/img/license.png" class="img-responsive"> -->
                </li>
                <!-- <li>
                    <a class="" style="margin-top:0px" href="<?php echo base_url()?>" ><i class="fa fa-arrow-circle-left"></i> Dashboard</a>	
                </li> -->
            </ul>
        </nav>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            hrf=$("#menuController").val();
            $("a").each(function () {
                if (sel=$(this).is('[href$='+hrf+']')){
                    $(this).closest("li.main_menu").addClass("active");
                    $(this).parentsUntil("main_menu", "li").addClass("active");
                    $(this).parentsUntil("main_menu", "ul").addClass("in");
                }
            });
        });
    </script>
 
