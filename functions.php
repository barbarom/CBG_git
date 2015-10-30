<?php


// Add the JS
function cbg_savings() {
  wp_enqueue_script( 'script-name', 'http://www.change-based-giving.org/cbg/wp-content/themes/virtue-cbg/js/savings.js', array('jquery'), '1.0.0', true );
  wp_localize_script( 'script-name', 'MyAjax', array(
    // URL to wp-admin/admin-ajax.php to process the request
    'ajaxurl' => admin_url( 'admin-ajax.php' ),
 
    // generate a nonce with a unique ID "myajax-post-comment-nonce"
    // so that you can check it later when an AJAX request is sent
    'security' => wp_create_nonce( 'sdjhKJH798fdkjKL989ee23' )
  ));
}
add_action( 'wp_enqueue_scripts', 'cbg_savings' );
 
// The function that handles the AJAX request
function my_action_callback() {
   check_ajax_referer( 'sdjhKJH798fdkjKL989ee23', 'security' );
   
   $currentmonth = intval(date('m'));
   $currentyear = intval(date('Y'));
   $state = $_POST['state'];
   $amount = $_POST['amount'];
   $userid = $_POST['userid'];	
   $savingstype = $_POST['savingstype'];
   $resourceamount = $_POST['resourceamount'];
   $resourcetype = $_POST['resourcetype'];
   $themonth = $_POST['month'];
   $theyear = $_POST['year'];
   $newfridge = $_POST['newfridge'];
   date_default_timezone_set('America/Chicago');
   $title = $userid . "_" . $themonth . "_" . $theyear . "_" . $savingstype;
   $title_r = $userid . "_" . $themonth . "_" . $theyear . "_" . $resourcetype;
   
   //Add user's state and save in user meta
   if (!empty($state)) {
		if (!empty($userid)) {
			update_user_meta( $userid, 'state', $state );
		}
   }
   
   
   $args_savings = array(
		'author' =>  $userid,
		'post_type' => 'saving',
		'posts_per_page' => -1,
		'meta_query' => array(
			'relation' => 'AND',
			array(
				'key'     => 'savings_month',
				'value'   => $themonth
			),
			array(
				'key' => 'savings_year',
				'value'   => $theyear				
			)		
		),
		'tax_query' => array(
			array(
				'taxonomy' => 'savings_types',
				'field' => 'slug',
				'terms' => $savingstype
			)
		)					
   
   );
   $saving_posts = get_posts($args_savings);	

   $args_resources = array(
		'author' =>  $userid,
		'post_type' => 'saved_resource',
		'posts_per_page' => -1,
		'meta_query' => array(
			'relation' => 'AND',
			array(
				'key'     => 'resource_month',
				'value'   => $themonth
			),
			array(
				'key' => 'resource_year',
				'value'   => $theyear				
			)		
		),
		'tax_query' => array(
			array(
				'taxonomy' => 'resource_types',
				'field' => 'slug',
				'terms' => $resourcetype
			)
		)					
   
   );
   $resource_posts = get_posts($args_resources);	   
   
   //For heating and cooling, we are replacing the savings if they exist for that month so that the savings do not accumulate like the other resources. Raising or lowering the temp is assumed to be less frequent than other resource savings.

   
	   if ($savingstype == 'heating' && !empty($saving_posts)) {
			foreach ( $saving_posts as $heating_post ) : setup_postdata( $heating_post ); 
				$heatingid = $heating_post->ID;			
			endforeach; 
			
			$my_post = array(
			  'ID' => $heatingid,
			  'post_title' => $title
			);
			wp_update_post( $my_post );		
			update_post_meta($heatingid, 'amount', $amount);
			cbg_send_savings_email('Home Heating', $userid);
			
	   } else if ($savingstype == 'cooling' && !empty($saving_posts)) {
			foreach ( $saving_posts as $cooling_post ) : setup_postdata( $cooling_post ); 
				$coolingid = $cooling_post->ID;			
			endforeach;  

			$my_post = array(
			  'ID' => $coolingid,
			  'post_title' => $title
			);
			wp_update_post( $my_post );		
			update_post_meta($coolingid, 'amount', $amount);		
			cbg_send_savings_email('Home Cooling', $userid);
			
			
	   } else if ($savingstype == 'appliance' && !empty($saving_posts)) {
			foreach ( $saving_posts as $appliance_post ) : setup_postdata( $appliance_post ); 
				$applianceid = $appliance_post->ID;			
			endforeach;  

			$my_post = array(
			  'ID' => $applianceid,
			  'post_title' => $title
			);
			wp_update_post( $my_post );		
			update_post_meta($applianceid, 'amount', $amount);		
			update_post_meta($applianceid, 'newfridge', $newfridge);
			cbg_send_savings_email('Home Appliances', $userid);
			
	   } else {   
			
		   if(!empty($saving_posts) && $savingstype != 'heating' && $savingstype != 'cooling' && $savingstype != 'appliance') {
				//update existing savings post (with cumulative amount, unlike heating/cooling)
				foreach ( $saving_posts as $saving_post ) : setup_postdata( $saving_post ); 
					$postid = $saving_post->ID;			
				endforeach; 
				
				$get_old_post_amount = get_post_meta( $postid, 'amount', true );
				
				$my_post = array(
				  'ID' => $postid,
				  'post_title' => $title
				);
				$newamount = $amount + (float)$get_old_post_amount;
				wp_update_post( $my_post );		
				update_post_meta($postid, 'amount', $newamount); 
				cbg_send_savings_email($savingstype, $userid);				
		   } else if (empty($savings_posts)) {
				//insert new savings post
				$p = array(
					  'post_title'    => $title,
					  'post_type'     => 'saving',	
					  'post_status'   => 'publish',
					  'post_author'   => $userid		  
					);
				$post_ID = wp_insert_post($p);
				add_post_meta($post_ID, 'amount', $amount, true);
				add_post_meta($post_ID, 'savings_month', $themonth);
				add_post_meta($post_ID, 'savings_year', $theyear);
				wp_set_object_terms( $post_ID, $savingstype, 'savings_types' );  
				cbg_send_savings_email($savingstype, $userid);				
		   }
		   }
		//RESOURCES 
		if (!empty($resourceamount)) {
		   if ($resourcetype == 'heating-2' && !empty($resource_posts)) {
			
				foreach ( $resource_posts as $heating_resource ) : setup_postdata( $heating_resource ); 
					$heatingresourceid = $heating_resource->ID;			
				endforeach; 
				
				$my_post2 = array(
				  'ID' => $heatingresourceid,
				  'post_title' => $title_r
				);
				wp_update_post( $my_post2 );		
				update_post_meta($heatingresourceid, 'resourceamount', $resourceamount);		
				
		   } else if ($resourcetype == 'cooling-2' && !empty($resource_posts)) {
				
				foreach ( $resource_posts as $cooling_resource ) : setup_postdata( $cooling_resource ); 
					$coolingresourceid = $cooling_resource->ID;			
				endforeach;		
				
				$my_post2 = array(
				  'ID' => $coolingresourceid,
				  'post_title' => $title_r
				);
				wp_update_post( $my_post2 );		
				update_post_meta($coolingresourceid, 'resourceamount', $resourceamount);
			
			
			} else if ($resourcetype == 'appliance-2' && !empty($resource_posts)) {
				
				foreach ( $resource_posts as $appliance_resource ) : setup_postdata( $appliance_resource ); 
					$applianceresourceid = $appliance_resource->ID;			
				endforeach;		
				
				$my_post2 = array(
				  'ID' => $applianceresourceid,
				  'post_title' => $title_r
				);
				wp_update_post( $my_post2 );		
				update_post_meta($applianceresourceid, 'resourceamount', $resourceamount);
			
			
			} else {
			   if(!empty($resource_posts) && $resourcetype != 'heating-2' && $resourcetype != 'cooling-2' && $resourcetype != 'appliance-2') {
					//update existing savings post (with cumulative amount, unlike heating/cooling)
					foreach ( $resource_posts as $resource_post ) : setup_postdata( $resource_post ); 
						$postid = $resource_post->ID;			
					endforeach; 
					
					$get_old_post_amount = get_post_meta( $postid, 'resourceamount', true );
					
					$my_post = array(
					  'ID' => $postid,
					  'post_title' => $title_r
					);
					$newamount = $resourceamount + (float)$get_old_post_amount;
					wp_update_post( $my_post );		
					update_post_meta($postid, 'resourceamount', $newamount);   
			   } else if (empty($resource_posts)) {						
			
					$r = array(
					  'post_title'    => $title_r,
					  'post_type'     => 'saved_resource',	
					  'post_status'   => 'publish',
					  'post_author'   => $userid
					);
					//var_dump($r);
					$rpost_ID = wp_insert_post($r);
					add_post_meta($rpost_ID, 'resourceamount', $resourceamount, true);
					add_post_meta($rpost_ID, 'resource_month', $themonth);
					add_post_meta($rpost_ID, 'resource_year', $theyear);
					wp_set_object_terms( $rpost_ID, $resourcetype, 'resource_types' );						
			}	
			
		}
  }
	
?>

<?php

   die(); // this is required to return a proper result
}
add_action( 'wp_ajax_my_action', 'my_action_callback' );

