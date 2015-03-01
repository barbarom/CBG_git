		jQuery(document).ready(function ($) {
		
			$("#entertain_bankit").click(function(event){			
			  var data = {
				action: 'my_action',
				security : MyAjax.security,
				amount: $("#entertainchange_1").val(), 
				savingstype: 'entertainmentrecreation',
				userid: $("#userid").val(),
				month: $("#month4").val(),
				year: $("#year4").val()				
			  };
			 
			  // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			  if ($("#entertainchange_1").val() > 0) {
				  $.post(MyAjax.ajaxurl, data, function(response) {
					$('#entertain_bankit_result').html(response);
				  })
				.done(function() {
					alert('Your savings have been updated!');
					window.name = "TAB2";
					location.reload(true);
				});				  
			  } else {
				return false;
			  }			  
			});	

			$("#grocery_bankit").click(function(event){			
			  var data = {
				action: 'my_action',
				security : MyAjax.security,
				amount: $("#grocerychange_1").val(), 
				savingstype: 'grocery-shopping',
				userid: $("#userid").val(),
				month: $("#month4").val(),
				year: $("#year4").val()
			  };
			 
			  // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			  if ($("#grocerychange_1").val() > 0) {
				  $.post(MyAjax.ajaxurl, data, function(response) {
					$('#grocerybankit_result').html(response);
				  })
				.done(function() {
					alert('Your savings have been updated!');
					window.name = "TAB2";
					location.reload(true);
				});				  
			  } else {
				return false;
			  }			  
			});		

			$("#lighting_bankit").click(function(event){			
			  var data = {
				action: 'my_action',
				security : MyAjax.security,
				amount: $("#lighting_money_saved").val(), 
				savingstype: 'lighting',
				resourceamount: $("#lightingchange_2").val(),
				resourcetype: 'lighting-2',				
				userid: $("#userid").val(),
				month: $("#month4").val(),
				year: $("#year4").val()
			  };
			 
			  // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			  if ($("#heatingchange_3").val() > 0) {
				  $.post(MyAjax.ajaxurl, data, function(response) {
					$('#lightingbankit_result').html(response);
				  })
				.done(function() {
					alert('Your savings have been updated!');
					window.name = "TAB2";
					location.reload(true);
				});				  
			  } else {
				return false;
			  }			  
			});				
			
			$("#heating_bankit").click(function(event){			
			  var data = {
				action: 'my_action',
				security : MyAjax.security,
				amount: $("#heatingchange_3").val(), 
				savingstype: 'heating',
				resourceamount: $("#heatingchange_1").val(),
				resourcetype: 'heating-2',			
				userid: $("#userid").val(),
				month: $("#month4").val(),
				year: $("#year4").val(),
				state: $("#state_electric option:selected").text()
			  };
			 
			  // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			  if ($("#heatingchange_3").val() > 0) {
				  $.post(MyAjax.ajaxurl, data, function(response) {
					$('#heatingbankit_result').html(response);
				  })
				.done(function() {
					alert('Your savings have been updated!');
					window.name = "TAB2";
					location.reload(true);
				});				  
			  } else {
				return false;
			  }			  
			});		

			$("#cooling_bankit").click(function(event){			
			  var data = {
				action: 'my_action',
				security : MyAjax.security,
				amount: $("#coolingchange_3").val(), 
				savingstype: 'cooling',
				resourceamount: $("#coolingchange_1").val(),
				resourcetype: 'cooling-2',
				userid: $("#userid").val(),
				month: $("#month4").val(),
				year: $("#year4").val()	,
				state: $("#state_electric option:selected").text()				
			  };
			 
			  // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			  if ($("#coolingchange_3").val() > 0) {
				  $.post(MyAjax.ajaxurl, data, function(response) {
					$('#coolingbankit_result').html(response);
				  })
				.done(function() {
					alert('Your savings have been updated!');
					window.name = "TAB2";
					location.reload(true);
				});				  
			  } else {
				return false;
			  }
			  
			});	

			$("#water_bankit").click(function(event){	
		
			  var data = {
				action: 'my_action',
				security : MyAjax.security,
				amount: $("#watersaved_1").val(), 
				savingstype: 'water',
				resourceamount: $("#watersaved_2").val(),
				resourcetype: 'water-2',
				userid: $("#userid").val(),
				month: $("#month4").val(),
				year: $("#year4").val()				
			  };
			 
			// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			if ($("#watersaved_1").val() > 0) {
				  $.post(MyAjax.ajaxurl, data, function(response) {
					$('#waterbankit_result').html(response);
				  })
				.done(function() {
					alert('Your savings have been updated!');
					window.name = "TAB2";
					location.reload(true);
				});
			  } else {
				return false;
			  }			  
			});	

			$("#trans_bankit").click(function(event){	

			//CHECK IF transsaved_0 is null before executing.
			
				  var data = {
					action: 'my_action',
					security : MyAjax.security,
					amount: $("#transsaved_0").val(), 
					savingstype: 'transportation',
					resourceamount: $("#transsaved_2").val(),
					resourcetype: 'gas',					
					userid: $("#userid").val(),
					month: $("#month4").val(),
					year: $("#year4").val()					
				  };
				 
				// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
				if ($("#transsaved_0").val() > 0) {
					  $.post(MyAjax.ajaxurl, data, function(response) {
						$('#transbankit_result').html(response);
					  })
					  .done(function() {
						alert('Your savings have been updated!');
						window.name = "TAB2";
						location.reload(true);
					});
				  } else {
					return false;
				  }				  
				});	
				
			$("#appliance_bankit").click(function(event){	
		
			  var data = {
				action: 'my_action',
				security : MyAjax.security,
				amount: $("#appliancechange_1").val(), 
				savingstype: 'appliance',
				resourceamount: $("#appliancechange_2").val(),
				resourcetype: 'appliance-2',
				userid: $("#userid").val(),
				month: $("#month4").val(),
				year: $("#year4").val(),
				state: $("#state_electric option:selected").text(),
				newfridge: $("#appliance_2").val()
			  };
			 
			// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			if ($("#appliancechange_1").val() > 0) {
				  $.post(MyAjax.ajaxurl, data, function(response) {
					$('#appliancebankit_result').html(response);
				  })
				  .done(function() {
					alert('Your savings have been updated!');
					window.name = "TAB2";
					location.reload(true);
				});
			  } else {
				return false;
			  }			  
			});					
				
			$("#recycling_bankit").click(function(event){	
		
			  var data = {
				action: 'my_action',
				security : MyAjax.security,
				amount: $("#recyclingchange_1").val(), 
				savingstype: 'recycling',
				userid: $("#userid").val(),
				month: $("#month4").val(),
				year: $("#year4").val()		
			  };
			 
			// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			if ($("#recyclingchange_1").val() > 0) {
				  $.post(MyAjax.ajaxurl, data, function(response) {
					$('#recycling_bankit_result').html(response);
				  })
				  .done(function() {
					alert('Your savings have been updated!');
					window.name = "TAB2";
					location.reload(true);
				});
			  } else {
				return false;
			  }			  
			});					
				
				
				
				
				
				
				
				


			//THE FOLLOWING ARE CLICK FUNCTIONS TO SAVE BASELINE INPUTS
			$("#trans_base").click(function(event){
			  var data = {
				action: 'my_base',
				security : MyAjax.security,
				B1: $("#trans_1").val(), 
				B2: $("#trans_2").val(), 
				B3: $("#trans_3").val(), 
				B4: $("#trans_4").val(), 
				B5: $("#transbaseline_1").val(), 
				B6: $("#transbaseline_2").val(), 
				baselinetype: 'transportation',
				userid: $("#userid").val()
			  };
			 
			  // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			  $.post(MyAjax.ajaxurl, data, function(response) {				
			  });
			});			
			
			$("#water_base").click(function(event){
			  var data = {
				action: 'my_base',
				security : MyAjax.security,
				B1: $("#water_1").val(), 
				B2: $("#water_2").val(), 
				B3: $("#water_3").val(), 
				B4: $("#waterbaseline_1	").val(), 
				B5: $("#waterbaseline_2	").val(), 
				B6: $("#waterbaseline_3	").val(), 
				B7: $("#waterbaseline_4	").val(), 
			
				baselinetype: 'water',
				userid: $("#userid").val()
			  };
			 
			  // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			  $.post(MyAjax.ajaxurl, data, function(response) {				
			  });
			});

			$("#cooling_base").click(function(event){
			  var data = {
				action: 'my_base',
				security : MyAjax.security,
				B1: $("#cooling_1").val(), 
				B2: $("#cooling_2").val(), 
				B3: $("#cooling_3").val(), 
				B4: $("#cooling_4").val(), 
				baselinetype: 'cooling',
				userid: $("#userid").val()
			  };
			 
			  // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			  $.post(MyAjax.ajaxurl, data, function(response) {				
			  });
			});

			$("#heating_base").click(function(event){
			  var data = {
				action: 'my_base',
				security : MyAjax.security,
				B1: $("#heating_1").val(), 
				B2: $("#heating_2").val(), 
				B3: $("#heating_3").val(), 
				B4: $("#heating_4").val(), 
				baselinetype: 'heating',
				userid: $("#userid").val()
			  };
			 
			  // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			  $.post(MyAjax.ajaxurl, data, function(response) {				
			  });
			});

			$("#lighting_base").click(function(event){
			  var data = {
				action: 'my_base',
				security : MyAjax.security,
				B1: $("#lighting_1").val(), 
				B2: $("#lighting_2").val(), 
				B3: $("#lighting_3").val(), 
				B4: $("#lighting_4").val(), 
				B5: $("#lighting_5").val(), 
				B6: $("#lightingbaseline_1").val(),
				
				baselinetype: 'lighting',
				userid: $("#userid").val()
			  };
			 
			  // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			  $.post(MyAjax.ajaxurl, data, function(response) {				
			  });
			});

			$("#grocery_base").click(function(event){
			  var data = {
				action: 'my_base',
				security : MyAjax.security,
				B1: $("#grocery_1").val(), 
				B2: $("#grocerybaseline_1").val(), 
				baselinetype: 'grocery-shopping',
				userid: $("#userid").val()
			  };
			 
			  // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			  $.post(MyAjax.ajaxurl, data, function(response) {				
			  });
			});
			
			$("#appliance_base").click(function(event){
			  var data = {
				action: 'my_base',
				security : MyAjax.security,
				B1: $("#appliance_1").val(), 
				B2: $("#appliancebaseline_1").val(), 				
				baselinetype: 'appliance',
				userid: $("#userid").val()
			  };
			 
			  // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			  $.post(MyAjax.ajaxurl, data, function(response) {				
			  });
			});			

			$("#changetime").click(function() {
				var dte = '?themonth=' + $("#month").val() + '&monthname=' + $('#month option:selected').html() + '&theyear=' + $("#year").val() + '#tab-M';
				var url = "/you/" + dte;
				window.location = url;			
			});	

			$("#changetime2").click(function() {
				var dte = '?themonth=' + $("#month2").val() + '&monthname=' + $('#month2 option:selected').html() + '&theyear=' + $("#year2").val() + '#tab-R';
				var url = "/you/" + dte;
				window.location = url;			
			});				
			
			$("#changetime3").click(function() {
				var dte = '?themonth=' + $("#month4").val() + '&theyear=' + $("#year4").val();
				var url = window.location.pathname + dte;
				window.location = url;			
			});			
			
		});