
<?php
/*
Template Name: Change Based Giving - Annual Donation Email
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
				cbg_annual_donation_email();
				get_template_part('templates/content', 'page'); ?>
				<?php global $virtue; 
					if(isset($virtue['page_comments']) && $virtue['page_comments'] == '1') {
						comments_template('/templates/comments.php');
					} ?>
		</div><!-- /.main -->

<?php
function cbg_annual_donation_email() {
  if (!empty($_POST['send_annual_email'])) {

	$args = array('orderby' => 'ID');
	$wp_user_query = get_users( $args );


	if (!empty($wp_user_query)) {
		echo "Annual Report Email sent to:<br /><br />";
		
		foreach ($wp_user_query as $author5) {
			$author_info = get_userdata($author5->ID);			
			$aid = $author_info->ID;	
			$aemail = $author_info->user_email;	
			$currentyear = $_POST['taxyear'];
			echo "<li><strong>" . $aemail . "</strong></li>";
			$args2 = array(				
				'post_type' => 'payment',
				'posts_per_page' => -1, 
				'year' => $currentyear,
				'meta_query' => array(
					array(
						'key'     => 'user_id',
						'value'   => $aid,						
					),
				),				

				
			);
			$author_query = new WP_Query( $args2 );
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
			
			//if ($aid == 1) {
				$donation_str = $donation_str . "</tbody></table>";
				$confirm_msg = "Here are your total donations to Change Based Giving for the tax year: <strong>" . $currentyear . "</strong><br /><br /><br />" . $donation_str . "<br /><br />" . $currentyear . " Total = <strong>" . money_format('$%i', $ttl_donation) . "</strong><br /><br /><br />";
				wp_mail( $aemail, 'Change Based Giving donations for tax year ' . $currentyear, $confirm_msg );			
			//}
			
		}
		
	} else {
		echo 'No results';
	}  
  
  } else {
?>

<form action="" method="post">
<?php
	$thisyear = date("Y");
	$taxyear = $thisyear - 1;
	//$taxyear = 2015;
	//echo "TAX YEAR = " . $taxyear . "<br />";
?>
	Tax Year: <strong><?php echo $taxyear; ?></strong> 
	<input type="hidden" id="taxyear" name="taxyear" value="<?php echo $taxyear; ?>">
	<br/><br/>
	<input type="submit" name="send_annual_email" id="send_annual_email" value="Send Annual Email to All Users" />  
</form>
	
<?php	
	}
}