function my_base_callback() {
	check_ajax_referer( 'sdjhKJH798fdkjKL989ee23', 'security' );
	$userid = $_POST['userid'];	
	$baselinetype = $_POST['baselinetype'];	
	$B1 = $_POST['B1'];
	$B2 = $_POST['B2'];
	$B3 = $_POST['B3'];
	$B4 = $_POST['B4'];
	$B5 = $_POST['B5'];
	$B6 = $_POST['B6'];
	$B7 = $_POST['B7'];
	$title = $userid . "_" . $baselinetype;
	
   $postexists = get_page_by_title( $title, 'OBJECT', 'baseline' );
   if ( !empty ($postexists) ) {
		$updatepost = array(
			'ID' => $postexists->ID		
		);
		wp_update_post($updatepost);   
		update_post_meta($postexists->ID, 'B1', $B1);
		update_post_meta($postexists->ID, 'B2', $B2);
		update_post_meta($postexists->ID, 'B3', $B3);
		update_post_meta($postexists->ID, 'B4', $B4);
		update_post_meta($postexists->ID, 'B5', $B5);
		update_post_meta($postexists->ID, 'B6', $B6);
		update_post_meta($postexists->ID, 'B7', $B7);
   } else {
		$p = array(
			  'post_title'    => $title,
			  'post_type'     => 'baseline',	
			  'post_status'   => 'publish',
			  'post_author'   => $userid		  
			);
		$post_ID = wp_insert_post($p);
		add_post_meta($post_ID, 'B1', $B1, true);
		add_post_meta($post_ID, 'B2', $B2, true);
		add_post_meta($post_ID, 'B3', $B3, true);
		add_post_meta($post_ID, 'B4', $B4, true);
		add_post_meta($post_ID, 'B5', $B5, true);
		add_post_meta($post_ID, 'B6', $B6, true);
		add_post_meta($post_ID, 'B7', $B7, true);
		wp_set_object_terms( $post_ID, $baselinetype, 'baseline_types' );
	}	
	die();
}
add_action( 'wp_ajax_my_base', 'my_base_callback' );

