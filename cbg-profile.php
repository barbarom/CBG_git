<?php
/*
Template Name: Change Based Giving - Profile Page
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
				cbg_profile();
				get_template_part('templates/content', 'page'); ?>
				<?php global $virtue; 
					if(isset($virtue['page_comments']) && $virtue['page_comments'] == '1') {
						comments_template('/templates/comments.php');
					} ?>
		</div><!-- /.main -->

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
	
	if (!empty($_GET['monthname'])) {
		$currentmonth = $_GET['monthname'];
	} else {
		$currentmonth = date('F');
	}
	if (!empty($_GET['theyear'])) {
		$currentyear = intval($_GET['theyear']);
	} else {
		$currentyear = intval(date('Y'));	
	}	
	//echo "MONTH=" . $currentmonth;
	
	//GET POSTS BY CURRENT MONTH
	$args = array(
		'author' =>  $user_id,
		'post_type' => 'saving',
		'posts_per_page' => -1,
		'meta_query' => array(
				'relation' => 'AND',
				array(
					'key'     => 'savings_month',
					'value'   => $currentmonth
				),
				array(
					'key' => 'savings_year',
					'value'   => $currentyear				
				),		
			),		
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
	
	
	

	$args2 = array(
		'author' =>  $user_id,
		'post_type' => 'saving',		
		'posts_per_page' => -1,
		'meta_query' => array(
				array(
					'key' => 'savings_year',
					'value'   => $currentyear				
				),		
			),		
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
<a href="http://www.change-based-giving.org/donate/"><img class=" size-full wp-image-928 alignright" src="http://www.change-based-giving.org/cbg/wp-content/uploads/2015/05/donatebutton.png" alt="Donate" width="112" height="40" /></a>
<p style="font-size:16pt;color:#915324;font-weight:bold;">Welcome, <?php echo $showname ?>!</p>
	<div class="tabs">
		<ul class="tab-links">
			<li id="t1" class="active"><a href="#tab1">Monetary Savings</a></li>
			<li id="t2"><a href="#tab2">Resource Savings</a></li>
			<li id="t3"><a href="#tab3">Total Donations</a></li>
		</ul>  
	
	  <div class="tab-content">
		  <div id="tab1" class="tab active">	
			<div style="width:98%;">
			<input id="changetime" type="button" value="Go" style="float:right;margin-left:5px;" />
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
				<td style="width:38%;text-align:center;background-color:#CFCFCF;">
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
				<td style="width:38%;text-align:center;background-color:#CFCFCF;">
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
				<td style="background-color:#CFCFCF;font-size:10pt;">
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
				<td style="background-color:#CFCFCF;font-size:10pt;">
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
				<td style="background-color:#CFCFCF;font-size:10pt;">
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
				<td style="background-color:#CFCFCF;font-size:10pt;">
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
				<td style="background-color:#CFCFCF;font-size:10pt;">
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
				<td style="background-color:#CFCFCF;font-size:10pt;">
					<img src="http://www.change-based-giving.org/cbg/wp-content/uploads/2014/05/water.png" alt="Water" width="40" style="vertical-align:middle;" />&nbsp;&nbsp;&nbsp;<strong>Water</strong>
				</td>
				<td class="dollarcell">
					<?php echo getSavings('water','M'); ?>
				</td>
				<td class="dollarcell">
					<?php echo getSavings('water','Y'); ?>
				</td>
			</tr>	
			<tr>
				<td style="background-color:#CFCFCF;font-size:10pt;">
					<img src="http://www.change-based-giving.org/cbg/wp-content/uploads/2015/02/fridge.png" alt="Appliances" width="40" style="vertical-align:middle;" />&nbsp;&nbsp;&nbsp;<strong>Appliances</strong>
				</td>
				<td class="dollarcell">
					<?php echo getSavings('appliance','M'); ?>
				</td>
				<td class="dollarcell">
					<?php echo getSavings('appliance','Y'); ?>
				</td>
			</tr>
			<tr>
				<td style="background-color:#CFCFCF;font-size:10pt;">
					<img src="http://www.change-based-giving.org/cbg/wp-content/uploads/2015/02/recycle.png" alt="Recycling" width="40" style="vertical-align:middle;" />&nbsp;&nbsp;&nbsp;<strong>Recycling</strong>
				</td>
				<td class="dollarcell">
					<?php echo getSavings('recycling','M'); ?>
				</td>
				<td class="dollarcell">
					<?php echo getSavings('recycling','Y'); ?>
				</td>
			</tr>			
			<tr style="border-top: solid 2px; #ccc;">
				<td style="background-color:#CFCFCF;text-align:center;">
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
	<div id="tab2" class="tab">
		<div style="width:98%;">
			<input id="changetime2" type="button" value="Go" style="float:right;margin-left:5px;" />
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
				<td style="width:38%;text-align:center;background-color:#CFCFCF;">
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
				<td style="width:38%;text-align:center;background-color:#CFCFCF;">
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
				<td style="background-color:#CFCFCF;font-size:10pt;">
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
				<td style="background-color:#CFCFCF;font-size:10pt;">
					<img src="http://www.change-based-giving.org/cbg/wp-content/uploads/2014/05/water.png" alt="Water" width="40" style="vertical-align:middle;" />&nbsp;&nbsp;&nbsp;<strong>Water</strong>
				</td>
				<td class="dollarcell">
					<?php echo getResourceSavings('water-2','M') . " gallons"; ?>
				</td>
				<td class="dollarcell">
					<?php echo getResourceSavings('water-2','Y') . " gallons"; ?>
				</td>
			</tr>	
			<tr>
				<td style="background-color:#CFCFCF;font-size:10pt;">
					<img src="http://www.change-based-giving.org/cbg/wp-content/uploads/2014/05/light.png" alt="Electricity" width="40" style="vertical-align:middle;" />&nbsp;&nbsp;&nbsp;<strong>Electricity</strong>
				</td>
				<td class="dollarcell">
					<?php echo getResourceSavings('electricity','M') . " kWh"; ?>
				</td>
				<td class="dollarcell">
					<?php echo getResourceSavings('electricity','Y') . " kWh"; ?>
				</td>
			</tr>
		</table>	
	</div>
	<div id="tab3" class="tab">
		<div style="width:98%;">
						
			<?php
				
				$paymentargs = array(				
					'post_type' => 'payment',
					'posts_per_page' => -1, 					
					'meta_query' => array(
						array(
							'key'     => 'user_id',
							'value'   => $user_id,						
						),
					),					
				);
				
				$author_query = new WP_Query( $paymentargs );
				$author_posts = $author_query->get_posts();
				$ttl_donation = 0;
				$donation_str = "<table style='width:50%'><thead><tr><td>Donation Date</td><td>Donation Amount</td></tr></thead>";
				foreach ($author_posts as $ap) {
					$date = $ap->post_date;
					$createDate = new DateTime($date);
					$strip = $createDate->format('m-d-Y');
					$donation_str = $donation_str . "<tbody><tr><td>" . $strip . "</td><td><span style='margin-left:20px;'>" . money_format('$%i', $ap->gross_donation) . "<span></td></tr>";
					//echo "<span style='margin-left:30px;'>" . $ap->gross_donation . "</span><br />";
					$ttl_donation = $ttl_donation + $ap->gross_donation;
				}
				//echo "<span style='margin-left:30px;font-weight:bold;'>" . $ttl_donation . "</span><br />";
				
				
				$donation_str = $donation_str . "</tbody></table>";
				$confirm_msg = "Here are your total donations to Change Based Giving.<br /><br /><br />" . $donation_str . "<br /><br />Total = <strong>" . money_format('$%i', $ttl_donation) . "</strong><br /><br /><br />";
				echo $confirm_msg;				

			?>			
		</div>
	</div>
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
			$('.tabs .tab-links a').on('click', function(e)  {
				var currentAttrValue = $(this).attr('href');
		 
				// Show/Hide Tabs
				$('.tabs ' + currentAttrValue).show().siblings().hide();
		 
				// Change/remove current tab to active
				$(this).parent('li').addClass('active').siblings().removeClass('active');
		 
				e.preventDefault();
			});	

			var m = getParameterByName('themonth');
			var y = getParameterByName('theyear');
			var t = getParameterByName('thetab');
			
			if (m.length>2) {
				if (t == 'tab1') {
					$("#tab3").hide();
					$("#tab2").hide();
					$("#tab1").show();
					$("#t3").removeClass('active');
					$("#t2").removeClass('active');					
					$("#t1").addClass('active');					
				} else if (t == 'tab2') {
					$("#tab1").hide();
					$("#tab2").show();
					$("#tab3").hide();
					$("#t1").removeClass('active');
					$("#t2").addClass('active');
					$("#t3").removeClass('active');					
				} else if (t == 'tab3') {
					$("#tab1").hide();
					$("#tab2").hide();
					$("#tab3").hide();
					$("#t1").removeClass('active');
					$("#t2").removeClass('active');	
					$("#t3").addClass('active');					
				}			
			}
			if (y.length>2) {
				if (t == 'tab1') {
					$("#tab3").hide();
					$("#tab2").hide();
					$("#tab1").show();
					$("#t3").removeClass('active');
					$("#t2").removeClass('active');
					$("#t1").addClass('active');					
				} else if (t == 'tab2') {
					$("#tab1").hide();
					$("#tab2").show();
					$("#tab3").hide();
					$("#t1").removeClass('active');
					$("#t2").addClass('active');
					$("#t3").removeClass('active');					
				} else if (t == 'tab3') {
					$("#tab1").hide();
					$("#tab2").hide();
					$("#tab3").show();
					$("#t1").removeClass('active');
					$("#t2").removeClass('active');	
					$("#t3").addClass('active');					
				}
			}		

			function getParameterByName(name) {
				name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
				var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
					results = regex.exec(location.search);
				return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
			}			
		});
		

	</script>
<?php	
}

function getSavings($stype,$timetype) {
	$current_user = wp_get_current_user();
	$user_id = $current_user->ID;
	if (!empty($_GET['monthname'])) {
		$currentmonth = $_GET['monthname'];
	} else {
		$currentmonth = date('F');
	}
	if (!empty($_GET['theyear'])) {
		$currentyear = $_GET['theyear'];
	} else {
		$currentyear = intval(date('Y'));	
	}	
	$args5;
	if ($timetype == 'M') {
		$args5 = array(
			'author' =>  $user_id,
			'post_type' => 'saving',
			'posts_per_page' => -1,
			'meta_query' => array(
					'relation' => 'AND',
					array(
						'key'     => 'savings_month',
						'value'   => $currentmonth
					),
					array(
						'key' => 'savings_year',
						'value'   => $currentyear				
					),		
				),			
			'tax_query' => array(
				array(
					'taxonomy' => 'savings_types',
					'field' => 'slug',
					'terms' => $stype
				),
			),			
			);		
	} elseif ($timetype == 'Y') {
		$args5 = array(
			'author' =>  $user_id,
			'post_type' => 'saving',
			'meta_query' => array(
					array(
						'key' => 'savings_year',
						'value'   => $currentyear				
					),		
				),
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

	if ($rtype == 'electricity') {
		$rtype = array( 'appliance-2', 'cooling-2', 'heating-2' );
	}

	$current_user = wp_get_current_user();
	$user_id = $current_user->ID;
	if (!empty($_GET['monthname'])) {
		$currentmonth = $_GET['monthname'];
	} else {
		$currentmonth = date('F');
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
			'posts_per_page' => -1,
			'meta_query' => array(
					'relation' => 'AND',
					array(
						'key'     => 'resource_month',
						'value'   => $currentmonth
					),
					array(
						'key' => 'resource_year',
						'value'   => $currentyear				
					),		
				),			
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
			'posts_per_page' => -1,
			'meta_query' => array(
					array(
						'key' => 'resource_year',
						'value'   => $currentyear				
					),		
				),			
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
	
	return number_format((float)$totsavings5, 2, '.', '');
	

}