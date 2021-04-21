
<div class="container-fluid">
  <div class="row">
    <main class="col-md-9 ms-sm-auto col-lg-12 px-md-4">
<?php echo form_open('management/calculations'); ?>
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Mine currency</h1>
      </div>
      <div class="row align-items-start">

      <h4>Actually, our blockchain technology is still under development, but for sake of giving a chance at prosperity for aspiring individuals, you can help us with some calculations. All you need to do, is to solve this simple hash problem and we're good. Choose desired currency and mine away!</h4>

      <div>
       <p><select name="type">
        <option value="coins">SumCoins</option>
        <option value="links">Cuban-Link</option>
        <option value="rocks">Mineralie</option>
       </select></p>
        <?php echo form_error('type'); ?>
        <?php echo $task ?>
        <?php echo form_error('calcres'); ?>
        <input name="calcres" type="number" step="0.01">
        <button class="btn btn-primary" type="submit" style="width: 170px">Calculate</button>
      </div>
    </div>
<?php echo form_close();?>
    </main>
  </div>
</div>
  <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script><script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script><script src="<?php echo base_url()?>css/dashboard.js"></script>
  </body>
</html>