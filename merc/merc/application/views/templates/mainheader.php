    <html lang="en">
  <head>
    <title><?= $title ?></title>
    <link sizes="16x16" rel="icon" type="image/png" href="<?php echo base_url(); ?>images/logo.png"/>
    <link href="<?php echo base_url();?>css/bootstrap.css" rel="stylesheet">
    <link href="<?php echo base_url();?>css/dashboard.css" rel="stylesheet">
    <link href="<?php echo base_url();?>css/datatables.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url();?>js/datatables.min.js"></script>
  </head>
  <body>
    
<header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
  <a class="navbar-brand col-md-3 col-lg-2 me-0 px-5" href="<?php echo base_url(); ?>pages/view/mainpage">M.E.R.C.</a>
  <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <nav class="container d-flex flex-column flex-md-row justify-content-between">
    <a class="py-2 d-none d-md-inline-block" href="<?php echo base_url(); ?>management/calculations"><span data-feather="dollar-sign"></span>
              Calculations</a>
    <a class="py-2 d-none d-md-inline-block" href="<?php echo base_url(); ?>management/transactions"><span data-feather="briefcase"></span>
              Transactions</a>
    <a class="py-2 d-none d-md-inline-block" href="<?php echo base_url(); ?>management/exchange"><span data-feather="trending-down"></span>
              Exchange</a>
    <a class="py-2 d-none d-md-inline-block" href="<?php echo base_url(); ?>management/membership"><span data-feather="users"></span>
              Membership</a>
    <a class="py-2 d-none d-md-inline-block" href="<?php echo base_url(); ?>management/contact_us"><span data-feather="file-text"></span>
              Contact us</a>
  </nav>
  <ul class="navbar-nav px-5">
    <li class="nav-item text-nowrap">
      <a class="nav-link" href="<?php echo base_url()?>account/logout">Sign out</a>
    </li>
  </ul>
</header>