function my_lookup_callback() {
	check_ajax_referer( 'sdjhKJH798fdkjKL989ee23', 'security' );
	$userid = $_POST['userid'];	
	$themonth = $_POST['month'];
	$theyear = $_POST['year'];
	$savings_cat = $_POST['savings_category'];
	
	$args = array(
		'author' => $userid,
		'post_type'  => 'saving',
		'meta_query' => array(
			'relation' => 'AND',
			array(
				'key'     => 'savings_month',
				'value'   => $themonth
			),
			array(
				'key' => 'savings_year',
				'value'   => $theyear				
			)		
		),
		'tax_query' => array(
			array(
				'taxonomy' => 'savings_types',
				'field'    => 'slug',
				'terms'    => $savings_cat				
			),
		)		
	);
	$posts = get_posts( $args );	
	$ttlamt = 0;
	if($posts) {
		foreach($posts as $post) {
			$amt = get_post_meta( $post->ID, 'amount', true );
			$ttlamt = $ttlamt + (float)$amt;
			//var_dump($amt);
			//echo $amt . "<br />";
		}
		
	} else {
		echo "No Data for this time period";
	}
	//echo $ttlamt;
	setlocale(LC_MONETARY, 'en_US.UTF-8');
	echo money_format('%n', $ttlamt); 
	die();
}
add_action( 'wp_ajax_nopriv_my_lookup', 'my_lookup_callback' );
add_action( 'wp_ajax_my_lookup', 'my_lookup_callback' );

