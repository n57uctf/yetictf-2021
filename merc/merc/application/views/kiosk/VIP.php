	
	  <main class="px-3" style="background-color: rgba(94, 94, 243, 0.7)">
	    <h1 style="color: rgba(248, 100, 250,1)">Manager required</h1>

	    <?php 

	    if ($mail){

	    	echo "<h4 style=\"color: rgba(250, 182, 200,1)\">We're reviewing your application right now. Wait for more information at your email soon.</h4> 
	    	<h4 class=\"message\" style=\"color: rgba(250, 100, 100,1)\">";
	    	echo $mail;
	    	echo "</h4>";


	    } else {

	    echo "<p class=\"message\" style=\"color: rgba(250, 182, 200,1)\">At the moment, we're in a desperate need for a person, who would further develop and run this establishment. Feel free to send us an Email with your portfolio and contact info.</p>";
	    echo form_open('casinoe/VIP_page');
	    echo "<div>
	    <input id =\"email\" name=\"email\" placeholder=\"Your Contact Email\" style=\"width: 200px\">";
	    echo form_error('email');
	    echo "</div>
		<div>
	    <textarea id=\"message\" name=\"message\"
		  style=\"padding-bottom:250px ;
		  font-size:16px;
		  height: 300px;
		  width: 500px;\" ></textarea>
		</div>";
		echo form_error('message');
		echo "<div><button class=\"btn btn-primary\" type=\"submit\"  style=\"width: 170px\">Send</button></div>";
		echo form_close();

		}
		?>

	  </main>
	  <footer class="mt-auto text-white-50" style="
	  margin-left: auto;
	  margin-right: auto;
	  width: 25%;">
	    <p>Powered by <a href="<?php echo base_url();?>" class="text-white">M.E.R.C.</a></p>
	  </footer>
	  </div>

  </body>
</html>
