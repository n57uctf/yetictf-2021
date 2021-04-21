
<div class="container-fluid">
  <div class="row">
    <main class="col-md-9 ms-sm-auto col-lg-12 px-md-4">
<?php echo form_open('management/transactions'); ?>
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Transfer currency to other account</h1>
      </div>
      <div class="row align-items-start">
      <div>

       <p><select id = "type" name="type" style="width: 100px">
        <option value="coins">SumCoins</option>
        <option value="links">Cuban-Link</option>
        <option value="rocks">Mineralie</option>
        
       </select></p>

        <?php echo form_error('type'); ?>
        <input id ="amount" name="amount" type="number" min ="0.01" max ="10" value="0.01" step="0.01" style="width: 100px">
        <?php echo form_error('amount'); ?>
      </div>
      send to
      <div>
       <input id ="recv_login" name="recv_login" style="width: 100px">
        <?php echo form_error('recv_login'); ?>
      </div>
      <div>
        with this message
        <input id ="message" name="message" style="width: 200px">
      </div>
        <?php echo form_error('message'); ?>
        <button class="btn btn-primary" type="submit" style="width: 170px">Transfer</button>
      </div>
    </div>
<?php echo form_close();?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Transactions history</h1>
      </div>
<h4 class="h4">Outgoing transactions</h4>
<table id ="outgoing_tbl" class="table table-striped">
  <thead>
  <tr>
    <th scope="info" style="width:20%">Date</th>
    <th scope="info" style="width:15%">To whom</th>
    <th scope="info" style="width:10%">Amount</th>
    <th scope="info" style="width:10%">Currency</th>
    <th scope="msg" style="width:40%">Message</th>
  </tr>
  </thead>
  <tbody>
  <?php 
        $dir = "transactions/".$this->session->userdata('usr_hash')."/outgoing";
        $receipts = get_filenames($dir);
        $row = 0;
      
        if (!empty($receipts)){

          foreach ($receipts as $receipt){
            $row = $row + 1;
            $message = read_file("".$dir."/".$receipt."");
            $msg_parts = explode("\n",$message);
            $details = explode(" ",$msg_parts[1]);

            echo "<tr><td>".$details[6]."</td><td>".$details[4]."</td><td>".$details[1]."</td><td>".$details[2]."</td><td>".$msg_parts[0]."</td></tr>";
          }
        }else {
          echo "<tr><td>No outgoing transactions yet :(<td></tr>";
        }
  ?>
  </tbody>
</table>
<h4 class="h4">Incoming transactions</h4>
<table id ="incoming_tbl" class="table table-striped">
  <thead>
  <tr>
    <th scope="info" style="width:20%">Date</th>
    <th scope="info" style="width:15%">From whom</th>
    <th scope="info" style="width:10%">Amount</th>
    <th scope="info" style="width:10%">Currency</th>
    <th scope="msg" style="width:40%">Message</th>
  </tr>
  </thead>
  <tbody>
  <?php 
        $dir = "transactions/".$this->session->userdata('usr_hash')."/incoming";
        $receipts = get_filenames($dir);
        $row = 0;
      
        if (!empty($receipts)){

          foreach ($receipts as $receipt){
            $row = $row + 1;
            $message = read_file("".$dir."/".$receipt."");
            $msg_parts = explode("\n",$message);
            $details = explode(" ",$msg_parts[1]);

            echo "<tr><td>".$details[6]."</td><td>".$details[4]."</td><td>".$details[1]."</td><td>".$details[2]."</td><td>".$msg_parts[0]."</td></tr>";
          }
        } else {
          echo "<tr><td>No incoming transactions yet :(<td></tr>";
        }
  ?>
  </tbody>
</table>
    </main>
  </div>
</div>
  <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
  <script src="<?php echo base_url()?>css/dashboard.js"></script>
  </body>
</html>
<script>
$(document).ready(function() {
  $("#outgoing_tbl").DataTable();
  $("#incoming_tbl").DataTable();
});
</script>

