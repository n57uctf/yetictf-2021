	
<?php echo form_open('account/register'); ?>  
  <center>
 
		<div class="col-md-4 col-md-offset-4">
			
				<input type="text" class="form-control" name="login" placeholder="Login" value="<?php echo set_value('login'); ?>" style="width: 240px">
				<?php echo form_error('login'); ?>
				<input type="password" class="form-control" name="passwd" placeholder="Password" style="width: 240px">
				<?php echo form_error('passwd'); ?>
				<input type="password" class="form-control" name="passwd2" placeholder="Confirm Password" style="width: 240px">
				<?php echo form_error('passwd2'); ?>
			<button class="btn btn-primary" type="submit"  style="width: 170px">Register</button>
			<br></br>
			<div>
  			<a href="<?php echo base_url();?>account/login">Have an account? Log in now!</a>
  		</div>
		</div>
	
</center>
<?php echo form_close(); ?>  
