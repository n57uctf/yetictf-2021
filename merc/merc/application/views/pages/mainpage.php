<script src='<?php echo base_url(); ?>js/chart.min.js' type='text/javascript' ></script>
<script src='<?php echo base_url(); ?>js/utils.js' type='text/javascript' ></script>
<script type='text/javascript'>
var baseURL= "<?php echo base_url();?>";
var today_coins = "<?php echo $this->session->userdata('coins_price')?>";
var today_rocks = "<?php echo $this->session->userdata('rocks_price')?>";
var today_links = "<?php echo $this->session->userdata('links_price')?>";
</script>
<div class="container-fluid">
  <div class="row">
    <main class="col-md-9 ms-sm-auto col-lg-12 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Account info</h1>
      </div>
      <h2 class="display-6">Balance</h2>
      <h3>Here's your funds, <?php echo $this->session->userdata('login')?>. Invest them wisely!</h3>
      <div class="row align-items-start">
      <div>
      <dl class="row">
        <dt class="col-sm-3">SumCoins: </dt>
          <dd class="col-sm-9" id="coins"><?php echo $this->session->userdata('usr_coins')?></dd>
        <dt class="col-sm-3">Cuban-Link coins: </dt>
          <dd class="col-sm-9" id="links"><?php echo $this->session->userdata('usr_links')?></dd>
        <dt class="col-sm-3">Mineralie coins: </dt>
          <dd class="col-sm-9" id="rocks"><?php echo $this->session->userdata('usr_rocks')?></dd>
        <dt class="col-sm-3" style="color:green">Bucks, pure and evergreen: </dt>
          <dd class="col-sm-9" id="bucks"><?php echo $this->session->userdata('usr_bucks')?></dd>
      </dl>
      </div>
    </div>
      <h3>Buy some of our freshly-mined cryptocurrency, stable as ever!</h3>
      <canvas id="mycanvas" style="width:150px !important; height:25px !important;"></canvas>
        <a class="btn btn-primary"  style="width: 170px" href="<?php echo base_url();?>management/exchange">Exchange</a>
    </main>
  </div>
</div>
  <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script><script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script><script src="<?php echo base_url()?>css/dashboard.js"></script>
  </body>
</html>
<script src='<?php echo base_url(); ?>js/exchart.js' type='text/javascript' ></script>