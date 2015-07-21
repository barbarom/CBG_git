<?php
/*
Template Name: Change Based Giving - Thank You Processing Page
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
				cbg_thank_you();
				get_template_part('templates/content', 'page'); ?>
				<?php global $virtue; 
					if(isset($virtue['page_comments']) && $virtue['page_comments'] == '1') {
						comments_template('/templates/comments.php');
					} 
					
					?>
		</div><!-- /.main -->

<?php
function cbg_thank_you() {

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
		echo "<p class='pagetitles'>Thank you for your donation, " . $showname . "!</p>";
	}

	//Code for this came from: http://www.geekality.net/2010/10/19/php-tutorial-paypal-payment-data-transfers-pdt/
	
	if(isset($_GET['tx']))
	{
		$tx = $_GET['tx'];
		// Further processing

		// Init cURL
		$request = curl_init();
		
		//Production token
		//$your_pdt_identity_token = '5BNta2jnegOlqOMKUbd8plpCo3G69Nshv5EBYQL50xboBRbnkm66yAvpCNy';		
		
		//Sandbox token
		$your_pdt_identity_token = '1QoIoxZzmL1dJu4PZYdpEadToC3VmAM5UQEC9mJnPyR6zNmn5c-M3DcqB30';

		// Set request options
		curl_setopt_array($request, array
		(
		  CURLOPT_URL => 'https://www.sandbox.paypal.com/cgi-bin/webscr',
		  CURLOPT_POST => TRUE,
		  CURLOPT_POSTFIELDS => http_build_query(array
			(
			  'cmd' => '_notify-synch',
			  'tx' => $tx,
			  'at' => $your_pdt_identity_token,
			)),
		  CURLOPT_RETURNTRANSFER => TRUE,
		  CURLOPT_HEADER => FALSE,
		  // CURLOPT_SSL_VERIFYPEER => TRUE,
		  // CURLOPT_CAINFO => 'cacert.pem',
		));

		// Execute request and get response and status code
		$response = curl_exec($request);
		$status   = curl_getinfo($request, CURLINFO_HTTP_CODE);

		// Close connection
		curl_close($request); 	  
	  
	}

	if($status == 200 AND strpos($response, 'SUCCESS') === 0)
	{
		// Further processing
		// Remove SUCCESS part (7 characters long)
		$response = substr($response, 7);

		// URL decode
		$response = urldecode($response);

		// Turn into associative array
		preg_match_all('/^([^=\s]++)=(.*+)/m', $response, $m, PREG_PATTERN_ORDER);
		$response = array_combine($m[1], $m[2]);

		// Fix character encoding if different from UTF-8 (in my case)
		if(isset($response['charset']) AND strtoupper($response['charset']) !== 'UTF-8')
		{
		  foreach($response as $key => &$value)
		  {
			$value = mb_convert_encoding($value, 'UTF-8', $response['charset']);
		  }
		  $response['charset_original'] = $response['charset'];
		  $response['charset'] = 'UTF-8';
		}

		// Sort on keys for readability (handy when debugging)
		ksort($response);
		//var_dump($response);

		
		
		
		
	}	
	else
	{
		// Log the error, ignore it, whatever
	}	




}

