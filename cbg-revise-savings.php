<?php
/*
Template Name: Change Based Giving - Revise Savings
*/

get_header(); ?>

	<div id="primary" class="site-content">
		<div id="content" role="main">

			<?php 
			cbg_revise_savings();
			
			while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'content', 'page' ); ?>
				
			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
	</div><!-- #primary -->


<?php get_footer(); ?>

<?php
function cbg_revise_savings() {
	$current_user = wp_get_current_user();
	$user_id = $current_user->ID;

?>	
<p class="pagetitles">REVISE EARLIER SAVINGS</p>
	<table>
		<tr>
			<td>
			<strong>Date:</strong>
			<br /><br />
			<input type="hidden" id="userid" value="<?php echo $user_id; ?>" />	
			<select name="month3" id="month3">
<?php       
			$months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");			
				for ($i=0; $i < count($months); $i++)
				  {
					   $mn = 1 + $i;
					   if (!empty($_GET['themonth'])) {
							   if($mn == intval($_GET['themonth']))
									{
											echo "<option selected value=" . $_GET['monthname'] . ">" . $_GET['monthname'] . "</option> \n";
									}
							   else
									{
											echo "<option value=" . $months[$i] . ">" . $months[$i] . "</option> \n";
									}				   
					   } else {
							   if($mn == date("m"))
									{
											echo "<option selected value=" . $months[$i] . ">" . $months[$i] . "</option> \n";
									}
							   else
									{
											echo "<option value=" . $months[$i] . ">" . $months[$i] . "</option> \n";
									}
						}
				  }
?>       
			</select>	
			<select name="year3" id="year3">
<?php
				for ($i=date('Y'); $i>=2012; $i--) {
					if (!empty($_GET['theyear'])) {
						if ($i == $_GET['theyear']) {
							echo '<option selected value="' . $i . '">' . $_GET['theyear'] . '</option>';
						} else {
							echo '<option value="' . $i . '">' . $i . '</option>';
						} 					
					} else {
						if ($i == date('Y')) {
							echo '<option selected value="' . $i . '">' . $i . '</option>';
						} else {
							echo '<option value="' . $i . '">' . $i . '</option>';
						}                
					}
				}
?>			
			</select>	
			<br /><br /><br />
			</td>
		</tr>
		<tr>
			<td>
				<strong>Savings Category:</strong><br /><br />

				<input type="radio" name="savings_category" value="cooling" checked>Cooling<br />
				<input type="radio" name="savings_category" value="entertainmentrecreation">Entertainment/Recreation<br />
				<input type="radio" name="savings_category" value="grocery-shopping">Grocery Shopping<br />
				<input type="radio" name="savings_category" value="heating">Heating<br />
				<input type="radio" name="savings_category" value="lighting">Lighting<br />
				<input type="radio" name="savings_category" value="transportation">Transportation<br />
				<input type="radio" name="savings_category" value="water">Water<br />				
				<br /><br /><br />
			</td>
		</tr>
		<tr>
			<td>
				<input type="button" id="lookup_savings" value="Lookup Savings" />
			</td>
		</tr>
	</table>
	<br /><br />
	<span id="old_savings"></span>
	
<?php	
	

	
	?>
	<script type="text/javascript">
		jQuery(document).ready(function ($) {
			$("#lookup_savings").click(function(event){
			  var data = {
				action: 'my_lookup',
				security : MyAjax.security,
				month: $("#month3").val(), 
				year: $("#year3").val(), 
				savings_category: $("input[type='radio'][name='savings_category']:checked").val(),
				userid: $("#userid").val()
			  };
			 
			  // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			  $.post(MyAjax.ajaxurl, data, function(response) {	
					$("#old_savings").html("Amount Saved in " + $("#month3").val() + ", " + $("#year3").val() + " : <strong>" + response + "</strong>");
			  });
			});	
		});
	</script
<?php	
}