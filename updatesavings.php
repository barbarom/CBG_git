<?php
	function cbg_savings() {
		if( $_POST["amount"] )
		{
		   $amount = $_POST['amount'];
		   $userid = $_POST['userid'];
		   

				
				date_default_timezone_set('America/Chicago');
				$title = $userid . "_" . date("Ymd_His");
				echo $title;
				// $p = array(
					  // 'post_title'    => $title,
					  // 'post_type'     => 'post',
					  // 'post_content'  => $amount,
					  // 'post_status'   => 'publish',
					  // 'post_author'   => $userid,
					  // 'post_date'     => [ Y-m-d H:i:s ]			  
					// );
				// wp_insert_post($p);
				// echo "Your savings have been updated!";

		}
	}
?>