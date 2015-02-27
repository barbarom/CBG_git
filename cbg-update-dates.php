<?php
/*
Template Name: Change Based Giving - Update DATES
*/

get_header(); ?>

	<div id="primary" class="site-content">
		<div id="content" role="main">

			<?php 
			cbg_update_dates();
			
			while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'content', 'page' ); ?>
				
			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
	</div><!-- #primary -->


<?php get_footer(); ?>

<?php
function cbg_update_dates() {

		$args = array(
			'posts_per_page'   => -1,
			'post_type'        => 'saved_resource',
			'post_status'      => 'publish'
			);
		$posts_array = get_posts( $args );
		
		foreach ( $posts_array as $post ) {
			$thedate = $post->post_date;
			$post_id = $post->ID;
			$pieces = explode("-", $thedate);
			$theyear = $pieces[0];
			$monum = $pieces[1];
			$themonth = cbg_month_lookup($monum);
			
			//add_post_meta($post_id, 'resource_month', $themonth);
			//add_post_meta($post_id, 'resource_year', $theyear);			
			
			//echo "Added post meta: " . $themonth . ", " . $theyear . "<br />";
		}
		
}

function cbg_month_lookup($mo_num) {
	$monthname = "";
	if ($mo_num == "01") {
		$monthname = "January";
	} else if ($mo_num == "02") {
		$monthname = "February";
	} else if ($mo_num == "03") {
		$monthname = "March";
	} else if ($mo_num == "04") {
		$monthname = "April";
	} else if ($mo_num == "05") {
		$monthname = "May";
	} else if ($mo_num == "06") {
		$monthname = "June";
	} else if ($mo_num == "07") {
		$monthname = "July";
	} else if ($mo_num == "08") {
		$monthname = "August";
	} else if ($mo_num == "09") {
		$monthname = "September";
	} else if ($mo_num == "10") {
		$monthname = "October";
	} else if ($mo_num == "11") {
		$monthname = "November";
	} else if ($mo_num == "12") {
		$monthname = "December";
	}	

	return $monthname;
}