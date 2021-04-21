
  <?php echo form_open('account/login'); ?>  
  <center>
	   <label for="inputEmail" class="sr-only">Email address</label>
 		   <input type="text" id="inputEmail" class="form-control" name="login" placeholder="Login" style="width: 240px">
       <?php echo form_error('login'); ?>
  	 <label for="inputPassword" class="sr-only">Password</label>
  		<input type="password" id="inputPassword" class="form-control" name="passwd" placeholder="Password" style="width: 240px">
      <?php echo form_error('passwd'); ?>
    <br></br>
      <button class="btn btn-primary" type="submit"  style="width: 170px">Log In</button>
  	<div class="checkbox mb-3">
    	<label>
     		<input type="checkbox" value="remember-me"> Remember me
    	</label>
  	</div>
  	<a href="<?php echo base_url();?>account/register">Not a member? Register now!</a>
  </center>
  <?php echo form_close(); ?> 