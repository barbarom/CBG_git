<?php
/**
 * The Template for displaying all single posts
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>
<style type="text/css">
	#content {
		width:700px;
		
	}
	.thermometer {
		float: right;
		margin: 20px;
	}
	.thermometer {
		width:70px;
		height:300px;
		position: relative;
		right: 75px;
		background: #ddd;
		border:1px solid #aaa;
		-webkit-border-radius: 12px;
		   -moz-border-radius: 12px;
			-ms-border-radius: 12px;
			 -o-border-radius: 12px;
				border-radius: 12px;

		-webkit-box-shadow: 1px 1px 4px #999, 5px 0 20px #999;
		   -moz-box-shadow: 1px 1px 4px #999, 5px 0 20px #999;
			-ms-box-shadow: 1px 1px 4px #999, 5px 0 20px #999;
			 -o-box-shadow: 1px 1px 4px #999, 5px 0 20px #999;
				box-shadow: 1px 1px 4px #999, 5px 0 20px #999;
	}

	.thermometer .track {
		height:280px;
		top:10px;
		width:20px;
		border: 1px solid #aaa;
		position: relative;
		margin:0 auto;
		background: rgb(255,255,255);
		background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgb(0,0,0)), color-stop(1%,rgb(255,255,255)));
		background: -webkit-linear-gradient(top, rgb(0,0,0) 0%,rgb(255,255,255) 10%);
		background:      -o-linear-gradient(top, rgb(0,0,0) 0%,rgb(255,255,255) 10%);
		background:     -ms-linear-gradient(top, rgb(0,0,0) 0%,rgb(255,255,255) 10%);
		background:    -moz-linear-gradient(top, rgb(0,0,0) 0%,rgb(255,255,255) 10%);
		background:   linear-gradient(to bottom, rgb(0,0,0) 0%,rgb(255,255,255) 10%);
		background-position: 0 -1px;
		background-size: 100% 5%;
	}

	.thermometer .progress {
		height:0%;
		width:100%;
		background: rgb(20,100,20);
		background: rgba(20,100,20,0.6);
		position: absolute;
		bottom:0;
		left:0;
	}

	.thermometer .goal {
		position:absolute;
		top:0;
	}

	.thermometer .amount {
		display: inline-block;
		padding:0 5px 0 60px;
		border-top:1px solid black;
		font-family: Trebuchet MS;
		font-weight: bold;
		color:#333;
	}

	.thermometer .progress .amount {
		padding:0 60px 0 5px;
		position: absolute;
		border-top:1px solid #060;
		color:#060;
		right:0;
	}



	.thermometer.horizontal {
		width:350px;
		height:70px;
	}

	.thermometer.horizontal .track {
		width:90%;
		left:0;
		height:20px;
		margin:14px auto;

		background: -webkit-gradient(linear, left top, right top, color-stop(0%,rgb(0,0,0)), color-stop(1%,rgb(255,255,255)));
		background: -webkit-linear-gradient(left, rgb(0,0,0) 0%,rgb(255,255,255) 10%);
		background:      -o-linear-gradient(left, rgb(0,0,0) 0%,rgb(255,255,255) 10%);
		background:     -ms-linear-gradient(left, rgb(0,0,0) 0%,rgb(255,255,255) 10%);
		background:    -moz-linear-gradient(left, rgb(0,0,0) 0%,rgb(255,255,255) 10%);
		background:   linear-gradient(to right, rgb(0,0,0) 0%,rgb(255,255,255) 10%);
		background-size: 5% 100%;
	}

	.thermometer.horizontal .progress {
		height:100%;
		width:0%;
	}

	.thermometer.horizontal .goal {
		left:100%;
		height:100%;
	}

	.thermometer.horizontal .amount {
		bottom:0;
		position: absolute;
		padding:0 5px 50px 5px;
		border-top:0;
		border-left:1px solid black;

	}

	.thermometer.horizontal .progress .amount {
		border-left:0;
		border-top:0;
		border-right:1px solid #060;
	}
</style>



	<div id="primary" class="site-content">	
    <div id="thermo1" class="thermometer">	
	
        <div class="track">
            <div class="goal">
                <div class="amount"></div>
            </div>
            <div class="progress">
                <div class="amount"></div>
            </div>
        </div>

    </div>	
	
		<div id="content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', get_post_format() ); ?>
				<?php 
				$goal = get_post_meta( get_the_ID(), 'cbg_projectcost', true );
							
				?>
				<nav class="nav-single">
					<h3 class="assistive-text"><?php _e( 'Post navigation', 'twentytwelve' ); ?></h3>
					<span class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'twentytwelve' ) . '</span> %title' ); ?></span>
					<span class="nav-next"><?php next_post_link( '%link', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'twentytwelve' ) . '</span>' ); ?></span>
				</nav><!-- .nav-single -->

				<?php comments_template( '', true ); ?>

			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
	</div><!-- #primary -->
<script type="text/javascript">
//originally from http://stackoverflow.com/questions/149055/how-can-i-format-numbers-as-money-in-javascript
function formatCurrency(n, c, d, t) {
    "use strict";

    var s, i, j;

    c = isNaN(c = Math.abs(c)) ? 2 : c;
    d = d === undefined ? "." : d;
    t = t === undefined ? "," : t;

    s = n < 0 ? "-" : "";
    i = parseInt(n = Math.abs(+n || 0).toFixed(c), 10) + "";
    j = (j = i.length) > 3 ? j % 3 : 0;

    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
}


/**
 * Thermometer Progress meter.
 * This function will update the progress element in the "thermometer"
 * to the updated percentage.
 * If no parameters are passed in it will read them from the DOM
 *
 * @param {Number} goalAmount The Goal amount, this represents the 100% mark
 * @param {Number} progressAmount The progress amount is the current amount
 * @param {Boolean} animate Whether to animate the height or not
 *
 */
function thermometer(id, goalAmount, progressAmount, animate) {
		"use strict";

		var $thermo = jQuery("#"+id),
			$progress = jQuery(".progress", $thermo),
			$goal = jQuery(".goal", $thermo),
			percentageAmount,
			isHorizontal = $thermo.hasClass("horizontal"),
			newCSS = {};

		goalAmount = goalAmount || parseFloat( $goal.text() ),
		progressAmount = progressAmount || parseFloat( $progress.text() ),
		percentageAmount =  Math.min( Math.round(progressAmount / goalAmount * 1000) / 10, 100); //make sure we have 1 decimal point

		//let's format the numbers and put them back in the DOM
		$goal.find(".amount").text( "$" + formatCurrency( goalAmount ) );
		$progress.find(".amount").text( "$" + formatCurrency( progressAmount ) );


		//let's set the progress indicator
		$progress.find(".amount").hide();

		newCSS[ isHorizontal ? "width" : "height" ] = percentageAmount + "%";

		if (animate !== false) {
			$progress.animate( newCSS, 1200, function(){
				jQuery(this).find(".amount").fadeIn(500);
			});
		}
		else {
			$progress.css( newCSS );
			$progress.find(".amount").fadeIn(500);
		}
	}

	jQuery(document).ready(function(){
		thermometer("thermo1", <?php echo $goal ?>, 0 );
	});
</script>
<?php get_sidebar(); ?>
<?php get_footer(); ?>