<?php
/*
Template Name: Change Based Giving - Profile Page
*/

get_header(); ?>

	<div id="primary" class="site-content">
		<div id="content" role="main">

			<?php 
			cbg_profile();
			
			while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'content', 'page' ); ?>
				
			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
	</div><!-- #primary -->


<?php get_footer(); ?>

<?php
function cbg_profile() {
if ( is_user_logged_in() ) {

	$current_user = wp_get_current_user();
	$fname = $current_user->user_firstname;
	$lname = $current_user->user_lastname;
	$dispname = $current_user->display_name;
	$showname = "";
	if (!empty($fname) && !empty($lname)) {
		$showname = $fname . " " . $lname;
	} else {
		$showname = $dispname;
	}
		
	$user_id = $current_user->ID;
	
	if (!empty($_GET['themonth'])) {
		$currentmonth = intval($_GET['themonth']);
	} else {
		$currentmonth = intval(date('m'));
	}
	
	//echo "MONTH=" . $currentmonth;
	
	//GET POSTS BY CURRENT MONTH
	$args = array(
		'author' =>  $user_id,
		'post_type' => 'saving',
		'monthnum' => $currentmonth,
		'posts_per_page' => -1
		);
	$month_posts = get_posts($args);
	$totsavings = 0;
	if (!empty($month_posts)) {
	foreach ( $month_posts as $month_post ) : setup_postdata( $month_post ); 
		$postamount = get_post_meta($month_post->ID, 'amount', true);			
		$totsavings = $totsavings + floatval($postamount);
	endforeach; 	
	wp_reset_postdata();			
	}
	//GET DATE THE USER REGISTERED
	//$regdate = $current_user->user_registered;	
	//$currtime = current_time( 'timestamp' );
	//$timediff = human_time_diff( $regdate, $currtime );
	//echo $regdate . "<br />";
	//echo $currtime . "<br />";
	//echo $timediff;
	//GET POSTS BY CURRENT YEAR
	
	if (!empty($_GET['theyear'])) {
		$currentyear = intval($_GET['theyear']);
	} else {
		$currentyear = intval(date('Y'));	
	}	
	

	$args2 = array(
		'author' =>  $user_id,
		'post_type' => 'saving',
		'year' => $currentyear,
		'posts_per_page' => -1
		);
	$year_posts = get_posts($args2);
	$totsavings2 = 0;
	if (!empty($year_posts)) {
	foreach ( $year_posts as $year_post ) : setup_postdata( $year_post ); 
		$postamount2 = get_post_meta($year_post->ID, 'amount', true);			
		$totsavings2 = $totsavings2 + floatval($postamount2);
	endforeach; 	
	wp_reset_postdata();			
	}
	//GET ALL SAVINGS POSTS
	$args3 = array(
		'author' =>  $user_id,
		'post_type' => 'saving',
		'posts_per_page' => -1
		);
	$all_posts = get_posts($args3);
	$totsavings3 = 0;
	foreach ( $all_posts as $all_post ) : setup_postdata( $all_post ); 
		$postamount3 = get_post_meta($all_post->ID, 'amount', true);			
		$totsavings3 = $totsavings3 + floatval($postamount3);
	endforeach; 	
	wp_reset_postdata();			
		
		
?>
<style type="text/css">
.tooltable {border-collapse: collapse; width:98%;color:#666666;}
.tooltable td, th { padding: .3em; border: 1px #ccc solid; } 
.dollarcell {text-align:center;vertical-align:middle;font-weight:bold;}
.totaldollarcell {text-align:center;vertical-align:middle;font-weight:bold;font-size:14pt;}
</style>

<p class="pagetitles">Welcome, <?php echo $showname ?>!</p>
<div id="profileTabs">
  <ul>
    <li><a href="#tab-M"><strong>Your Monetary Savings</strong></a></li>
    <li><a href="#tab-R"><strong>Your Resource Savings</strong></a></li>    
  </ul>
	<div id="tab-M">	
		<div style="width:98%;">
			<input id="changetime" type="button" value="Switch Date" style="float:right;margin-left:5px;" />
			<select name="year" id="year" style="float:right;">
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
			<select name="month" id="month" style="float:right;">
<?php       
			$months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");			
				for ($i=0; $i < count($months); $i++)
				  {
					   $mn = 1 + $i;
					   if (!empty($_GET['themonth'])) {
							   if($mn == intval($_GET['themonth']))
									{
											echo "<option selected value=" . $mn . ">" . $_GET['monthname'] . "</option> \n";
									}
							   else
									{
											echo "<option value=" . $mn . ">" . $months[$i] . "</option> \n";
									}				   
					   } else {
							   if($mn == date("m"))
									{
											echo "<option selected value=" . $mn . ">" . $months[$i] . "</option> \n";
									}
							   else
									{
											echo "<option value=" . $mn . ">" . $months[$i] . "</option> \n";
									}
						}
				  }
?>       
			</select>
			
		</div>
		<br /><br /><br />
		<table class="tooltable">
			<tr style="border-bottom: solid 2px; #ccc;">
				<td style="width:24%;border-top:0px;border-left:0px;text-align:center;">
					<br />
					<strong style="font-size:14pt;">CHANGE REPORT</strong>
					<br /><br />
				</td>
				<td style="width:38%;text-align:center;background-color:#e0e0e0;">
					<br />
					<strong style="font-size:14pt;"><?php 
					if (!empty($_GET['monthname'])) {			
						echo $_GET['monthname'];
					} else {
						echo date('F'); 
					}
					?></strong>
					<br /><br />
				</td>
				<td style="width:38%;text-align:center;background-color:#e0e0e0;">
					<br />
					<strong style="font-size:14pt;">Year-to-Date (<?php 
					if (!empty($_GET['theyear'])) {			
						echo $_GET['theyear'];
					} else {
						echo date('Y'); 
					}			
					?>)</strong>
					<br /><br />
				</td>
			</tr>
			<tr>
				<td style="background-color:#e0e0e0;font-size:10pt;">
					<img src="http://www.change-based-giving.org/cbg/wp-content/uploads/2014/06/transportation.png" alt="Transportation" width="40" style="vertical-align:middle;" />&nbsp;&nbsp;&nbsp;<strong>Transportation</strong>
				</td>
				<td class="dollarcell">
					<?php echo getSavings('transportation','M'); ?>
				</td>
				<td class="dollarcell">
					<?php echo getSavings('transportation','Y'); ?>
				</td>
			</tr>
			<tr>
				<td style="background-color:#e0e0e0;font-size:10pt;">
					<img src="http://www.change-based-giving.org/cbg/wp-content/uploads/2014/06/shopping.png" alt="Grocery Shopping" width="40" style="vertical-align:middle;" />&nbsp;&nbsp;&nbsp;<strong>Grocery Shopping</strong>
				</td>
				<td class="dollarcell">
					<?php echo getSavings('grocery-shopping','M'); ?>
				</td>
				<td class="dollarcell">
					<?php echo getSavings('grocery-shopping','Y'); ?>
				</td>
			</tr>
			<tr>
				<td style="background-color:#e0e0e0;font-size:10pt;">
					<img src="http://www.change-based-giving.org/cbg/wp-content/uploads/2014/06/entertainment.png" alt="Entertainment" width="40" style="vertical-align:middle;" />&nbsp;&nbsp;&nbsp;<strong>Entertainment</strong>
				</td>
				<td class="dollarcell">
					<?php echo getSavings('entertainmentrecreation','M'); ?>
				</td>
				<td class="dollarcell">
					<?php echo getSavings('entertainmentrecreation','Y'); ?>
				</td>
			</tr>
			<tr>
				<td style="background-color:#e0e0e0;font-size:10pt;">
					<img src="http://www.change-based-giving.org/cbg/wp-content/uploads/2014/05/cooling.png" alt="Cooling" width="40" style="vertical-align:middle;" />&nbsp;&nbsp;&nbsp;<strong>Cooling</strong>
				</td>
				<td class="dollarcell">
					<?php echo getSavings('cooling','M'); ?>
				</td>
				<td class="dollarcell">
					<?php echo getSavings('cooling','Y'); ?>
				</td>
			</tr>
			<tr>
				<td style="background-color:#e0e0e0;font-size:10pt;">
					<img src="http://www.change-based-giving.org/cbg/wp-content/uploads/2014/05/heating.png" alt="Heating" width="40" style="vertical-align:middle;" />&nbsp;&nbsp;&nbsp;<strong>Heating</strong>
				</td>
				<td class="dollarcell">
					<?php echo getSavings('heating','M'); ?>
				</td>
				<td class="dollarcell">
					<?php echo getSavings('heating','Y'); ?>
				</td>
			</tr>
			<tr>
				<td style="background-color:#e0e0e0;font-size:10pt;">
					<img src="http://www.change-based-giving.org/cbg/wp-content/uploads/2014/05/water.png" alt="Water" width="40" style="vertical-align:middle;" />&nbsp;&nbsp;&nbsp;<strong>Water</strong>
				</td>
				<td class="dollarcell">
					<?php echo getSavings('water','M'); ?>
				</td>
				<td class="dollarcell">
					<?php echo getSavings('water','Y'); ?>
				</td>
			</tr>	
			<tr style="border-top: solid 2px; #ccc;">
				<td style="background-color:#e0e0e0;text-align:center;">
					<br />
					<strong style="font-size:16pt;">TOTAL</strong>
					<br /><br />
				</td>
				<td class="totaldollarcell">
					$<?php 
						echo money_format('%i', $totsavings);
					?>
				</td>
				<td class="totaldollarcell">
					$<?php 
						echo money_format('%i', $totsavings2);
					?>
				</td>
			</tr>	
		</table>
	</div>
	<div id="tab-R">
		<div style="width:98%;">
			<input id="changetime2" type="button" value="Switch Date" style="float:right;margin-left:5px;" />
			<select name="year2" id="year2" style="float:right;">
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
			<select name="month2" id="month2" style="float:right;">
<?php       
			$months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");			
				for ($i=0; $i < count($months); $i++)
				  {
					   $mn = 1 + $i;
					   if (!empty($_GET['themonth'])) {
							   if($mn == intval($_GET['themonth']))
									{
											echo "<option selected value=" . $mn . ">" . $_GET['monthname'] . "</option> \n";
									}
							   else
									{
											echo "<option value=" . $mn . ">" . $months[$i] . "</option> \n";
									}				   
					   } else {
							   if($mn == date("m"))
									{
											echo "<option selected value=" . $mn . ">" . $months[$i] . "</option> \n";
									}
							   else
									{
											echo "<option value=" . $mn . ">" . $months[$i] . "</option> \n";
									}
						}
				  }
?>       
			</select>
			
		</div>
		<br /><br /><br />
		<table class="tooltable">
			<tr style="border-bottom: solid 2px; #ccc;">
				<td style="width:24%;border-top:0px;border-left:0px;text-align:center;">
					<br />
					<strong style="font-size:14pt;">CHANGE REPORT</strong>
					<br /><br />
				</td>
				<td style="width:38%;text-align:center;background-color:#e0e0e0;">
					<br />
					<strong style="font-size:14pt;"><?php 
					if (!empty($_GET['monthname'])) {			
						echo $_GET['monthname'];
					} else {
						echo date('F'); 
					}
					?></strong>
					<br /><br />
				</td>
				<td style="width:38%;text-align:center;background-color:#e0e0e0;">
					<br />
					<strong style="font-size:14pt;">Year-to-Date (<?php 
					if (!empty($_GET['theyear'])) {			
						echo $_GET['theyear'];
					} else {
						echo date('Y'); 
					}			
					?>)</strong>
					<br /><br />
				</td>
			</tr>
			<tr>
				<td style="background-color:#e0e0e0;font-size:10pt;">
					<img src="http://www.change-based-giving.org/cbg/wp-content/uploads/2014/06/transportation.png" alt="Transportation" width="40" style="vertical-align:middle;" />&nbsp;&nbsp;&nbsp;<strong>Transportation</strong>
				</td>
				<td class="dollarcell">
					<?php echo getResourceSavings('gas','M') . " gallons (gas)"; ?>
				</td>
				<td class="dollarcell">
					<?php echo getResourceSavings('gas','Y') . " gallons (gas)"; ?>
				</td>
			</tr>
			<tr>
				<td style="background-color:#e0e0e0;font-size:10pt;">
					<img src="http://www.change-based-giving.org/cbg/wp-content/uploads/2014/05/water.png" alt="Water" width="40" style="vertical-align:middle;" />&nbsp;&nbsp;&nbsp;<strong>Water</strong>
				</td>
				<td class="dollarcell">
					<?php echo getResourceSavings('water-2','M') . " gallons"; ?>
				</td>
				<td class="dollarcell">
					<?php echo getResourceSavings('water-2','Y') . " gallons"; ?>
				</td>
			</tr>	

		</table>	
	</div>
</div>
<?php	
	} else {
?>	
		<p class="pagetitles">Welcome, visitor!</p>
		<p>You are currently not logged in or registered. In order to start making real change please <a href="http://www.change-based-giving.org/cbg/wp-login.php">LOG IN</a> or <a href="http://www.change-based-giving.org/cbg/wp-login.php?action=register">REGISTER</a> now!</p>
<?php	
	}
?>	
	<script type="text/javascript">
		jQuery(document).ready(function ($) {
				$( "#profileTabs" ).tabs();		
		});
		
		function getParameterByName(name) {
			name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
			var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
				results = regex.exec(location.search);
			return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
		}
	</script>
<?php	
}

function getSavings($stype,$timetype) {
	$current_user = wp_get_current_user();
	$user_id = $current_user->ID;
	if (!empty($_GET['themonth'])) {
		$currentmonth = intval($_GET['themonth']);
	} else {
		$currentmonth = intval(date('m'));
	}
	if (!empty($_GET['theyear'])) {
		$currentyear = intval($_GET['theyear']);
	} else {
		$currentyear = intval(date('Y'));	
	}	
	$args5;
	if ($timetype == 'M') {
		$args5 = array(
			'author' =>  $user_id,
			'post_type' => 'saving',
			'monthnum' => $currentmonth,
			'year' => $currentyear,
			'posts_per_page' => -1,
			'tax_query' => array(
				array(
					'taxonomy' => 'savings_types',
					'field' => 'slug',
					'terms' => $stype
				)
			)			
			);		
	} elseif ($timetype == 'Y') {
		$args5 = array(
			'author' =>  $user_id,
			'post_type' => 'saving',
			'year' => $currentyear,
			'posts_per_page' => -1,
			'tax_query' => array(
				array(
					'taxonomy' => 'savings_types',
					'field' => 'slug',
					'terms' => $stype
				)
			)			
			);		
	}
	

	$all_posts = get_posts($args5);
	$totsavings5 = 0;
	foreach ( $all_posts as $all_post ) : setup_postdata( $all_post ); 
		$postamount5 = get_post_meta($all_post->ID, 'amount', true);			
		$totsavings5 = $totsavings5 + floatval($postamount5);
	endforeach; 	
	wp_reset_postdata();
	
	return '$' . money_format('%i', $totsavings5);
}

function getResourceSavings($rtype,$timetype) {
	$current_user = wp_get_current_user();
	$user_id = $current_user->ID;
	if (!empty($_GET['themonth'])) {
		$currentmonth = intval($_GET['themonth']);
	} else {
		$currentmonth = intval(date('m'));
	}
	if (!empty($_GET['theyear'])) {
		$currentyear = intval($_GET['theyear']);
	} else {
		$currentyear = intval(date('Y'));	
	}	
	$args5;
	if ($timetype == 'M') {
		$args5 = array(
			'author' =>  $user_id,
			'post_type' => 'saved_resource',
			'monthnum' => $currentmonth,
			'year' => $currentyear,
			'posts_per_page' => -1,
			'tax_query' => array(
				array(
					'taxonomy' => 'resource_types',
					'field' => 'slug',
					'terms' => $rtype
				)
			)			
			);		
	} elseif ($timetype == 'Y') {
		$args5 = array(
			'author' =>  $user_id,
			'post_type' => 'saved_resource',
			'year' => $currentyear,
			'posts_per_page' => -1,
			'tax_query' => array(
				array(
					'taxonomy' => 'resource_types',
					'field' => 'slug',
					'terms' => $rtype
				)
			)			
			);		
	}
	

	$all_posts = get_posts($args5);
	$totsavings5 = 0;
	foreach ( $all_posts as $all_post ) : setup_postdata( $all_post ); 
		$postamount5 = get_post_meta($all_post->ID, 'resourceamount', true);			
		$totsavings5 = $totsavings5 + floatval($postamount5);
	endforeach; 	
	wp_reset_postdata();
	
	return $totsavings5;

}