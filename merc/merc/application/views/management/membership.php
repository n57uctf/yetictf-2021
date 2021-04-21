<script>var baseURL= "<?php echo base_url();?>";</script>
<div class="container-fluid">
  <div class="row">
    <main class="col-md-9 ms-sm-auto col-lg-12 px-md-4">
      <?php 

      if ($this->session->userdata('member')){
        echo '<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">You\'re a member!</h1>
      </div>
      <h3 class="h4">You have an access to Advanced Privileged Premium Member features:</h3>
    <ul>
      <li>Higher transaction limit</li>
      <li>Ability to withdraw bucks-funds (not implemented yet, but soon will be, for sure)</li>
      <li>Easier calculations</li>
      <li>Access to members club</li>
    </ul>
 <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom"></div>
    <h3 class="h4">To access members club:</h3>
    <a class="btn btn-primary"  style="width: 170px" href="'; 
    echo base_url();
    echo 'casinoe/frontpage">Enter</a>';
      } else {
      echo '<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Become a member!</h1>
      </div>
      <h3 class="h4">For a small monthly fee you can gain access to Advanced Privileged Premium Member features:</h3>
    <ul>
      <li>Higher transaction limit</li>
      <li>Ability to withdraw bucks-funds (not implemented yet, but soon will be, for sure)</li>
      <li>Easier calculations</li>
      <li>Access to members club</li>
    </ul>
    <div>And many more, in not so distant future!</div>
 <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom"></div>
    <h3 class="h4">Interested? Buy it right now! Any currency accepted:</h3>';
    echo form_open('management/membership');
    echo '<div>Choose currency:</div>
        <p><select id="type" name="type" style="width: 100px">
        <option value="coins">SumCoins</option>
        <option value="links">Cuban-Link</option>
        <option value="rocks">Mineralie</option>
        <option value="bucks">Bucks</option>
        </select></p>';
    echo form_error('type');
    echo '<input id="amount" name="amount" type="number" readonly>';
    echo form_error('amount');
    echo '<button class="btn btn-primary" type="submit" style="width: 170px">Become a member!</button>
    <?php echo form_close();?>';
    }
    ?>

    </main>
    <div><?= $error ?></div>
  </div>
</div>
  <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
  <script src="<?php echo base_url()?>css/dashboard.js"></script>
  </body>
</html>
<script>
  $(document).ready(function() {
     $('#type').change(function(){

            var type = $('#type').val();
      
            $.ajax({
              url:baseURL+'management/membership_prep',
              method:"POST",
              data:{'type':type},
              success:function(result)
              {
               document.getElementById('amount').value = result;
              }
             });
    });
});
</script>