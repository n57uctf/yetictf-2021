<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap.css">
    <title><?= $title ?></title>
    <link sizes="16x16" rel="icon" type="image/png" href="<?php echo base_url(); ?>images/logo2.png"/>
</head>

 <body class="d-flex h-100 text-center text-white bg-dark">
    <div style="background-image: url(<?php echo base_url(); ?>images/casinoe.png);
  filter:blur(4px);
  background-position: bottom;
  background-repeat: no-repeat;
  background-size: cover;
  height: 100vh;
  width: 100%;"></div>

	<div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column" style="position:absolute;">
	  <header class="mb-auto">
	    <div style="background-color: rgba(94, 94, 243, 0.7); width: 100%;">
	      <h3 class="float-md-start mb-0" style="color: rgba(248, 100, 250,1)">Kiosk</h3>
	      <nav class="nav nav-masthead justify-content-center float-md-end">
	        <a class="nav-link active" aria-current="page" href="<?php echo base_url();?>casinoe/account" style="color: rgba(248, 195, 194,1)">Account</a>
	        <a class="nav-link" href="<?php echo base_url();?>casinoe/plays" style="color: rgba(248, 195, 194,1)">Plays</a>
	        <a class="nav-link" href="<?php echo base_url();?>casinoe/VIP_page" style="color: rgba(248, 195, 194,1)">VIP</a>
	      </nav>
	    </div>
	  </header>