//Code to store payment info and generate email when PayPal transaction is made.
add_action('paypal_ipn_for_wordpress_txn_type_web_accept', 'cbg_process_payment_for_community', 10, 1);
 
function cbg_process_payment_for_community( $posted )
{
    // Parse data from IPN $posted[] array
    $first_name = isset($posted['first_name']) ? $posted['first_name'] : '';
    $last_name = isset($posted['last_name']) ? $posted['last_name'] : '';
	$payment_fee = isset($posted['payment_fee']) ? $posted['payment_fee'] : '';
	$payment_gross = isset($posted['payment_gross']) ? $posted['payment_gross'] : '';
    $payer_email = isset($posted['payer_email']) ? $posted['payer_email'] : '';
    $txn_id = isset($posted['txn_id']) ? $posted['txn_id'] : '';
	$item_name = isset($posted['item_name']) ? $posted['item_name'] : '';
	$payment_date = isset($posted['payment_date']) ? $posted['payment_date'] : '';
	$custom = isset($posted['custom']) ? $posted['custom'] : '';
	
	$customparts = explode("|", $custom);
	$city = $customparts[0];
	$userid = $customparts[1];
	$useremail = $customparts[2];
	
	$gross = floatval($payment_gross);
	$fee = floatval($payment_fee);
	$total_donation = $gross - $fee;
	
	
	//Insert Payment Post
	// Create post object
	$my_post = array(
	  'post_title'    => $txn_id,
	  'post_content'  => $total_donation,
	  'post_status'   => 'publish',
	  'post_type' => 'payment'
	);

	// Insert the post into the database
	$newpaymentid = wp_insert_post( $my_post );	
	
	
	
	add_post_meta($newpaymentid, 'gross_donation', $gross);
	add_post_meta($newpaymentid, 'paypal_fee', $fee);
	add_post_meta($newpaymentid, 'user_email', $useremail);
	add_post_meta($newpaymentid, 'user_id', $userid);
	
	$taxonomyid = 0;
	if ($city == "mathare") {
		$taxonomyid = 32;
	} else if ($city == "sanjosepalmas") {
		$taxonomyid = 33;
	} else if ($city == "kolkata") {
		$taxonomyid = 34;
	} else if ($city == "lima") {
		$taxonomyid = 35;
	}
	//Set communities taxonomy to appropriate location.	
	wp_set_object_terms( $newpaymentid, $taxonomyid, 'communities');
	//Set payment status taxonomy to "Not Paid to Community" default
	wp_set_object_terms( $newpaymentid, 37, 'payment_status');
	
	//Send EMAIL here
	$confirm_msg = "Change Based Giving and our partners on the front lines in urban slums thank you for your donation!<br /><br />Your donation of <strong>$" . $payment_gross . "</strong> has been received for the community of <strong>" . $item_name . "</strong><br/>Donation received: <strong>" . $payment_date . "</strong>";
	
	$headers = 'From: Change-Based Giving <info@change-based-giving.org>';
	
	//add_filter( 'wp_mail_content_type', 'set_html_content_type' );
	
	
	//Check if the user is unsubscribed. If yes, don't send email
	$unsubscribe_status = get_user_meta($userid, 'unsubscribe');	
	if (empty($unsubscribe_status)) {	
		wp_mail( $payer_email, 'Donation to Change-Based Giving', $confirm_msg, $headers );		
	}
	//remove_filter( 'wp_mail_content_type', 'set_html_content_type' );	
 
    /**
     * At this point you can use the data to generate email notifications,
     * update your local database, hit 3rd party web services, or anything
     * else you might want to automate based on this type of IPN.
     */
}

