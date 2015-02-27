<?php
function cbg_add_tabs_js() {
	wp_enqueue_script(
		'custom-script',
		get_stylesheet_directory_uri() . '/js/jquery-ui-1.10.4.custom.js',
		array( 'jquery' )
	);
}
add_action( 'wp_enqueue_scripts', 'cbg_add_tabs_js' );

function cbg_add_countUp_js() {
	wp_enqueue_script(
		'custom-script',
		get_stylesheet_directory_uri() . '/js/countUp.js',
		array( 'jquery' )
	);
}

add_action( 'wp_enqueue_scripts', 'cbg_add_countUp_js' );

// Add the JS
function cbg_savings() {
  wp_enqueue_script( 'script-name', 'http://www.change-based-giving.org/cbg/wp-content/themes/twentytwelve-cbg/js/savings.js', array('jquery'), '1.0.0', true );
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
 
   $amount = $_POST['amount'];
   $userid = $_POST['userid'];	
   $savingstype = $_POST['savingstype'];
   $resourceamount = $_POST['resourceamount'];
   $resourcetype = $_POST['resourcetype'];
   $themonth = $_POST['month'];
   $theyear = $_POST['year'];
   date_default_timezone_set('America/Chicago');
   $title = $userid . "_" . $themonth . "_" . $theyear . "_" . $savingstype;
   $title_r = $userid . "_" . $themonth . "_" . $theyear . "_" . $resourcetype;
   
   
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
			
			
			
	   } else {   
			
		   if(!empty($saving_posts) && $savingstype != 'heating' && $savingstype != 'cooling') {
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
		   }
		   }
		//RESOURCES (for non-heating/cooling)
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
			
			
			} else {
			   if(!empty($resource_posts) && $resourcetype != 'heating-2' && $resourcetype != 'cooling-2') {
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
	<script type="text/javascript">
		alert('Your savings have been updated!');
		window.name = "TAB2";
		location.reload(true);
		
		
	</script>
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
?>