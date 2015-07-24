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

	$args = array('orderby' => 'display_name');
	$wp_user_query = new WP_User_Query($args);
	$authors = $wp_user_query->get_results();

	if (!empty($authors)) {
		echo "Annual Report Email sent to:<br /><br />";
		//echo '<ul>';
		foreach ($authors as $author) {
			$author_info = get_userdata($author->ID);
			$aid = $author_info->ID;
			$currentyear = intval($_POST['taxyear']);
			$args_payments = array(
				'author' => 1,
				'post_type' => 'payment',
				'posts_per_page' => -1,					
				'date_query' => array(
					array(
						'year'  => $currentyear
					),
				),
			);
			$query = new WP_Query($args_payments);
			//var_dump($args_payments);
		   if($query->have_posts()) : 
			  while($query->have_posts()) : 
				 $query->the_post();
		?>

				 <h1><?php the_title() ?></h1>
				 <div class='post-content'><?php the_content() ?></div>      
			  
		<?php
			  endwhile;
			endif;
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			if ($author_info->user_email == "mbarbaro76@gmail.com") {
				
			} 
			
			echo '<li>' . $author_info->user_email . '</li>';
		}
		//echo '</ul>';
	} else {
		echo 'No results';
	}  
  
  } else {
?>

<form action="" method="post">
	Tax Year: <select id="taxyear" name="taxyear">
		<option value="2015">2015</option>
		<option value="2016">2016</option>
	</select>
	<br/><br/>
	<input type="submit" name="send_annual_email" id="send_annual_email" value="Send Annual Email to All Users" />  
</form>
	
<?php	
	}
}