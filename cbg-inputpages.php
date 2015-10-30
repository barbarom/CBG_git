<?php
/*
Template Name: Change Based Giving - Input Pages
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
				cbg_add_inputs();
				get_template_part('templates/content', 'page'); ?>
				<?php global $virtue; 
					if(isset($virtue['page_comments']) && $virtue['page_comments'] == '1') {
						comments_template('/templates/comments.php');
					} ?>
		</div><!-- /.main -->

<?php
function cbg_add_inputs() {
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
	if (window.name == "TAB2") {
		//alert("tab2");
		$("#tab1").hide();
		$("#tab2").show();
		$("#t1").removeClass('active');
		$("#t2").addClass('active');
		window.name="CBG";
	}
	if (m.length>2) {
		
		$("#tab1").hide();
		$("#tab2").show();
		$("#t1").removeClass('active');
		$("#t2").addClass('active');
		
	}
	if (y.length>2) {

		$("#tab1").hide();
		$("#tab2").show();
		$("#t1").removeClass('active');
		$("#t2").addClass('active');
	}
	
	$( document ).tooltip();
	$("#water_shortcut").click(function () {
		$("#water_3").val(.006);
	});
	$('#electric_heat_cb').change(function () {
        if (!this.checked) {
            $('#electric_heat_div').fadeOut('slow');
		} else {
            $('#electric_heat_div').fadeIn('slow');
		}
    });
	
	function getParameterByName(name) {
		name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
		var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
			results = regex.exec(location.search);
		return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
	}
});
</script>
<?php
	$user_id = 0;
	if(is_user_logged_in()) {
		global $current_user; 
		$user_id = $current_user->ID;	
	
		$newfridge;
		$fridgeargs = array(
		'author' =>  $user_id,
		'post_type' => 'saving',
		'posts_per_page'=>-1,
		'tax_query' => array(
			array(
				'taxonomy' => 'savings_types',
				'field' => 'slug',
				'terms' => 'appliance'
			)
		)	
		);
		$fridge_posts = get_posts($fridgeargs);
		
		if (!empty($fridge_posts)) {
			foreach ( $fridge_posts as $fridge_post ) : setup_postdata( $fridge_post );
				
				$newfridge = get_post_meta( $fridge_post->ID, 'newfridge', true );

			endforeach; 
			wp_reset_postdata();
		}
	

		
		if (!empty($_GET['themonth'])) {
			$currentmonth = $_GET['themonth'];
		} else {
			$currentmonth = date('F');
		}
		if (!empty($_GET['theyear'])) {
			$currentyear = intval($_GET['theyear']);
		} else {
			$currentyear = intval(date('Y'));	
		}		
		
		$old_entertainmentrecreation = "";
		$old_appliance = "";
		$old_recycling = "";
		$old_cooling = "";
		$old_heating = "";
		$old_groceryshopping = "";
		$old_lighting = "";
		$old_transportation = "";
		$old_water = "";
		
		$args = array(
			'author' =>  $user_id,
			'post_type' => 'saving',
			'posts_per_page'=>-1,
			'meta_query' => array(
					'relation' => 'AND',
					array(
						'key'     => 'savings_month',
						'value'   => $currentmonth
					),
					array(
						'key' => 'savings_year',
						'value'   => $currentyear				
					)		
				),
			);
		$author_posts = get_posts($args);
		
		$totsavings = 0;
		$totentertainmentrecreation = 0;
		$totappliance = 0;
		
		$totrecycling = 0;
		$totheating = 0;
		$totcooling = 0;
		$totgrocery = 0;
		$totlighting = 0;
		$tottrans = 0;
		$totwater = 0;
		foreach ( $author_posts as $author_post ) : setup_postdata( $author_post ); 
			$postamount = get_post_meta($author_post->ID, 'amount', true);	
			$savingstypes = wp_get_post_terms( $author_post->ID, 'savings_types' );
			foreach ($savingstypes as $savingstype) {				
				if ($savingstype->slug == "entertainmentrecreation") {
					$old_entertainmentrecreation = $postamount;
					$totentertainmentrecreation = $totentertainmentrecreation + floatval($postamount);
				} elseif($savingstype->slug == "heating") {
					$old_heating = $postamount;
					$totheating = $totheating + floatval($postamount);
				} elseif($savingstype->slug == "cooling") {
					$old_cooling = $postamount;
					$totcooling = $totcooling + floatval($postamount);
				} elseif($savingstype->slug == "grocery-shopping") {
					$old_groceryshopping = $postamount;
					$totgrocery = $totgrocery + floatval($postamount);
				} elseif($savingstype->slug == "lighting") {
					$old_lighting = $postamount;
					$totlighting = $totlighting + floatval($postamount);
				} elseif($savingstype->slug == "transportation") {
					$old_transportation = $postamount;
					//echo $postamount . "<br />";
					$tottrans = $tottrans + floatval($postamount);
				} elseif($savingstype->slug == "water") {
					$old_water = $postamount;	
					$totwater = $totwater + floatval($postamount);
				} elseif($savingstype->slug == "appliance") {
					$old_appliance = $postamount;	
					$totappliance = $totappliance + floatval($postamount);					
				} elseif($savingstype->slug == "recycling") {
					$old_recycling = $postamount;	
					$totrecycling = $totrecycling + floatval($postamount);
				}						
			}			
			$totsavings = $totsavings + floatval($postamount);			
		endforeach; 
		wp_reset_postdata();

		//Saved Resources		
		$sr_args = array(
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
					)		
				),
			);
		$sr_posts = get_posts($sr_args);
		$tot_sr = 0;
		$totheating_sr = 0;
		$totcooling_sr = 0;
		$totlighting_sr = 0;
		$totwater_sr = 0;	
		$totgas_sr = 0;
		
		foreach ( $sr_posts as $sr_post ) : setup_postdata( $sr_post ); 
			$sramount = get_post_meta($sr_post->ID, 'resourceamount', true);
			$resourcetypes = wp_get_post_terms( $sr_post->ID, 'resource_types' );
			foreach ($resourcetypes as $resourcetype) {
				if ($resourcetype->slug == "gas") {
					$totgas_sr = $totgas_sr + floatval($sramount);
				} elseif($resourcetype->slug == "heating-2") {
					$totheating_sr = $totheating_sr + floatval($sramount);
				} elseif($resourcetype->slug == "cooling-2") {
					$totcooling_sr = $totcooling_sr + floatval($sramount);
				} elseif($resourcetype->slug == "water-2") {
					$totwater_sr = $totwater_sr + floatval($sramount);
				} elseif($resourcetype->slug == "lighting-2") {
					$totlighting_sr = $totlighting_sr + floatval($sramount);
				} 	
			}	
			$tot_sr = $tot_sr + floatval($sramount);				
		endforeach; 		
		wp_reset_postdata();		
		
				
		global $post;
		$pagename = $post->post_name;
		if ($pagename == 'entertainment') {
			$pagename = 'entertainmentrecreation';
		}
		if ($pagename == 'appliances') {
			$pagename = 'appliance';
		}

		$B1 = "";
		$B2 = "";
		$B3 = "";
		$B4 = "";
		$B5 = "";
		$B6 = "";
		$B7 = "";
		
		$args2 = array(
			'author' =>  $user_id,
			'post_type' => 'baseline',
			'baseline_types' => $pagename
			);
		$baseline_posts = get_posts($args2);
		foreach ( $baseline_posts as $baseline_post ) : setup_postdata( $baseline_post ); 
			$B1 = get_post_meta($baseline_post->ID, 'B1', true);
			$B2 = get_post_meta($baseline_post->ID, 'B2', true);
			$B3 = get_post_meta($baseline_post->ID, 'B3', true);
			$B4 = get_post_meta($baseline_post->ID, 'B4', true);
			$B5 = get_post_meta($baseline_post->ID, 'B5', true);
			$B6 = get_post_meta($baseline_post->ID, 'B6', true);
			$B7 = get_post_meta($baseline_post->ID, 'B7', true);
		endforeach; 
		wp_reset_postdata();		

	}
?>
	<input type="hidden" id="userid" value="<?php echo $user_id ?>" />	
<?php	
	
	if (is_page( 'transportation' )) {
?>
	

		
		

	<div class="tabs">
		<ul class="tab-links">
			<li id="t1" class="active"><a href="#tab1">Baseline</a></li>
			<li id="t2"><a href="#tab2">Savings</a></li>

		</ul>  
	
	  <div class="tab-content">
		  <div id="tab1" class="tab active">
					
					<p style="margin-bottom:15px;font-style:italic;">Hover over textboxes for additional information. You must fill in the baseline information—will only take a minute—in order to track your savings.</p>
					<p class="inputs"><strong>On average, how many roundtrips do you make to work each month? (if work is not your most frequent destination, feel free to substitute something else)</strong>  <input type="text" id="trans_1" class="tooltips" title="It may be easier to think about this as the number of weekly trips to work multiplied by four.  Also, if you go back home for lunch then return to work—make sure you consider the extra trips." style="width:60px;" value="<?php echo $B1 ?>" /><span class="errmsg" id="errmsg1"></span><br />
					
					<p class="inputs"><strong>How many miles are included in roundtrip—i.e. to work and back home?</strong> <input type="text" id="trans_2" style="width:60px;" class="tooltips" title="Use the odometer on your car to measure the distance.  Or research it online using MapQuest or Google Maps" value="<?php echo $B2 ?>" /><span class="errmsg" id="errmsg2"></span><br />
					
					<p class="inputs"><strong>How many miles does your car travel per gallon (mpg)?  </strong> <input type="text" id="trans_3" style="width:60px;" class="tooltips" title="When you fill up, reset your odometer.  When your tank is empty again, see how many miles you've gone.  Divide the miles by the number of gallons it took to refill your tank.  Or you could research this info online for the average MPG of your car's make and model." value="<?php echo $B3 ?>"/><span class="errmsg" id="errmsg3"></span><br />
					
					<p class="inputs"><strong>What is the average price of gas in your area?</strong> $  <input type="text" id="trans_4" style="width:60px;" class="tooltips" title="This information can usually be found on the Internet (www.gasbuddy.com)" value="<?php echo $B4 ?>"/><span class="errmsg" id="errmsg4"></span> per gallon.<br />Short cut: Find average gas prices in your location by using one of the following websites: <a href="http://fuelgaugereport.aaa.com/todays-gas-prices/" target="_blank">AAA</a> or <a href="http://www.gasbuddy.com/" target="_blank">GasBuddy</a>.
					
				<br /><br />
				<input id="trans_base" type="button" value="Calculate Baseline" onclick="calcTransportation()" /><span class="errmsg" id="errmsg5"></span>
				<hr />
				<div class="baselinediv1" id="baselineresults">
				<h5>LIFESTYLE BASELINE</h5>
					You spend about $ <input id="transbaseline_1" style="width:50px;font-weight:bold;" value="<?php echo $B5 ?>" readonly /> on gas per month.  Each trip to work costs $ <input id="transbaseline_2" value="<?php echo $B6 ?>" style="width:50px;font-weight:bold;" readonly />.  
				</div>
		  </div>
		  <div id="tab2" class="tab">

				
				<div style="text-align:right;"><?php cbg_change_savings_date(); ?></div>
				<p class="inputs">Fill in one or more options below. You can update your savings after each individual trip, or you can tally up your alternative transportation trips and add them whenever it suits you--e.g. weekly or monthly.</p><br />
				<p class="inputs">&bull;&nbsp;&nbsp;&nbsp;Instead of driving, I saved <input type="text" id="transchange_1" style="width:60px;" /> trips to work by riding my bike.  <span class="errmsg" id="errmsg7"></span></p>
				<p class="inputs">&bull;&nbsp;&nbsp;&nbsp;I joined a carpool and saved <input type="text" id="transchange_2" style="width:60px;" /> trips to work with my own car.  <span class="errmsg" id="errmsg8"></span></p>
				<p class="inputs">&bull;&nbsp;&nbsp;&nbsp;Instead of driving, I took the bus or train; this saved me <input type="text" id="transchange_3" style="width:60px;" /> trips to work using my own vehicle. A roundtrip ticket costs $ <input type="text" id="transchange_4" style="width:60px;" />.  (Your total monthly savings will reflect the cost of these tickets.)
				<span class="errmsg" id="errmsg10"></span><span class="errmsg" id="errmsg11"></span>
				</p>
				<br />
				<input type="button" id="trans_bankit" name="trans_bankit" value="Update Savings" onclick="calcTransSavings()" /><span class="errmsg" id="errmsg6"></span>
				<hr />
				<div class="baselinediv2" id="baselineresults">
				<h5>TRANSPORTATION SAVINGS</h5><br />
			<?php if ( is_user_logged_in() ) { ?>				
					<input type="hidden" id="transsaved_0" />	
					<p class="inputs">Gas saved this month: <input type="text" id="transsaved_2" value="<?php echo $totgas_sr; ?>" style="width:50px;font-weight:bold;" readonly /> gallons.</p>
					<div><strong>Transportation money saved this month:</strong>&nbsp;&nbsp;<span style="font-size:18pt;font-weight:bold;color:#688571;">$</span><span id="transsaved_1" style="color:#688571;font-size:18pt;font-weight:bold;"><?php echo money_format('%i', $tottrans); ?></span></div>
					<div id="transbankit_result"></div>		
			<?php } else { ?>
					<div><strong>Transportation money saved:</strong>&nbsp;&nbsp;<span id="transsaved_NL" style="color:#688571;font-size:18pt;font-weight:bold;"></span></div>
					
			<?php
					}
				?>	
				</div>

		  </div>
	</div>	
</div>	
<?php cbg_tool_dropdown(); ?>
		

		



		<script type="text/javascript">
			jQuery(document).ready(function ($) {

				
			
			
			  //called when key is pressed in textbox
			  $("#trans_1").keypress(function (e) {
				 //if the letter is not digit then display error and don't type anything
				 if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
					//display error message
					$("#errmsg1").html("Digits Only").show().fadeOut(2000);
						   return false;
				}
			   });
			  //called when key is pressed in textbox
			  $("#trans_2").keypress(function (e) {
				 //if the letter is not digit then display error and don't type anything
				 if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
					//display error message
					$("#errmsg2").html("Digits Only").show().fadeOut(2000);
						   return false;
				}
			   });		
			  //called when key is pressed in textbox
			  $("#trans_3").keypress(function (e) {
				 //if the letter is not digit then display error and don't type anything
				 if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
					//display error message
					$("#errmsg3").html("Digits Only").show().fadeOut(2000);
						   return false;
				}
			   });
			  $("#trans_4").on("keyup", function(){
				var valid = /^\d{0,4}(\.\d{0,2})?$/.test(this.value),
					val = this.value;
				
				if(!valid){
				this.value = val.substring(0, val.length - 1);
					$("#errmsg5").html("Currency Only").show().fadeOut(2000);
						   return false;					
				}
				});	
			  //called when key is pressed in textbox
			  $("#transchange_1").keypress(function (e) {
				 //if the letter is not digit then display error and don't type anything
				 if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
					//display error message
					$("#errmsg7").html("Digits Only").show().fadeOut(2000);
						   return false;
				}
			   });
			  //called when key is pressed in textbox
			  $("#transchange_2").keypress(function (e) {
				 //if the letter is not digit then display error and don't type anything
				 if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
					//display error message
					$("#errmsg8").html("Digits Only").show().fadeOut(2000);
						   return false;
				}
			   });
			  //called when key is pressed in textbox
			  $("#transchange_3").keypress(function (e) {3
				 //if the letter is not digit then display error and don't type anything
				 if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
					//display error message
					$("#errmsg10").html("Digits Only").show().fadeOut(2000);
						   return false;
				}
			   });	
			  $("#transchange_4").on("keyup", function(){
				var valid = /^\d{0,4}(\.\d{0,2})?$/.test(this.value),
					val = this.value;
				
				if(!valid){
				this.value = val.substring(0, val.length - 1);
					$("#errmsg11").html("Currency Only").show().fadeOut(2000);
						   return false;					
				}
				});			   
			});		
		   function calcTransportation() {
				if ( jQuery("#trans_1").val().length === 0 || jQuery("#trans_2").val().length === 0 || jQuery("#trans_3").val().length === 0 || jQuery("#trans_4").val().length === 0 ) {
					jQuery("#errmsg5").html("Make sure all fields are filled in!").show();
					jQuery('#transbaseline_1').removeAttr('value');
					jQuery('#transbaseline_2').removeAttr('value');
					return false;
				} else {
					var totalmiles = parseInt(jQuery("#trans_1").val() * jQuery("#trans_2").val());
					var gallons = totalmiles / jQuery("#trans_3").val();
					var moneyspent = (gallons * jQuery("#trans_4").val()).toFixed(2);
				
					jQuery("#transbaseline_1").val(moneyspent);
					
					var costpertrip = ((jQuery("#trans_2").val() / jQuery("#trans_3").val()) * jQuery("#trans_4").val()).toFixed(2);
					jQuery("#transbaseline_2").val(costpertrip);
					jQuery("#errmsg5").hide();
				}
		   }
			function calcTransSavings() {
				if (jQuery("#transbaseline_2").val().length === 0) {
						jQuery("#errmsg6").html("Please calculate your baseline before calculating your savings.").show();
						return false;	
				} else {
					if ( jQuery("#transchange_1").val().length === 0 && jQuery("#transchange_2").val().length === 0 && jQuery("#transchange_3").val().length === 0 ) {
						jQuery("#errmsg6").html("Make at least one of the savings fields is filled in!").show();
						return false;				
					} else {
						var othertranscost = (jQuery("#transchange_1").val() * jQuery("#transbaseline_2").val()) + (jQuery("#transchange_2").val() * jQuery("#transbaseline_2").val());
						var pubtranscost = (jQuery("#transchange_3").val() * jQuery("#transbaseline_2").val()) - (jQuery("#transchange_4").val() * jQuery("#transchange_3").val());
						var savings1;
						if (pubtranscost > 0) {
							savings1 = (othertranscost + pubtranscost).toFixed(2);
							
						} else {
							savings1 = (othertranscost + 0).toFixed(2);
						}
						
						if (savings1 < 0) {
							savings1 = 0;
						}
						jQuery("#transsaved_0").val(savings1);
						jQuery("#transsaved_NL").html("$" + savings1);
						
						jQuery("#errmsg6").hide();
						
						var trips1 = 0;
						var trips2 = 0;
						var trips3 = 0;
						
						if (jQuery("#transchange_1").val()) {
							trips1 = jQuery("#transchange_1").val();
						}
						if (jQuery("#transchange_2").val()) {
							trips2 = jQuery("#transchange_2").val();
						}
						if (jQuery("#transchange_3").val()) {
							trips3 = jQuery("#transchange_3").val();
						}
						
						var numtrips = parseFloat(trips1) + parseFloat(trips2) + parseFloat(trips3);						
						var gal_saved = (numtrips * jQuery("#trans_2").val()) / jQuery("#trans_3").val();						
						jQuery("#transsaved_2").val(gal_saved);
					
							
					}
				}
			
			}
		</script>
<?php 
	} elseif (is_page( 'water' )) {
?>
	<div class="tabs">
		<ul class="tab-links">
			<li id="t1" class="active"><a href="#tab1">Baseline</a></li>
			<li id="t2"><a href="#tab2">Savings</a></li>

		</ul>  
	
	  <div class="tab-content">
		  <div id="tab1" class="tab active">
		  
			<p style="margin-bottom:15px;font-style:italic;">Hover over textboxes for additional information. You must fill in the baseline information—will only take a minute—in order to track your savings.</p>
			<p class="inputs"><strong>How many showers do you take per month?</strong><input type="text" id="water_1" value="<?php echo $B1 ?>" class="tooltips" title="Remember: this question is simply trying to help you get a handle on your resource usage; your answer does not have to be exact.  Many people, for instance, shower at least once a day [e.g. before work or school in the morning], and many people exercise a consistent number of days a week [and often take showers after a workout].  These 'habits' or 'markers' should make it pretty simple to approximate how many showers you take per month." style="width:60px;" /><span class="errmsg" id="errmsg1"></span><br /></p>
			<p class="inputs"><strong>On average, how many minutes do you spend in the shower?  </strong> <input type="text" id="water_2" class="tooltips" title="Time your showers for the next several days and divide the total minutes by the number of days.  For instance, if you spent a total of 60 minutes in the shower over five days, your average shower time would be ten minutes." value="<?php echo $B2 ?>" style="width:60px;" /><span class="errmsg" id="errmsg2"></span><br />
			<p class="inputs"><strong>What does one gallon of water cost from your utility?    </strong> $<input type="text" id="water_3" value="<?php echo $B3 ?>" class="tooltips" title="Most water utilities charge a fixed amount for the first 1,000 gallons (i.e. from 1-1,000).  In Batesville, AR, for instance, the minimum fee is $8.50.  For every thousand gallons consumed after you cross the 1,000 threshold, the utility will assess a fee.  For example, in Batesville, it is $3.27 per 1,000 gallons.  So, to take a hypothetical example, let’s say you used 14,500 gallons of water in one month.  The first 1,000 gallons would cost $8.50.  Then, the remaining 13,500 gallons would cost $44.15 (13.5x$3.27).  The total water bill would be $52.65 ($8.50 minimum + $44.15). To find the amount your utility charges per gallon of water, simply divide your total bill (in this case, $52.65), by the number of total gallons used (in this case, 14,500) to get your answer--.0036 or 4/10 of a cent." style="width:60px;" /><span class="errmsg" id="errmsg3"></span><input type="button" id="water_shortcut" value="Use Shortcut" /><br />Short cut: use national average of $.006 (Source: <a href="http://www.epa.gov/watersense/our_water/how_works.html#inputs" target="_blank">EPA guidelines</a>)</p>
		<br />
		<input id="water_base" type="button" value="Calculate your Baseline >>" style="font-size:14pt;" onclick="calcWater()" /><span class="errmsg" id="errmsg5"></span>
		<hr />
		<div class="baselinediv1" id="baselineresults">
		<h5>LIFESTYLE BASELINE</h5>
			You spend <input id="waterbaseline_1" style="width:50px;font-weight:bold;" value="<?php echo $B4 ?>" readonly /> minutes in the shower per month.  This means that you use approximately <input id="waterbaseline_2" style="width:50px;font-weight:bold;" value="<?php echo $B5 ?>" readonly />  gallons of water for showering per month.  (Cbg uses U.S. Geological Survey data: assumes average shower head/average pressure = 2.5 gallons per minute.)  Your approximate showering costs per month are $ <input id="waterbaseline_3" style="width:50px;font-weight:bold;" value="<?php echo $B6 ?>" readonly /> and your approximate cost per shower is $ <input id="waterbaseline_4" style="width:50px;font-weight:bold;" value="<?php echo $B7 ?>" readonly />.		
			 
		</div>		
	</div>
	<div id="tab2" class="tab">
	
		
		<div style="text-align:right;"><?php cbg_change_savings_date(); ?></div>
		<p class="inputs">Fill in one or more options below. This money and water saving strategy uses monthly averages.</p><br />
		<p class="inputs">&bull;&nbsp;&nbsp;&nbsp;Instead of my normal shower time of <span id="normalshower" style="font-weight:bold;"></span> minutes, I reduced my average shower time this month by <input type="text" id="waterchange_1" style="width:60px;" /> minutes.  <span class="errmsg" id="errmsg7"></span></p>
		<p class="inputs">&bull;&nbsp;&nbsp;&nbsp;I purchased a low flow shower head.  Instead of using approximately 2.5 gallons of water a minute, I am now using (enter new gallons per minute specs) <input type="text" id="waterchange_2" style="width:60px;" /><span class="errmsg" id="errmsg8"></span></p>

		</p>
		<br />
		<input type="button" id="water_bankit" name="water_bankit" value="Update your Savings! >>" style="font-size:14pt;" onclick="calcWaterSavings()" /><span class="errmsg" id="errmsg6"></span>
		<hr />
		<div class="baselinediv2" id="baselineresults">
		<h5>WATER SAVINGS</h5>
		<?php if ( is_user_logged_in() ) { ?>
			<p class="inputs">Gallons of water saved this month:  <input type="text" id="watersaved_2" value="<?php echo $totwater_sr; ?>" style="width:50px;font-weight:bold;" readonly /></p> 
					
			<div><strong>Water money saved this month:</strong>&nbsp;&nbsp;<span style="font-size:18pt;font-weight:bold;color:#688571;">$</span><span id="watersaved_1" style="color:#688571;font-size:18pt;font-weight:bold;"><?php echo money_format('%i', $totwater); ?></span></div>		
			<div id="waterbankit_result"></div>	
		<?php } else { ?>
			<div><strong>Water money saved:</strong>&nbsp;&nbsp;<span id="watersaved_NL" style="color:#688571;font-size:18pt;font-weight:bold;"></span></div>	

		<?php
			}
		?>
		</div>
		
	</div>
</div>
</div>
<?php cbg_tool_dropdown(); ?>

		<script type="text/javascript">
		jQuery(document).ready(function ($) {

		
		
		

		
		  //called when key is pressed in textbox
		  $("#water_1").keypress(function (e) {
			 //if the letter is not digit then display error and don't type anything
			 if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
				//display error message
				$("#errmsg1").html("Digits Only").show().fadeOut(2000);
					   return false;
			}
		   });
		  //called when key is pressed in textbox
		  $("#water_2").keypress(function (e) {
			 //if the letter is not digit then display error and don't type anything
			 if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
				//display error message
				$("#errmsg2").html("Digits Only").show().fadeOut(2000);
					   return false;
			}
		   });	
		  //called when key is pressed in textbox
		  $("#waterchange_1").keypress(function (e) {
			 //if the letter is not digit then display error and don't type anything
			 if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
				//display error message
				$("#errmsg7").html("Digits Only").show().fadeOut(2000);
					   return false;
			}
		   });	

			$("#water_3").keydown(function(e){
				var key = e.which;

				// backspace, tab, left arrow, up arrow, right arrow, down arrow, delete, numpad decimal pt, period, enter
				if (key != 8 && key != 9 && key != 37 && key != 38 && key != 39 && key != 40 && key != 46 && key != 110 && key != 190 && key != 13){
					if (key < 48){
										//display error message
				$("#errmsg3").html("Number/Decimal Only").show().fadeOut(2000);
					   return false;
					}
					else if (key > 57 && key < 96){
										//display error message
				$("#errmsg3").html("Number/Decimal Only").show().fadeOut(2000);
					   return false;
					}
					else if (key > 105) {
										//display error message
				$("#errmsg3").html("Number/Decimal Only").show().fadeOut(2000);
					   return false;
					}
					
				}
			});
			$("#waterchange_2").keydown(function(e){
				var key = e.which;

				// backspace, tab, left arrow, up arrow, right arrow, down arrow, delete, numpad decimal pt, period, enter
				if (key != 8 && key != 9 && key != 37 && key != 38 && key != 39 && key != 40 && key != 46 && key != 110 && key != 190 && key != 13){
					if (key < 48){
										//display error message
				$("#errmsg8").html("Number/Decimal Only").show().fadeOut(2000);
					   return false;
					}
					else if (key > 57 && key < 96){
										//display error message
				$("#errmsg8").html("Number/Decimal Only").show().fadeOut(2000);
					   return false;
					}
					else if (key > 105) {
										//display error message
				$("#errmsg8").html("Number/Decimal Only").show().fadeOut(2000);
					   return false;
					}
					
				}
			});	
			$("#normalshower").html($("#water_2").val());
		});
		
		function calcWater() {
			var C1 = jQuery("#water_1").val() * jQuery("#water_2").val();
			jQuery("#waterbaseline_1").val(C1);
			var C2 = C1 * 2.5;
			jQuery("#waterbaseline_2").val(C2);
			var C3 = (C2 * jQuery("#water_3").val()).toFixed(2);
			jQuery("#waterbaseline_3").val(C3);
			var C4 = (jQuery("#water_2").val() * 2.5 * jQuery("#water_3").val()).toFixed(2);
			jQuery("#waterbaseline_4").val(C4);
			jQuery("#normalshower").html(jQuery("#water_2").val());
			
		}
		
		function calcWaterSavings() {
			var C5 = 0;
			if (jQuery("#waterchange_2").val().length === 0 && jQuery("#waterchange_1").val().length > 0) {
					C5 = jQuery("#waterbaseline_3").val() - (jQuery("#water_1").val() * (jQuery("#water_2").val() - jQuery("#waterchange_1").val()) * 2.5 * jQuery("#water_3").val() );
			}
			
			if (jQuery("#waterchange_2").val().length > 0 && jQuery("#waterchange_1").val().length === 0) {
				C5 = jQuery("#waterbaseline_3").val() - (jQuery("#waterbaseline_1").val() * jQuery("#waterchange_2").val() * jQuery("#water_3").val() );
			}			
			
			if (jQuery("#waterchange_2").val().length > 0 && jQuery("#waterchange_1").val().length > 0) {		
					C5 = jQuery("#waterbaseline_3").val() - (jQuery("#water_1").val() * (jQuery("#water_2").val() - jQuery("#waterchange_1").val()) * jQuery("#waterchange_2").val() * jQuery("#water_3").val() );
			}
			
			C5 = C5.toFixed(2);
			if (C5 < 0) {
				C5 = 0;
			}			
			jQuery("#watersaved_1").val(C5);
			jQuery("#watersaved_NL").html("$" + C5);
			
			var C6 = 0;
			if (jQuery("#waterchange_2").val().length === 0 && jQuery("#waterchange_1").val().length > 0) {
				C6 = jQuery("#waterbaseline_2").val() - (jQuery("#water_1").val() * (jQuery("#water_2").val() - jQuery("#waterchange_1").val()) * 2.5);
			}
			
			if (jQuery("#waterchange_2").val().length > 0 && jQuery("#waterchange_1").val().length === 0) {
				C6 = (jQuery("#water_2").val() * 2.5) - (jQuery("#water_2").val() * jQuery("#waterchange_2").val());
			}			
			
			if (jQuery("#waterchange_2").val().length > 0 && jQuery("#waterchange_1").val().length > 0) {
				//alert(parseFloat((jQuery("#water_2").val() * 2.5) - (jQuery("#water_2").val() * jQuery("#waterchange_2").val())));
				//alert(parseFloat((jQuery("#waterchange_1").val() * jQuery("#waterchange_2").val())));
				C6 = jQuery("#waterbaseline_2").val() - (jQuery("#water_1").val() * (jQuery("#water_2").val() - jQuery("#waterchange_1").val()) * jQuery("#waterchange_2").val());
			}	
			if (C6 < 0) {
				C6 = 0;
			}	
			jQuery("#watersaved_2").val(C6);
			
			
		
		}
		</script>
<?php	
	} elseif (is_page( 'cooling' )) {
?>
	<div class="tabs">
		<ul class="tab-links">
			<li id="t1" class="active"><a href="#tab1">Baseline</a></li>
			<li id="t2"><a href="#tab2">Savings</a></li>

		</ul>  
	
	  <div class="tab-content">
		  <div id="tab1" class="tab active">
		  
			<p style="margin-bottom:15px;font-style:italic;">Hover over textboxes for additional information. You must fill in the baseline information—will only take a minute—in order to track your savings.</p>
			<p class="inputs"><strong>What is your average utility bill during the summer months?</strong> $<input type="text" id="cooling_4" class="tooltips" title="Look at your statements from last year's warmest months (when you were likely using air conditioning); add the totals together and divide by the number of months under consideration." value="<?php echo $B4 ?>" style="width:60px;" /><span class="errmsg" id="errmsg4"></span><br />
			
			
			<p class="inputs"><strong>What is your utility's monthly service charge?  </strong> $<input type="text" id="cooling_2" class="tooltips" title="Beyond other fees and the cost of kilo-watt hours consumed, most utilities charge a baseline, monthly fee.  This should be clearly indicated on your bill." value="<?php echo $B2 ?>" style="width:60px;" /><span class="errmsg" id="errmsg2"></span><br />
			
			<br /><br />			
			<input id="cooling_base" type="button" value="Save Cooling Usage >>" style="font-size:14pt;" />
			<hr />
			<span id="coolingsaved" style="display:none;color:red;margin-left:30px;">Cooling Usage Saved!</span>
		<br />				
	</div>
	<div id="tab2" class="tab">	
	
		
		<div style="text-align:right;"><?php cbg_change_savings_date(); ?></div>
		<p class="inputs">This month I raised my thermostat setting by an average of <input id="cooling_5" style="width:60px;" /> degrees Fahrenheit.  After the change, my thermostat setting was still over 75 degrees Fahrenheit. (please select yes or no below)
		<br/><br/>
		<input type="radio" name="cooling75" value="yes" />Yes<br />
		<input type="radio" name="cooling75" value="no" />No<br /><br />
		<?php
			cbg_state_dropdown();
		?>
		<br />
		<span class="errmsg" id="errmsg5"></span></p>
		<p><input type="button" id="cooling_bankit" name="cooling_bankit" value="Update your Savings! >>" style="font-size:14pt;" onclick="calcCoolingSavings()" /><span class="errmsg" id="errmsg6"></span></p>
		<hr />
		<div class="baselinediv2" id="baselineresults">
			
			<h5>COOLING SAVINGS</h5>	
			<?php if ( is_user_logged_in() ) { ?>
				<div><strong>Cooling money saved this month:</strong>&nbsp;&nbsp;<span style="font-size:18pt;font-weight:bold;color:#688571;">$</span><span id="coolingchange_3" style="color:#688571;font-size:18pt;font-weight:bold;"><?php echo money_format('%i', $totcooling); ?></span></div>		

				<div id="coolingbankit_result"></div>	
				<input type="hidden" id="coolingchange_1" />
		<?php } else { ?>
				<div><strong>Cooling money saved:</strong>&nbsp;&nbsp;<span id="coolingchange_NL" style="color:#688571;font-size:18pt;font-weight:bold;"></span></div>		
			
		<?php
			}
		?>				
				<br /><br />
				<a href="javascript:void(0)" id="clickcoolingass1">Our assumptions</a><div id="coolingass1" style="display:none;margin-top:15px;">After consulting several sources, including the Department of Energy, we concluded that a person can expect—on average--to lower his/her utility bill by 3% per degree if the temperature remains above 75 degrees or 1% per degree if below 75 degrees.</div>

				
		</div>	
	
	</div>	
</div>
</div>
<?php cbg_tool_dropdown(); ?>

		<script type="text/javascript">
			jQuery(document).ready(function ($) {
			
					var C2 = ((jQuery("#cooling_4").val() - jQuery("#cooling_2").val()) / jQuery("#cooling_3").val()).toFixed(2);					
					jQuery("#coolingchange_2").val(C2);			
			
			  

				
			  

			$( "#clickcoolingass1" ).click(function() {
				$( "#coolingass1" ).slideToggle( "slow" );
			});			
			
			  //called when key is pressed in textbox
			  $("#cooling_1").keypress(function (e) {
				 //if the letter is not digit then display error and don't type anything
				 if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
					//display error message
					$("#errmsg1").html("Digits Only").show().fadeOut(2000);
						   return false;
				}
			   });
			  $("#cooling_2").on("keyup", function(){
				var valid = /^\d{0,4}(\.\d{0,2})?$/.test(this.value),
					val = this.value;
				
				if(!valid){
				this.value = val.substring(0, val.length - 1);
					$("#errmsg2").html("Currency Only").show().fadeOut(2000);
						   return false;					
				}
				});	
			  //called when key is pressed in textbox
			  $("#cooling_3").keypress(function (e) {
				 //if the letter is not digit then display error and don't type anything
				 if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
					//display error message
					$("#errmsg3").html("Digits Only").show().fadeOut(2000);
						   return false;
				}
			   });				
			  $("#cooling_4").on("keyup", function(){
				var valid = /^\d{0,4}(\.\d{0,2})?$/.test(this.value),
					val = this.value;
				
				if(!valid){
				this.value = val.substring(0, val.length - 1);
					$("#errmsg4").html("Currency Only").show().fadeOut(2000);
						   return false;					
				}
				});	
			  //called when key is pressed in textbox
			  $("#cooling_5").keypress(function (e) {
				 //if the letter is not digit then display error and don't type anything
				 if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
					//display error message
					$("#errmsg5").html("Digits Only").show().fadeOut(2000);
						   return false;
				}
			   });				
		   });
		   
		   function calcCoolingSavings() {
				if (jQuery("#cooling_2").val().length === 0 || jQuery("#cooling_4").val().length === 0 || jQuery("#cooling_5").val().length === 0 ) {
					jQuery("#errmsg6").html("Please fill in all baseline values and degrees raised.");
					return false;
					 
				} else if (jQuery("input:radio[name='cooling75']:checked").length == 0) {
					jQuery("#errmsg6").html("Please fill in whether thermostat was over 75 degrees.");
					return false;
				} else {
					// var C1 = (0.048 * jQuery("#cooling_5").val() * jQuery("#cooling_1").val()).toFixed(2);
					// jQuery("#coolingchange_1").val(C1);
					// var C2 = ((jQuery("#cooling_4").val() - jQuery("#cooling_2").val()) / jQuery("#cooling_3").val()).toFixed(2);					
					// jQuery("#coolingchange_2").val(C2);
					// var C3 = (jQuery("#coolingchange_1").val() * jQuery("#coolingchange_2").val()).toFixed(2);
					var C3;
					if (jQuery( 'input[name=cooling75]:checked' ).val() == 'yes') {
						C3 = (jQuery("#cooling_4").val() - jQuery("#cooling_2").val()) * jQuery("#cooling_5").val() * 0.03;
					} else {
						C3 = (jQuery("#cooling_4").val() - jQuery("#cooling_2").val()) * jQuery("#cooling_5").val() * 0.01;
					}
					
					jQuery("#coolingchange_3").val(C3);
					jQuery("#coolingchange_NL").html("$" + C3);
					
					var stateprice = jQuery("#state_electric").val()/100;
					
					var kwh = jQuery("#coolingchange_3").val()/stateprice;
					
					jQuery("#coolingchange_1").val(kwh);					
				}
				
		   }
	   </script>
<?php
	} elseif (is_page( 'heating' )) {
?>
	<div class="tabs">
		<ul class="tab-links">
			<li id="t1" class="active"><a href="#tab1">Baseline</a></li>
			<li id="t2"><a href="#tab2">Savings</a></li>

		</ul>  
	
	  <div class="tab-content">
		  <div id="tab1" class="tab active">
			
			<p style="margin-bottom:15px;font-style:italic;">Hover over textboxes for additional information. You must fill in the baseline information—will only take a minute—in order to track your savings.</p>
			<p class="inputs"><strong>What is your average utility bill for heating during the winter months?</strong> $<input type="text" id="heating_4" class="tooltips" title="Look at your statements from last year's coolest months (when you were likely using heat); add the totals together and divide by the number of months under consideration." value="<?php echo $B4 ?>" style="width:60px;" /><span class="errmsg" id="errmsg4"></span><br /></p>
			
			
			<p class="inputs"><strong>What is your utility's monthly service charge?  </strong> $<input type="text" id="heating_2" class="tooltips" title="Beyond other fees and the cost of kilo-watt hours consumed, most utilities charge a baseline, monthly fee.  This should be clearly indicated on your bill." value="<?php echo $B2 ?>" style="width:60px;" /><span class="errmsg" id="errmsg2"></span><br />
			</p>
			
			
			<input id="heating_base" type="button" value="Save Heating Usage >>" style="font-size:14pt;" />
			<span id="heatingsaved" style="display:none;color:red;margin-left:30px;">Heating Usage Saved!</span>
		<br />		
	</div>
	<div id="tab2" class="tab">		

		
		<div style="text-align:right;"><?php cbg_change_savings_date(); ?></div>
		<p class="inputs">This month I lowered my thermostat setting by an average of <input id="heating_5" style="width:60px;" /> degrees Fahrenheit. <span class="errmsg" id="errmsg5"></span></p>
		<input type="checkbox" id="electric_heat_cb" /> Check box if using electric heat to track kilowatt hour savings.
		<div id="electric_heat_div" style="display:none;">
		<?php
			cbg_state_dropdown();
		?>
		</div>
		<br /><br />
		<p><input type="button" id="heating_bankit" name="heating_bankit" value="Update your Savings! >>" style="font-size:14pt;" onclick="calcHeatingSavings()" /><span class="errmsg" id="errmsg6"></span></p><br />
		<hr />
		<div class="baselinediv2" id="baselineresults">
		<h5>HEATING SAVINGS</h5>
		<?php if ( is_user_logged_in() ) { ?>
			
			<div><strong>Heating money saved this month:</strong>&nbsp;&nbsp;<span style="font-size:18pt;font-weight:bold;color:#688571;">$</span><span id="heatingchange_3" style="color:#688571;font-size:18pt;font-weight:bold;"><?php echo money_format('%i', $totheating); ?></span></div>				
			

			<div id="heatingbankit_result"></div>	
			<input type="hidden" id="heatingchange_1" />
	<?php } else { ?>
			<div><strong>Heating money saved:</strong>&nbsp;&nbsp;<span id="heatingchange_NL" style="color:#688571;font-size:18pt;font-weight:bold;"></span></div>				
		
	<?php
		}
	?>	
					<br /><br /><a href="javascript:void(0)" id="clickheatingass1">Our assumptions</a><div id="heatingass1" style="display:none;margin-top:15px;">According to several sources, including the Department of Energy, a person can expect to lower his/her utility bill (models account for various types of heating sources, i.e. electric heat pumps, natural gas) by 1%-3% per degree change.  We use the mean— 2% per degree change.</div></p>
		</div>	
		
	</div>
</div>
</div>
<?php cbg_tool_dropdown(); ?>

		<script type="text/javascript">
			jQuery(document).ready(function ($) {
			
					var C2 = (($("#heating_4").val() - $("#heating_2").val()) / $("#heating_3").val()).toFixed(4);
					$("#heatingchange_2").val(C2);			
			
			
		
				
				$( "#heating_base" ).click(function() {
					$( "#heatingsaved" ).show();
				});					
			

				$( "#clickheatingass1" ).click(function() {
					$( "#heatingass1" ).slideToggle( "slow" );
				});
			  //called when key is pressed in textbox
			  $("#heating_1").keypress(function (e) {
				 //if the letter is not digit then display error and don't type anything
				 if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
					//display error message
					$("#errmsg1").html("Digits Only").show().fadeOut(2000);
						   return false;
				}
			   });
			  $("#heating_2").on("keyup", function(){
				var valid = /^\d{0,4}(\.\d{0,2})?$/.test(this.value),
					val = this.value;
				
				if(!valid){
				this.value = val.substring(0, val.length - 1);
					$("#errmsg2").html("Currency Only").show().fadeOut(2000);
						   return false;					
				}
				});	
			  //called when key is pressed in textbox
			  $("#heating_3").keypress(function (e) {
				 //if the letter is not digit then display error and don't type anything
				 if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
					//display error message
					$("#errmsg3").html("Digits Only").show().fadeOut(2000);
						   return false;
				}
			   });				
			  $("#heating_4").on("keyup", function(){
				var valid = /^\d{0,4}(\.\d{0,2})?$/.test(this.value),
					val = this.value;
				
				if(!valid){
				this.value = val.substring(0, val.length - 1);
					$("#errmsg4").html("Currency Only").show().fadeOut(2000);
						   return false;					
				}
				});	
			  //called when key is pressed in textbox
			  $("#heating_5").keypress(function (e) {
				 //if the letter is not digit then display error and don't type anything
				 if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
					//display error message
					$("#errmsg5").html("Digits Only").show().fadeOut(2000);
						   return false;
				}
			   });				
		   });
		   
		   function calcHeatingSavings() {
				if (jQuery("#heating_4").val().length === 0 || jQuery("#heating_5").val().length === 0 || jQuery("#heating_2").val().length === 0) {
					jQuery("#errmsg6").html("Please fill in all baseline values and degrees lowered.");
					return false;
				} else {
					// var C1 = (0.08167 * jQuery("#heating_5").val() * jQuery("#heating_1").val()).toFixed(2);
					// jQuery("#heatingchange_1").val(C1);
					// var C2 = ((jQuery("#heating_4").val() - jQuery("#heating_2").val()) / jQuery("#heating_3").val()).toFixed(4);
					// jQuery("#heatingchange_2").val(C2);
					//var C3 = (jQuery("#heatingchange_1").val() * jQuery("#heatingchange_2").val()).toFixed(2);
					
					var C3 = (jQuery("#heating_4").val() - jQuery("#heating_2").val()) * jQuery("#heating_5").val() * 0.02;
					
					jQuery("#heatingchange_3").val(C3);
					jQuery("#heatingchange_NL").html("$" + C3);
					
					jQuery('#electric_heat_cb').click(function () {
						if (this.checked) {
							var stateprice = jQuery("#state_electric").val()/100;
							var kwh = jQuery("#heatingchange_3").val()/stateprice;
							jQuery("#heatingchange_1").val(kwh);						
						}
					});

					
					

				}
			
		   }
	   </script>		

<?php
	} elseif (is_page( 'grocery-shopping' )) {
?>

	<div class="tabs">
		<ul class="tab-links">
			<li id="t1" class="active"><a href="#tab1">Baseline</a></li>
			<li id="t2"><a href="#tab2">Savings</a></li>

		</ul>  
	
	  <div class="tab-content">
		  <div id="tab1" class="tab active">			
			<p style="margin-bottom:15px;font-style:italic;">Hover over textboxes for additional information. This baseline is NOT required; rather, it is for your information only.  Feel free to SKIP immediately to the savings tab.</p>
			<p class="inputs"><strong>On average, how much do you spend per month on groceries?</strong> $<input type="text" id="grocery_1" class="tooltips" title="Take a look at your grocery bills (thumb through paper receipts or pull up your on-line banking statement) over the last several months.  For example, if you consider six months’ worth of spending, add all six months’ spending together and divide that total by six to get your average monthly spending." value="<?php echo $B1 ?>" style="width:60px;" /><span class="errmsg" id="errmsg1"></span><br />
			</p>
		<br /><br />				
		<input id="grocery_base" type="button" value="Calculate your Baseline >>" style="font-size:14pt;" onclick="calcGrocery()" /><span class="errmsg" id="errmsg16"></span>
		<hr />
		<div class="baselinediv1" id="baselineresults">
		<h5>LIFESTYLE BASELINE</h5>
			As noted above, you spend an average of $ <input id="grocerybaseline_1" style="width:50px;font-weight:bold;" value="<?php echo $B2 ?>" readonly /> per month on groceries.				 
		</div>		

	</div>
	<div id="tab2" class="tab">

		<p style="margin-bottom:15px;font-style:italic;">Hover over textboxes for additional information.</p>
		<div style="text-align:right;"><?php cbg_change_savings_date(); ?></div>
		
		<p class="inputs"><strong>I used several coupons and saved $ <input id="grocery_2" class="tooltips" title="Remember: the tool will allow you to update your savings each time you use coupons." style="width:60px;" />.</strong><br /> 
		<span class="errmsg" id="errmsg2"></span></p>
		<p class="inputs"><strong>I identified several items that for health-related/nutritional or simple cost reasons <i>I elected not to purchase</i>,<br />and I saved $ <input id="grocery_3" class="tooltips" title="Remember: the tool will allow you to update your healthy living/simple living savings each time you go shopping." style="width:60px;" />.</strong><br />
		<span class="errmsg" id="errmsg3"></span></p>
		<p class="inputs"><strong><a href="javascript:void(0)" class="tooltips" title="Each week, try to skip a junk food item, e.g. chips, cookies or other processed snacks.  Eat one less portion of meat (and reap the environmental and health benefits).  If you are not into coupon cutting, most stores now have a handy app that enables you to save money on each shopping trip.  Consider skipping sugary drinks, especially sodas, one week a month.">***Savings Tips***</a></strong><br /><br /></p>			
		<p><input type="button" id="grocery_bankit" name="grocery_bankit" value="Update your Savings! >>" style="font-size:14pt;" onclick="calcGrocerySavings()" /><span class="errmsg" id="errmsg4"></span></p>
	
		<hr />		
		<div class="baselinediv2" id="baselineresults">
		<h5>GROCERY SHOPPING SAVINGS</h5>
	<?php if ( is_user_logged_in() ) { ?>			
			<div><strong>Grocery shopping money saved this month:</strong>&nbsp;&nbsp;<span style="font-size:18pt;font-weight:bold;color:#688571;">$</span><span id="grocerychange_1" style="color:#688571;font-size:18pt;font-weight:bold;"><?php echo money_format('%i', $totgrocery); ?></span></div>				
						
			<div id="grocerybankit_result"></div>	
	<?php } else { ?>
			<div><strong>Grocery shopping money saved:</strong>&nbsp;&nbsp;<span id="grocerychange_NL" style="color:#688571;font-size:18pt;font-weight:bold;"></span></div>				
	<?php
		}
	?>				
		</div>		
	
	</div>
</div>		
</div>
<?php cbg_tool_dropdown(); ?>
		
		<script type="text/javascript">
			jQuery(document).ready(function ($) {	

	
			
				$( "#click1" ).click(function() {
					$( "#help1" ).slideToggle( "slow" );
				});	
			
			  $("#grocery_1").on("keyup", function(){
				var valid = /^\d{0,4}(\.\d{0,2})?$/.test(this.value),
					val = this.value;
				
				if(!valid){
				this.value = val.substring(0, val.length - 1);
					$("#errmsg1").html("Currency Only").show().fadeOut(2000);
						   return false;					
				}
				});			
		   });	
			
			function calcGrocery() {
				if (jQuery("#grocery_1").val().length === 0 ) {
					jQuery("#errmsg16").html("Please fill in the field above.");
					return false;
				} else {
					var C1 = jQuery("#grocery_1").val();
					jQuery("#grocerybaseline_1").val(C1);
				}
			}
			
			function calcGrocerySavings() {
				if (jQuery("#grocery_2").val().length === 0 && jQuery("#grocery_3").val().length === 0) {
					jQuery("#errmsg4").html("Please fill in one or both of the fields above.");
					return false;
				} else {
					var grocery2 = 0;
					if(jQuery("#grocery_2").val().length === 0) {
						grocery2 = 0;
					} else {
						grocery2 = parseFloat(jQuery("#grocery_2").val());
					}
					var grocery3 = 0;
					if(jQuery("#grocery_3").val().length === 0) {
						grocery3 = 0;
					} else {
						grocery3 = parseFloat(jQuery("#grocery_3").val());
					}					
					var C2 = grocery2 + grocery3;
					jQuery("#grocerychange_1").val(C2);
					jQuery("#grocerychange_NL").html("$" + C2);
				}
				
			}
		</script>		
<?php
	} elseif (is_page( 'entertainment' )) {
?>
	<div class="tabs">
		<ul class="tab-links">
			<li id="t1" class="active"><a href="#tab1">Baseline</a></li>
			<li id="t2"><a href="#tab2">Savings</a></li>

		</ul>  
	
	  <div class="tab-content">
		  <div id="tab1" class="tab active">
		
		<p class="ptext"><strong>Note:</strong> From the outset, we want to emphasize that we believe re-creation is a fundamental human need and, by asking people to consider their habits in the area of recreation and entertainment, we are by no means trying to be a “kill joy” or deter such activities.  Indeed, as with the remainder of the site, you are more than welcome to use whatever parts of the tool you find make the most sense.  Having said that, we included entertainment/recreational spending because, for many people, this is an area where there is a fair amount of consumer discretion and choice involved, and thus there is a lot of room for savings and reflection.</p>
		<br />
		<p class="ptext">We are departing from the format offered on the other pages for a host of reasons.  For one, it is difficult to establish a lifestyle baseline because people may not all agree about what “counts” as “entertainment,” and spending can vary quite a bit month-to-month.  Instead, we offer some changes/savings to consider and leave it up to you to construct your own paradigm.</p>
		<br /><br />
		<p class="ptext"><strong>Areas/Inputs to consider:</strong></p>
		<br />
		<p class="ptext"><strong>Eating:</strong> Because restaurants differ in price and because the constituent parts of one’s lunch, breakfast or dinner are variable, it’s nigh impossible to come up with an algorithm to help you decide, in some abstract way, how much you save by, say, taking your lunch to work versus eating out.  But, in <i>specific</i> instances, you may have a pretty good idea how much you spent putting together a sandwich versus the cost of a fast food item you otherwise would have eaten.  Some may want to consider “fasting” or skipping a few meals each month in solidarity with those for whom getting three nutritious meals a day is a rarity.  Or, easier still, you can choose to skip some lattes, beers or donuts each month.</p>
		<br />
		<p class="ptext"><strong>Home entertainment:</strong> Some people spend a lot of money on home entertainment.  You may want to consider, for example, how much money you could save by sacrificing one premium channel on cable.  Many people spend a lot of money compiling large libraries of video games.</p>
		<br />
		<p class="ptext"><strong>Recreation:</strong> A lot of recreational choices carry little to no cost--like taking your kids to the neighborhood playground or walking your dog on a local trail system--and provide great satisfaction.  But most of us also have recreational activities and hobbies that we engage in regularly that come with a significant price tag.  Many people go boating or snow skiing multiple times a year; others take frequent hunting and fishing trips; yet others frequent the local cinema or some other cultural venue.  Again, the point is NOT that you should cease doing these things; rather, the point is to consider how much time and money you actually spend on these activities and, if you are so inclined, to consider if there may be a way to occasionally choose a lower cost yet meaningful option.  Recreation/entertainment is an area that is <i>essential</i> to our well-being, but, in a consumer society such as ours, it is also an area in which it is easy to become overly indulgent and self-centered.</p>
		
	</div>
	<div id="tab2" class="tab">
		
		<div style="text-align:right;"><?php cbg_change_savings_date(); ?></div>
		<p class="inputs">Fill in one or more options below. Remember, you can return to this site and update your savings as frequently as you like.</p><br />

		<p class="inputs">In the area of eating/drinking, I saved $ <input id="entertain_1" style="width:60px;" /> <span class="errmsg" id="errmsg1"></span><br />
		<p class="inputs">In the area of home entertainment, I saved $ <input id="entertain_2" style="width:60px;" /> <span class="errmsg" id="errmsg2"></span><br />
		<p class="inputs">In the area of recreational activities/hobbies, I saved $ <input id="entertain_3" style="width:60px;" /> <span class="errmsg" id="errmsg3"></span><br />
		<p class="inputs">Other: I saved $ <input id="entertain_4" style="width:60px;" /></strong> <span class="errmsg" id="errmsg4"></span><br />
		<p><input type="button" id="entertain_bankit" name="entertain_bankit" value="Update your Savings! >>" style="font-size:14pt;" onclick="calcEntertainSavings()" /><span class="errmsg" id="errmsg5"></span></p>
		<hr />		
		<div class="baselinediv2" id="baselineresults">
		<h5>ENTERTAINMENT/RECREATION SAVINGS</h5>	
	<?php if ( is_user_logged_in() ) { ?>		
			<div><strong>Entertainment/Recreation money saved this month:</strong>&nbsp;&nbsp;<span style="font-size:18pt;font-weight:bold;color:#688571;">$</span><span id="entertainchange_1" style="color:#688571;font-size:18pt;font-weight:bold;"><?php echo money_format('%i', $totentertainmentrecreation); ?></span></div>				

				
				<div id="entertain_bankit_result"></div>
	<?php } else { ?>
			<div><strong>Entertainment/Recreation money saved:</strong>&nbsp;&nbsp;<span id="entertainchange_NL" style="color:#688571;font-size:18pt;font-weight:bold;"></span></div>				

	<?php
		}
	?>			
		</div>
   
	</div>
</div>
</div>
<?php cbg_tool_dropdown(); ?>


	<script type="text/javascript">
		jQuery(document).ready(function ($) {				
		
	
		
		  $("#entertain_1").on("keyup", function(){
			var valid = /^\d{0,4}(\.\d{0,2})?$/.test(this.value),
				val = this.value;			
			if(!valid){
			this.value = val.substring(0, val.length - 1);
				$("#errmsg1").html("Currency Only").show().fadeOut(2000);
					   return false;					
			}
			});		
		  $("#entertain_2").on("keyup", function(){
			var valid = /^\d{0,4}(\.\d{0,2})?$/.test(this.value),
				val = this.value;			
			if(!valid){
			this.value = val.substring(0, val.length - 1);
				$("#errmsg2").html("Currency Only").show().fadeOut(2000);
					   return false;					
			}
			});		
		  $("#entertain_3").on("keyup", function(){
			var valid = /^\d{0,4}(\.\d{0,2})?$/.test(this.value),
				val = this.value;			
			if(!valid){
			this.value = val.substring(0, val.length - 1);
				$("#errmsg3").html("Currency Only").show().fadeOut(2000);
					   return false;					
			}
			});		
		  $("#entertain_4").on("keyup", function(){
			var valid = /^\d{0,4}(\.\d{0,2})?$/.test(this.value),
				val = this.value;			
			if(!valid){
			this.value = val.substring(0, val.length - 1);
				$("#errmsg4").html("Currency Only").show().fadeOut(2000);
					   return false;					
			}
			});					
	   });

		function calcEntertainSavings() {
			var E1;
			var E2;
			var E3;
			var E4;
			if (jQuery("#entertain_1").val().length === 0) {
				E1 = 0;
			} else {
				E1 = jQuery("#entertain_1").val();
			}
			if (jQuery("#entertain_2").val().length === 0) {
				E2 = 0;
			} else {
				E2 = jQuery("#entertain_2").val();
			}
			if (jQuery("#entertain_3").val().length === 0) {
				E3 = 0;
			} else {
				E3 = jQuery("#entertain_3").val();
			}
			if (jQuery("#entertain_4").val().length === 0) {
				E4 = 0;
			} else {
				E4 = jQuery("#entertain_4").val();
			}
			
			var C1 = (parseFloat(E1) + parseFloat(E2) + parseFloat(E3) + parseFloat(E4)).toFixed(2);
			jQuery("#entertainchange_1").val(C1);
			
			jQuery("#entertainchange_NL").html("$" + C1);
		}
   </script>
<?php	
	}
	
elseif (is_page( 'appliances' )) {
?>
	<div class="tabs">
		<ul class="tab-links">
			<li id="t1" class="active"><a href="#tab1">Baseline</a></li>
			<li id="t2"><a href="#tab2">Savings</a></li>

		</ul>  
	
	  <div class="tab-content">
		  <div id="tab1" class="tab active">
		  
		<p class="inputs"><strong>How much does it cost to run your current refrigerator?</strong></p>
		<p>Use the following website to calculate the annual cost of running your refrigerator. Put the yearly cost in the box below. <a href="https://www.energystar.gov/index.cfm?fuseaction=refrig.calculator" target="_blank">Energy Star Calculator</a></p>
		<br /><br />
		<p class="inputs"><strong>Annual Cost of Refrigerator: </strong>$<input type="text" id="appliance_1" value="<?php echo $B1 ?>" /><span class="errmsg" id="errmsg1"></span></p>
		<br />
		<input id="appliance_base" type="button" value="Calculate your Baseline >>" style="font-size:14pt;" onclick="calcAppliance()" /><span class="errmsg" id="errmsg5"></span>
		<hr />
		<div class="baselinediv1" id="baselineresults">
		
			You spend $<input id="appliancebaseline_1" style="width:50px;font-weight:bold;" value="<?php echo $B2 ?>" readonly /> per month.		
			 
		</div>			
	</div>
	<div id="tab2" class="tab">

		<div style="text-align:right;"><?php cbg_change_savings_date(); ?></div>
		<p class="inputs">I replaced my current/older refrigerator with an energy star refrigerator. Use the <a href="https://www.energystar.gov/index.cfm?fuseaction=refrig.calculator" target="_blank">Energy Star Calculator</a> to figure the new annual cost.</p><br />
		<p class="inputs"><strong>The new estimated annual cost to run my new refrigerator is:</strong> $<input type="text" id="appliance_2" value="<?php echo $newfridge ?>" /><span class="errmsg" id="errmsg2"></span></p><br />
		<p>
		<?php
			cbg_state_dropdown();
		?>		
		</p><br /><br />
		<p><input type="button" id="appliance_bankit" name="appliance_bankit" value="Update your Savings! >>" style="font-size:14pt;" onclick="calcApplianceSavings()" /><span class="errmsg" id="errmsg3"></span></p>
		<hr />		
		<div class="baselinediv2" id="baselineresults">
		<h5>APPLIANCE SAVINGS</h5>	
	<?php if ( is_user_logged_in() ) { ?>			
			<div><strong>Appliance money saved this month:</strong>&nbsp;&nbsp;<span style="font-size:18pt;font-weight:bold;color:#688571;">$</span><span id="appliancechange_1" style="color:#688571;font-size:18pt;font-weight:bold;"><?php echo money_format('%i', $totappliance); ?></span></div>				

				
				<div id="appliance_bankit_result"></div>
				<input type="hidden" id="appliancechange_2" />
	<?php } else {  ?>
			<div><strong>Appliance money saved:</strong>&nbsp;&nbsp;<span id="appliancechange_NL" style="color:#688571;font-size:18pt;font-weight:bold;"></span></div>				
	<?php
	}
	?>					
		</div>
   
	</div>
</div>
</div>
<?php cbg_tool_dropdown(); ?>


	<script type="text/javascript">
		jQuery(document).ready(function ($) {				
		
		
		
		  $("#appliance_1").on("keyup", function(){
			var valid = /^\d{0,4}(\.\d{0,2})?$/.test(this.value),
				val = this.value;			
			if(!valid){
			this.value = val.substring(0, val.length - 1);
				$("#errmsg1").html("Currency Only").show().fadeOut(2000);
					   return false;					
			}
			});		
		  $("#appliance_2").on("keyup", function(){
			var valid = /^\d{0,4}(\.\d{0,2})?$/.test(this.value),
				val = this.value;			
			if(!valid){
			this.value = val.substring(0, val.length - 1);
				$("#errmsg2").html("Currency Only").show().fadeOut(2000);
					   return false;					
			}
			});				
				
	   });
			
		function calcAppliance() {
				if (jQuery("#appliance_1").val().length === 0 ) {
					jQuery("#errmsg1").html("Please fill in this field.");
					return false;
				} else {
					var xy = jQuery("#appliance_1").val()/12;
					var C1 = xy.toFixed(2);
					jQuery("#appliancebaseline_1").val(C1);
				}
		}
	   
		function calcApplianceSavings() {
				if (jQuery("#appliance_2").val().length === 0 ) {
					jQuery("#errmsg2").html("Please fill in this field.");
					return false;
				} else {
					var C1 = jQuery("#appliance_2").val()/12;
					var C2 = (jQuery("#appliance_1").val()/12)-C1;
					var rndC2 = Math.round(C2 * 100) / 100;
					jQuery("#appliancechange_1").val(rndC2);
					jQuery("#appliancechange_NL").html("$" + rndC2);
					
					var stateprice = jQuery("#state_electric").val()/100;
					var kwh = C2/stateprice;
					jQuery("#appliancechange_2").val(kwh);	
					
					
				}			
		}
   </script>
<?php	
	}	
	
elseif (is_page( 'recycling' )) {
?>
	<div class="tabs">
		<ul class="tab-links">
			<li id="t1" class="active"><a href="#tab1">Baseline</a></li>
			<li id="t2"><a href="#tab2">Savings</a></li>

		</ul>  
	
	  <div class="tab-content">
		  <div id="tab1" class="tab active">
		  
		<p class="ptext">Many people live in cities that do not provide curbside recycling.  Or, even if they DO have such an option, they still use recycling centers.  There is no need for a baseline measure for this part of our tool.  Start recycling!  Help the planet and save some money at the same time.</p>

	</div>
	<div id="tab2" class="tab">
		
		<div style="text-align:right;"><?php cbg_change_savings_date(); ?></div>
		

		<p class="inputs">I took my recyclable materials to a local recycling center and received $ <input id="recycling_1" /> <span class="errmsg" id="errmsg1"></span><br />

		<p><input type="button" id="recycling_bankit" name="recycling_bankit" value="Update your Savings! >>" style="font-size:14pt;" onclick="calcRecyclingSavings()" /><span class="errmsg" id="errmsg5"></span></p>
		<hr />		
		<div class="baselinediv2" id="baselineresults">
		<h5>RECYCLING SAVINGS</h5>		
		
	<?php if ( is_user_logged_in() ) { ?>		
		
			<div><strong>Recycling money saved this month:</strong>&nbsp;&nbsp;<span style="font-size:18pt;font-weight:bold;color:#688571;">$</span><span id="recyclingchange_1" style="color:#688571;font-size:18pt;font-weight:bold;"><?php echo money_format('%i', $totrecycling); ?></span></div>				

				
				<div id="recycling_bankit_result"></div>
	<?php } else { ?>
		<div><strong>Recycling money saved:</strong>&nbsp;&nbsp;<span id="recyclingchange_NL" style="color:#688571;font-size:18pt;font-weight:bold;"></span></div>
	<?php	
		}
	?>			
		</div>
   
	</div>
</div>
</div>
<?php cbg_tool_dropdown(); ?>


	<script type="text/javascript">
		jQuery(document).ready(function ($) {				
	
		
		
		  $("#recycling_1").on("keyup", function(){
			var valid = /^\d{0,4}(\.\d{0,2})?$/.test(this.value),
				val = this.value;			
			if(!valid){
			this.value = val.substring(0, val.length - 1);
				$("#errmsg1").html("Currency Only").show().fadeOut(2000);
					   return false;					
			}
			});		
			
	   });

		function calcRecyclingSavings() {
			var C1 = jQuery("#recycling_1").val();
			jQuery("#recyclingchange_1").val(C1);
			jQuery("#recyclingchange_NL").html("$" + C1);
			
		}
   </script>
<?php	
	}	
	
	
	
?>
				<!--<div style="font-weight:bold;color:#688571;font-size:12pt;">Total amount saved (from all types of Change):&nbsp;&nbsp;<span style="font-size:24pt;">$</span><span id="savingscount" style="font-size:24pt;"></span></div>
		-->
				<script type="text/javascript">

				
					var options = {
					  useEasing : true, 
					  useGrouping : true, 
					  separator : ',', 
					  decimal : '.' 
					}
					//var demo = new countUp("transsaved_1", 0.00, <?php echo $tottrans ?>, 2, 2.5, options);
					//demo.start();				
				</script>
<?php
}

function cbg_tool_dropdown() {
?>
	<br />
	<strong>Navigate to another tool:</strong>&nbsp;
	<select id="tooldropdown">
		<option value="">---Select---</option>
		<option value="appliances">Appliances</option>
		<option value="cooling">Cooling</option>
		<option value="entertainment">Entertainment</option>
		<option value="grocery-shopping">Grocery Shopping</option>
		<option value="heating">Heating</option>
		<option value="recycling">Recycling</option>		
		<option value="transportation">Transportation</option>
		<option value="water">Water</option>		
	</select>
	
	<script type="text/javascript">
		jQuery(document).ready(function ($) {	
			$("#tooldropdown").change(function() {
				window.location = "/" + $("#tooldropdown").val() + "/";
			});
		});
	</script>
<?php
}

function cbg_state_dropdown() {
	$user_ID = get_current_user_id();
	$user_state = get_user_meta($user_ID, 'state', true);
?>
	
	Select your state to calculate the amount of electricity saved: <select id="state_electric">
		<option value='9.84'>---Select your state---</option>
		<option value='9.18' <?php if ($user_state == "Alabama") { echo " selected"; } ?>>Alabama</option>
		<option value='16.3' <?php if ($user_state == "Alaska") { echo " selected"; } ?>>Alaska</option>
		<option value='9.81' <?php if ($user_state == "Arizona") { echo " selected"; } ?>>Arizona</option>
		<option value='7.62' <?php if ($user_state == "Arkansas") { echo " selected"; } ?>>Arkansas</option>
		<option value='13.5' <?php if ($user_state == "California") { echo " selected"; } ?>>California</option>
		<option value='9.39'<?php if ($user_state == "Colorado") { echo " selected"; } ?>>Colorado</option>
		<option value='15.5' <?php if ($user_state == "Connecticut") { echo " selected"; } ?>>Connecticut</option>
		<option value='11.1' <?php if ($user_state == "Delaware") { echo " selected"; } ?>>Delaware</option>
		<option value='11.9' <?php if ($user_state == "District of Columbia") { echo " selected"; } ?>>District of Columbia</option>
		<option value='10.4' <?php if ($user_state == "Florida") { echo " selected"; } ?>>Florida</option>
		<option value='9.37' <?php if ($user_state == "Georgia") { echo " selected"; } ?>>Georgia</option>
		<option value='34' <?php if ($user_state == "Hawaii") { echo " selected"; } ?>>Hawaii</option>
		<option value='6.92' <?php if ($user_state == "Idaho") { echo " selected"; } ?>>Idaho</option>
		<option value='8.4' <?php if ($user_state == "Illinois") { echo " selected"; } ?>>Illinois</option>
		<option value='8.29' <?php if ($user_state == "Indiana") { echo " selected"; } ?>>Indiana</option>
		<option value='7.71' <?php if ($user_state == "Iowa") { echo " selected"; } ?>>Iowa</option>
		<option value='9.33' <?php if ($user_state == "Kansas") { echo " selected"; } ?>>Kansas</option>
		<option value='7.26' <?php if ($user_state == "Kentucky") { echo " selected"; } ?>>Kentucky</option>
		<option value='6.9' <?php if ($user_state == "Louisiana") { echo " selected"; } ?>>Louisiana</option>
		<option value='11.8' <?php if ($user_state == "Maine") { echo " selected"; } ?>>Maine</option>
		<option value='11.3' <?php if ($user_state == "Maryland") { echo " selected"; } ?>>Maryland</option>
		<option value='13.8' <?php if ($user_state == "Massachusetts") { echo " selected"; } ?>>Massachusetts</option>
		<option value='10.98' <?php if ($user_state == "Michigan") { echo " selected"; } ?>>Michigan</option>
		<option value='8.86' <?php if ($user_state == "Minnesota") { echo " selected"; } ?>>Minnesota</option>
		<option value='8.6' <?php if ($user_state == "Mississippi") { echo " selected"; } ?>>Mississippi</option>
		<option value='8.53' <?php if ($user_state == "Missouri") { echo " selected"; } ?>>Missouri</option>
		<option value='8.25' <?php if ($user_state == "Montana") { echo " selected"; } ?>>Montana</option>
		<option value='8.37' <?php if ($user_state == "Nebraska") { echo " selected"; } ?>>Nebraska</option>
		<option value='8.95' <?php if ($user_state == "Nevada") { echo " selected"; } ?>>Nevada</option>
		<option value='14.2' <?php if ($user_state == "New Hampshire") { echo " selected"; } ?>>New Hampshire</option>
		<option value='13.7' <?php if ($user_state == "New Jersey") { echo " selected"; } ?>>New Jersey</option>
		<option value='8.83' <?php if ($user_state == "New Mexico") { echo " selected"; } ?>>New Mexico</option>
		<option value='15.2' <?php if ($user_state == "New York") { echo " selected"; } ?>>New York</option>
		<option value='9.15' <?php if ($user_state == "North Carolina") { echo " selected"; } ?>>North Carolina</option>
		<option value='7.83' <?php if ($user_state == "North Dakota") { echo " selected"; } ?>>North Dakota</option>
		<option value='9.12' <?php if ($user_state == "Ohio") { echo " selected"; } ?>>Ohio</option>
		<option value='7.54'<?php if ($user_state == "Oklahoma") { echo " selected"; } ?>>Oklahoma</option>
		<option value='8.21' <?php if ($user_state == "Oregon") { echo " selected"; } ?>>Oregon</option>
		<option value='9.91' <?php if ($user_state == "Pennsylvania") { echo " selected"; } ?>>Pennsylvania</option>
		<option value='12.7' <?php if ($user_state == "Rhode Island") { echo " selected"; } ?>>Rhode Island</option>
		<option value='9.1' <?php if ($user_state == "South Carolina") { echo " selected"; } ?>>South Carolina</option>
		<option value='8.49' <?php if ($user_state == "South Dakota") { echo " selected"; } ?>>South Dakota</option>
		<option value='9.27' <?php if ($user_state == "Tennessee") { echo " selected"; } ?>>Tennessee</option>
		<option value='8.55' <?php if ($user_state == "Texas") { echo " selected"; } ?>>Texas</option>
		<option value='7.84'<?php if ($user_state == "Utah") { echo " selected"; } ?>>Utah</option>
		<option value='14.2' <?php if ($user_state == "Vermont") { echo " selected"; } ?>>Vermont</option>
		<option value='9.07' <?php if ($user_state == "Virginia") { echo " selected"; } ?>>Virginia</option>
		<option value='6.94' <?php if ($user_state == "Washington") { echo " selected"; } ?>>Washington</option>
		<option value='8.14' <?php if ($user_state == "West Virginia") { echo " selected"; } ?>>West Virginia</option>
		<option value='10.3' <?php if ($user_state == "Wisconsin") { echo " selected"; } ?>>Wisconsin</option>
		<option value='7.19'<?php if ($user_state == "Wyoming") { echo " selected"; } ?>>Wyoming</option>	
	</select>
<br /><span style="font-size:8pt">Source: <a href="http://www.eia.gov/electricity/state/" target="_blank">U.S. Energy Information Administration</a></span>
<?php
}

function cbg_change_savings_date() {
?>
			
			<select name="month4" id="month4">
<?php       
			$months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");			
				for ($i=0; $i < count($months); $i++)
				  {
					   $mn = 1 + $i;
					   if (!empty($_GET['themonth'])) {
							   if($months[$i] == $_GET['themonth'])
									{
											echo "<option selected value=" . $_GET['themonth'] . ">" . $_GET['themonth'] . "</option> \n";
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
			<select name="year4" id="year4">
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
			<input type="button" id="changetime3" value="Go" />
			<hr />
			
			
<?php
}