function set_html_content_type() {
	return 'text/html';
}

//http://wordpress.stackexchange.com/questions/87261/checkboxes-in-registration-form
// REGISTRATION
add_action( 'register_form', 'signup_fields_wpse_87261' );
add_action( 'user_register', 'handle_signup_wpse_87261', 10, 2 );

// PROFILE
add_action( 'show_user_profile', 'user_field_wpse_87261' );
add_action( 'personal_options_update', 'save_profile_fields_87261' );

// USER EDIT
add_action( 'edit_user_profile', 'user_field_wpse_87261' );
add_action( 'edit_user_profile_update', 'save_profile_fields_87261' );

function signup_fields_wpse_87261() {
?>
<p style="margin-bottom:12px;font-style:italic;">***Please check your junk folder in case an email from CBG is interpreted as spam.***</p>
    <label>
        <input type="checkbox" name="launch_campaign" id="launch_campaign" /> 
		Do you want to be a part of our launch campaign?
    </label>
    <br /><br /><br />

<?php
}

function handle_signup_wpse_87261( $user_id, $data = null ) 
{
    $feat_a = isset( $_POST['launch_campaign'] ) ? $_POST['launch_campaign'] : false;

    if ( $feat_a ) 
    {
        add_user_meta( $user_id, 'launch_campaign', $feat_a );
    }

}

function user_field_wpse_87261( $user ) 
{
    $feat_a = get_user_meta( $user->ID, 'launch_campaign', true );
?>
    <h3><?php _e('Launch Campaign'); ?></h3>
    <table class="form-table">
        <tr>
            <td>
                <label><?php 
                    printf(
                        '<input type="checkbox" name="launch_campaign" id="launch_campaign" %1$s />',
                        checked( $feat_a, 'on', false )
                    );
                    ?>
                    <span class="description"><?php _e('Part of the Launch Campaign?'); ?></span>
                    </label>
            </td>
        </tr>
    </table>
<?php 
}

function save_profile_fields_87261( $user_id ) 
{
    $feat_a = isset( $_POST['launch_campaign'] ) ? $_POST['launch_campaign'] : false;

    update_usermeta( $user_id, 'launch_campaign', $feat_a );

}

function cbg_send_savings_email($savingstype, $userid) {
	//Send EMAIL here
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
	$user_email = $current_user->user_email;
	
	$confirm_msg = "Way to go " . $showname . "!!<br /><br />You made a change (or took credit for a good habit) in the area of <strong>" . ucfirst( $savingstype ) . "</strong>!!";
	
	//$headers = 'From: Change-Based Giving <change.based.giving@gmail.com>';
	
	//add_filter( 'wp_mail_content_type', 'set_html_content_type' );
	
	//Check if the user is unsubscribed. If yes, don't send email
	$unsubscribe_status = get_user_meta($userid, 'unsubscribe');	
	if (empty($unsubscribe_status)) {
		wp_mail( $user_email, 'Congratulations on your Change!', $confirm_msg );		
	}
	
	//remove_filter( 'wp_mail_content_type', 'set_html_content_type' );

}


?>