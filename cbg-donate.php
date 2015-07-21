<?php
/*
Template Name: Change Based Giving - Donate
*/
?>
<div id="pageheader" class="titleclass">
	<div class="container">
		<?php get_template_part('templates/page', 'header'); ?>
	</div><!--container-->
</div><!--titleclass-->
	
<div id="content" class="container">
   	<div class="row">
     	<div class="main <?php echo esc_attr(kadence_main_class()); ?>" role="main">
				<?php 
				cbg_donate();
				get_template_part('templates/content', 'page'); ?>
				<?php global $virtue; 
					if(isset($virtue['page_comments']) && $virtue['page_comments'] == '1') {
						comments_template('/templates/comments.php');
					} 
					
					?>
		</div><!-- /.main -->

<?php
function cbg_donate() {
?>
<h4>Select a Community for your Donation:</h4>
<select id="donate_community">  
	<option value="">---Select---</option>
	<option value="mathare">Mathare Neighborhood, Nairobi, Kenya</option>

	
</select>
<br/><br/>

<div id="mathare_btn" style="display:none;">
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
	<input type="hidden" name="cmd" value="_s-xclick">
	<input type="hidden" name="hosted_button_id" value="RF9USTMM8NPQL">
	<input type="hidden" name="custom" value="mathare">
	<input type="image" src="http://www.change-based-giving.org/cbg/wp-content/uploads/2015/05/donatenowbutton.png" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
	<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
	</form>
</div>
<div id="kolkata_btn" style="display:none;">
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
	<input type="hidden" name="cmd" value="_s-xclick">
	<input type="hidden" name="hosted_button_id" value="JG3HCDDY3QN3A">
	<input type="hidden" name="custom" value="kolkata">
	<input type="image" src="http://www.change-based-giving.org/cbg/wp-content/uploads/2015/05/donatenowbutton.png" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
	<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
	</form>
</div>
<div id="sanjosepalmas_btn" style="display:none;">
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
	<input type="hidden" name="cmd" value="_s-xclick">
	<input type="hidden" name="hosted_button_id" value="E8CHQ6WKMPLQ6">
	<input type="hidden" name="custom" value="sanjosepalmas">
	<input type="image" src="http://www.change-based-giving.org/cbg/wp-content/uploads/2015/05/donatenowbutton.png" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
	<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
	</form>
</div>
<div id="test_btn" style="display:none;">
	<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top">
	<input type="hidden" name="cmd" value="_s-xclick">
	<input type="hidden" name="hosted_button_id" value="T4P6ZD5DKJYVL">
	<input type="hidden" name="custom" value="lima">
	<input type="image" src="http://www.change-based-giving.org/cbg/wp-content/uploads/2015/05/donatenowbutton.png" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
	<img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
	</form>
</div>
<br /><br />
This site uses PayPal to collect donations. To use a credit card look for the link as shown in the image below.<br/><br/>
<img src="http://www.change-based-giving.org/cbg/wp-content/uploads/2015/06/paypal_cc.jpg" alt="PayPal Credit Card Link" /><br/><br/>
Change Based Giving is a registered 501(c)(3) non-profit organization. All donations are tax-exempt. PayPal charges non-profits 2.2% plus $.30 for processing.  Change Based Giving keeps 7% for administrative costs. <i>That means over 90% of every donation goes directly to the project the user designates! </i>
<script>
jQuery( document ).ready(function($) {
    $( "#donate_community" ).change(function() {
		if ($("#donate_community").val() == "kolkata") {
			$("#kolkata_btn").show();
			$("#mathare_btn").hide();
			$("#sanjosepalmas_btn").hide();
			$("#test_btn").hide();
		}
		if ($("#donate_community").val() == "mathare") {
			$("#mathare_btn").show();
			$("#kolkata_btn").hide();
			$("#sanjosepalmas_btn").hide();
			$("#test_btn").hide();
		}
		if ($("#donate_community").val() == "sanjosepalmas") {
			$("#sanjosepalmas_btn").show();
			$("#mathare_btn").hide();
			$("#kolkata_btn").hide();	
			$("#test_btn").hide();		
		}		
		if ($("#donate_community").val() == "testacct") {
			$("#sanjosepalmas_btn").hide();
			$("#mathare_btn").hide();
			$("#kolkata_btn").hide();	
			$("#test_btn").show();		
		}			
	});
});
</script>
<?php
}