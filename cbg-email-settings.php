<?php
/*
Template Name: Change Based Giving - Email Settings
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
				cbg_email_settings();
				get_template_part('templates/content', 'page'); ?>
				<?php global $virtue; 
					if(isset($virtue['page_comments']) && $virtue['page_comments'] == '1') {
						comments_template('/templates/comments.php');
					} 
					
					?>
		</div><!-- /.main -->

<?php
function cbg_email_settings() {
if ( is_user_logged_in() ) {
	$current_user = wp_get_current_user();
	$userid = $current_user->ID;
	
	$unsubscribe_status = get_user_meta($userid, 'unsubscribe');
	

?>

	<div class="tabs">
		<ul class="tab-links">
			<li id="t1" class="active"><a href="#tab1">Subscribe</a></li>
			<li id="t2"><a href="#tab2">Unsubscribe</a></li>
		</ul>  
	
	  <div class="tab-content">
		  <div id="tab1" class="tab active">
<?php
		  if (!empty($_POST['submit_yes_emails'])) {

			if (isset($_POST['check_yes_emails'])) {
				delete_user_meta( $userid, 'unsubscribe' );
				echo "Thank you for subscribing to Change Based Giving! You will now start receiving newsletters and updates from us.";
			} else {
				echo "You did not check the box indicating your wish to start receiving emails from CBG. Please check the box before clicking the submit button.<br /><br />To start over, click <a href='/email-settings/?thetab=tab1'>here</a>.";
			}

		  
		  } else {
			if (empty($unsubscribe_status)) {
				echo "You are already subscribed to the Change Based Giving email list.";
			} else {
?>		  
				To start receiving future emails from Change Based Giving (CBG) check the box below and click the "Submit" button.<br/><br/>

				<form id="start" action="/email-settings/?thetab=tab1" method="post">
					<input type="checkbox" name="check_yes_emails" id="check_yes_emails" /> Start receiving future emails from CBG<br/><br/>
					<input type="submit" name="submit_yes_emails" id="submit_yes_emails" value="Submit" />  
				</form>	
<?php
			}
		}
?>			  
		  </div>
		  <div id="tab2" class="tab">
<?php
		  if (!empty($_POST['submit_no_emails'])) {

			if (isset($_POST['check_no_emails'])) {
				//add unsubscribe = true to usermeta
				update_user_meta( $userid, 'unsubscribe', 'true' );
				echo "You are now unsubscribed from future emails from Change Based Giving.";				
			} else {
				echo "You did not check the box indicating your wish to stop receiving emails from CBG. Please check the box before clicking the submit button.<br /><br />To start over, click <a href='/email-settings/?thetab=tab2'>here</a>.";
			}

		  
		  } else {
			if (!empty($unsubscribe_status)) {
				echo "You are already unsubscribed from the Change Based Giving email list.";
			} else {
?>		  
				To stop receiving future emails from Change Based Giving (CBG) check the box below and click the "Submit" button.<br/><br/>

				<form id="stop" action="/email-settings/?thetab=tab2" method="post">
					<input type="checkbox" name="check_no_emails" id="check_no_emails" /> Stop receiving future emails from CBG<br/><br/>
					<input type="submit" name="submit_no_emails" id="submit_no_emails" value="Submit" />  
				</form>	
<?php
			}
		}
?>				
		  </div>
	  </div>
	</div>

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
			
			var t = getParameterByName('thetab');	
			if (t == 'tab1') {
				$("#tab2").hide();
				$("#tab1").show();
				$("#t2").removeClass('active');
				$("#t1").addClass('active');					
			} else if (t == 'tab2') {
				$("#tab1").hide();
				$("#tab2").show();
				$("#t1").removeClass('active');
				$("#t2").addClass('active');					
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
	} else {
?>	
		<p class="pagetitles">Welcome, visitor!</p>
		<p>You are currently not logged in or registered. In order to start making real change please <a href="http://www.change-based-giving.org/cbg/wp-login.php">LOG IN</a> or <a href="http://www.change-based-giving.org/cbg/wp-login.php?action=register">REGISTER</a> now!</p>	
<?php		
	}
}