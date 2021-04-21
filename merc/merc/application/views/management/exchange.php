<script src='<?php echo base_url(); ?>js/exchange.js' type='text/javascript' ></script>
<script type='text/javascript'>
var baseURL= "<?php echo base_url();?>";
var today_coins = "<?php echo $this->session->userdata('coins_price')?>";
var today_rocks = "<?php echo $this->session->userdata('rocks_price')?>";
var today_links = "<?php echo $this->session->userdata('links_price')?>";
</script>
<div class="container-fluid">
  <div class="row">
    <main class="col-md-9 ms-sm-auto col-lg-12 px-md-4">
<?php echo form_open('management/exchange'); ?>
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Exchange currency</h1>
      </div>
      <div class="row align-items-start">
      <div>
       <p><select id = "type2" name="type2" style="width: 100px">
        <option value="coins">SumCoins</option>
        <option value="links">Cuban-Link</option>
        <option value="rocks">Mineralie</option>
        
       </select></p>

        <?php echo form_error('type2'); ?>
        <input id ="amount2" name="amount2" type="number" min ="0.01" max ="10" value="0.01" step="0.01" style="width: 100px">
        <?php echo form_error('amount2'); ?>
      </div>
      turn into
      <div>
      <p><select id="type1" name="type1" style="width: 100px">
        <option value="coins">SumCoins</option>
        <option value="links">Cuban-Link</option>
        <option value="rocks">Mineralie</option>
       </select></p>
       <?php echo form_error('type1'); ?>
       <input id ="amount1" name="amount1" readonly style="width: 100px">
      <?php echo form_error('amount1'); ?>
      </div>
      <div>
        <button class="btn btn-primary" type="submit" style="width: 170px">Change</button>
      </div>
    </div>
<?php echo form_close();?>
 <div style="width:75%;">
  </div>
<?php echo form_open('management/purchase'); ?>
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
          <h1 class="h2">Purchase currency</h1>

        </div>

        <p><input type="radio" name="type" value="coins">SumCoins</p>
        <p><input type="radio" name="type" value="links">Cuban-Link</p>
        <p><input type="radio" name="type" value="rocks">Mineralie</p>
        <p><input type="radio" name="type" value="bucks">Bucks</p>
        <?php echo form_error('type'); ?>
        <input name="amount" type="number" min ="0.01" max ="10" value="0.01" step="0.01">
        <?php echo form_error('amount'); ?>
        <button class="btn btn-outline-danger" type="submit" style="width: 170px">Purchase</button>
<?php echo form_close();?>
<?= $error ?>
    </main>
  </div>
</div>
  <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
  <script src="<?php echo base_url()?>css/dashboard.js"></script>
  </body>
</html>
<script src='<?php echo base_url(); ?>js/exchart.js' type='text/javascript' ></script>