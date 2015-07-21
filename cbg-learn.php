<?php
/*
Template Name: Change Based Giving - Learn Page
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
				cbg_learn();
				get_template_part('templates/content', 'page'); ?>
				<?php global $virtue; 
					if(isset($virtue['page_comments']) && $virtue['page_comments'] == '1') {
						comments_template('/templates/comments.php');
					} ?>
		</div><!-- /.main -->

<?php
function cbg_learn() {
?>

<script type="text/javascript">
jQuery( document ).ready(function($) {
	var iOS = /(iPad|iPhone|iPod)/g.test(navigator.userAgent);
	if (iOS == true) {
		$("#flashlearn").hide();
		$("#noflashlearn").show();		
	} else {
		$("#flashlearn").show();
		$("#noflashlearn").hide();
	}   
	

});

</script>

<div id="flashlearn">
	To learn more about sustainability topics and urban slums, slowly roll over then click any of the following elements: city lights, sun, car, sewage, refrigerator, slum community, or river.
	<br /><br />
	<object type="application/x-shockwave-flash" data="http://change-based-giving.org/flash/C+B+G.swf" allowScriptAccess="always" class="movie" width="750" height="750">
	<param name="movie" value="http://change-based-giving.org/flash/C+B+G.swf" />
	<param name="bgcolor" value="ffffff" />
	<param name="allowScriptAccess" value="always">
	<img src="http://path-to/noflash.gif" width="750" height="750" alt="No flash installed" title="No flash installed" class="image" />
	</object>
</div>

<div id="noflashlearn">
	To learn more about sustainability topics and urban slums, click any of the following elements:<br /><br />
	<ul>
		<li><a href="http://change-based-giving.org/learn/consumption/">Consumption</a></li>
		<li><a href="http://change-based-giving.org/learn/climate/">Climate Change</a></li>
		<li><a href="http://change-based-giving.org/learn/transportation/">Transportation</a></li>
		<li><a href="http://change-based-giving.org/learn/waste/">Waste</a></li>
		<li><a href="http://change-based-giving.org/learn/home-energy-use/">Energy Use</a></li>
		<li><a href="http://change-based-giving.org/learn/slums/">Slums</a></li>
		<li><a href="http://change-based-giving.org/learn/water/">Water</a></li>
	</ul>	

</div>



<?php
}