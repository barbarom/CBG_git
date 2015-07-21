<?php
/*
Template Name: Change Based Giving - Give Page with Map
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
				cbg_give_w_map();
				get_template_part('templates/content', 'page'); 
				?>
				<?php global $virtue; 
					if(isset($virtue['page_comments']) && $virtue['page_comments'] == '1') {
						comments_template('/templates/comments.php');
					} 
					
					?>
		</div><!-- /.main -->

<?php
function cbg_give_w_map() {
?>
<a href="http://www.change-based-giving.org/donate/"><img class=" size-full wp-image-928 alignright" src="http://www.change-based-giving.org/cbg/wp-content/uploads/2015/05/donatebutton.png" alt="Donate" width="112" height="40" /></a>
	<p class="pagetitles">CLICK ON THE MAP TO LEARN ABOUT OUR PROJECTS...</p>
	<div id="map-canvas" style="float:left;width:100%; height:600px"></div>
	
	<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
    <script type="text/javascript">
		var map;
      function initialize() {
        var mapOptions = {
          center: { lat: 0, lng: 0},
          zoom: 2
        };
        map = new google.maps.Map(document.getElementById('map-canvas'),
            mapOptions);
			
		map.setOptions({ minZoom: 2 });
			
		var MCM_latlng = new google.maps.LatLng(19.432608, -99.133208);
		var mexicocity = new google.maps.Marker({
			position: MCM_latlng,
			map: map,
			title:"Mexico City, Mexico"
		});			
		var KI_latlng = new google.maps.LatLng(22.572646, 88.363895);
			var kolkata = new google.maps.Marker({
			position: KI_latlng,
			map: map,
			title:"Kolkata, India"
		});	
		var NK_latlng = new google.maps.LatLng(-1.2610267,36.8644604);
			var nairobi = new google.maps.Marker({
			position: NK_latlng,
			map: map,
			title:"Mathare Neighborhood, Nairobi, Kenya"
		});	

		
		var MCMcontentString = '<div id="content1" style="max-height:300px;overflow-y:auto;">'+
			'<h5>Mexico City, Mexico</h5>'+
			'Projects from Mexico City Coming Soon'+
			'</div>';
		var MCMinfowindow = new google.maps.InfoWindow({
		  content: MCMcontentString,
		  maxWidth: 320
		});				
		google.maps.event.addListener(mexicocity, 'click', function() {
			map.setZoom(4);
			map.setCenter(mexicocity.getPosition());
			MCMinfowindow.open(map,mexicocity);
		});

		var KIcontentString = '<div id="content2" style="max-height:300px;overflow-y:auto;">'+
			'<h5>Kolkata, India</h5>'+
			'Projects from Kolkata Coming Soon'+
			'</div>';
		var KIinfowindow = new google.maps.InfoWindow({
		  content: KIcontentString,
		  maxWidth: 320
		});				
		google.maps.event.addListener(kolkata, 'click', function() {
			map.setZoom(4);
			map.setCenter(kolkata.getPosition());
			KIinfowindow.open(map,kolkata);
		});
		
		var NKcontentString = '<div id="content3" style="max-height:300px;overflow-y:auto;">'+
			'<h5>Mathare Neighborhood, Nairobi, Kenya</h5>'+
			'The Mathare OVC (orphan and vulnerable children) project aims to restore hope and dignity for 150 orphans and vulnerable children by providing education, clothing, food and medical care.<br /><a href="http://www.change-based-giving.org/project/mathare-neighborhood-nairobi/">Click here for more information</a><br /><br /><img src="http://www.change-based-giving.org/cbg/wp-content/uploads/2015/05/5.jpg" width="300" />'+
			'</div>';
		var NKinfowindow = new google.maps.InfoWindow({
		  content: NKcontentString,
		  maxWidth: 320,
		  maxHeight: 200
		});				
		google.maps.event.addListener(nairobi, 'click', function() {
			map.setZoom(4);
			map.setCenter(nairobi.getPosition());		
			NKinfowindow.open(map,nairobi);
		});		
		
      }
      google.maps.event.addDomListener(window, 'load', initialize);
    </script>
	
<?php
}