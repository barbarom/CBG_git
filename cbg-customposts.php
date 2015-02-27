<?php

/**
 * Plugin Name: Change-Based-Giving Custom Post Types
 * Description: Code for custom post types
 * Version: 1.0
 * Author: Michael C. Barbaro
 */

add_action( 'init', 'register_cpt_saving' );

function register_cpt_saving() {

    $labels = array( 
        'name' => _x( 'Savings', 'saving' ),
        'singular_name' => _x( 'Saving', 'saving' ),
        'add_new' => _x( 'Add New', 'saving' ),
        'all_items' => _x( 'Savings', 'saving' ),
        'add_new_item' => _x( 'Add New Saving', 'saving' ),
        'edit_item' => _x( 'Edit Saving', 'saving' ),
        'new_item' => _x( 'New Saving', 'saving' ),
        'view_item' => _x( 'View Saving', 'saving' ),
        'search_items' => _x( 'Search Savings', 'saving' ),
        'not_found' => _x( 'No savings found', 'saving' ),
        'not_found_in_trash' => _x( 'No savings found in Trash', 'saving' ),
        'parent_item_colon' => _x( 'Parent Saving:', 'saving' ),
        'menu_name' => _x( 'Savings', 'saving' ),
    );

    $args = array( 
        'labels' => $labels,
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true
    );

    register_post_type( 'saving', $args );
}

add_action( 'init', 'register_taxonomy_savings_types' );

function register_taxonomy_savings_types() {

    $labels = array( 
        'name' => _x( 'Savings Types', 'savings_types' ),
        'singular_name' => _x( 'Savings Type', 'savings_types' ),
        'search_items' => _x( 'Search Savings Types', 'savings_types' ),
        'popular_items' => _x( 'Popular Savings Types', 'savings_types' ),
        'all_items' => _x( 'All Savings Types', 'savings_types' ),
        'parent_item' => _x( 'Parent Savings Type', 'savings_types' ),
        'parent_item_colon' => _x( 'Parent Savings Type:', 'savings_types' ),
        'edit_item' => _x( 'Edit Savings Type', 'savings_types' ),
        'update_item' => _x( 'Update Savings Type', 'savings_types' ),
        'add_new_item' => _x( 'Add New Savings Type', 'savings_types' ),
        'new_item_name' => _x( 'New Savings Type', 'savings_types' ),
        'separate_items_with_commas' => _x( 'Separate savings types with commas', 'savings_types' ),
        'add_or_remove_items' => _x( 'Add or remove savings types', 'savings_types' ),
        'choose_from_most_used' => _x( 'Choose from the most used savings types', 'savings_types' ),
        'menu_name' => _x( 'Savings Types', 'savings_types' ),
    );

    $args = array( 
        'labels' => $labels,
        'public' => true,
        'show_in_nav_menus' => true,
        'show_ui' => true,
        'show_tagcloud' => true,
        'show_admin_column' => false,
        'hierarchical' => true,

        'rewrite' => true,
        'query_var' => true
    );

    register_taxonomy( 'savings_types', array('saving'), $args );
}

add_action( 'init', 'register_cpt_project' );

function register_cpt_project() {

    $labels = array( 
        'name' => _x( 'Projects', 'project' ),
        'singular_name' => _x( 'Project', 'project' ),
        'add_new' => _x( 'Add New', 'project' ),
        'all_items' => _x( 'Projects', 'project' ),
        'add_new_item' => _x( 'Add New Project', 'project' ),
        'edit_item' => _x( 'Edit Project', 'project' ),
        'new_item' => _x( 'New Project', 'project' ),
        'view_item' => _x( 'View Project', 'project' ),
        'search_items' => _x( 'Search Projects', 'project' ),
        'not_found' => _x( 'No projects found', 'project' ),
        'not_found_in_trash' => _x( 'No projects found in Trash', 'project' ),
        'parent_item_colon' => _x( 'Parent Project:', 'project' ),
        'menu_name' => _x( 'Projects', 'project' ),
    );

    $args = array( 
        'labels' => $labels,
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
		'has_archive' => true	
    );

    register_post_type( 'project', $args );
}

add_action( 'init', 'register_taxonomy_locations' );

function register_taxonomy_locations() {

    $labels = array( 
        'name' => _x( 'Locations', 'locations' ),
        'singular_name' => _x( 'Location', 'locations' ),
        'search_items' => _x( 'Search Locations', 'locations' ),
        'popular_items' => _x( 'Popular Locations', 'locations' ),
        'all_items' => _x( 'All Locations', 'locations' ),
        'parent_item' => _x( 'Parent Location', 'locations' ),
        'parent_item_colon' => _x( 'Parent Location:', 'locations' ),
        'edit_item' => _x( 'Edit Location', 'locations' ),
        'update_item' => _x( 'Update Location', 'locations' ),
        'add_new_item' => _x( 'Add New Location', 'locations' ),
        'new_item_name' => _x( 'New Location', 'locations' ),
        'separate_items_with_commas' => _x( 'Separate locations with commas', 'locations' ),
        'add_or_remove_items' => _x( 'Add or remove locations', 'locations' ),
        'choose_from_most_used' => _x( 'Choose from the most used locations', 'locations' ),
        'menu_name' => _x( 'Locations', 'locations' ),
    );

    $args = array( 
        'labels' => $labels,
        'public' => true,
        'show_in_nav_menus' => true,
        'show_ui' => true,
        'show_tagcloud' => true,
        'show_admin_column' => false,
        'hierarchical' => true,

        'rewrite' => true,
        'query_var' => true
    );

    register_taxonomy( 'locations', array('project'), $args );
}

add_action( 'init', 'register_cpt_payment' );

function register_cpt_payment() {

    $labels = array( 
        'name' => _x( 'Payments', 'payment' ),
        'singular_name' => _x( 'Payment', 'payment' ),
        'add_new' => _x( 'Add New', 'payment' ),
        'all_items' => _x( 'Payments', 'payment' ),
        'add_new_item' => _x( 'Add New Payment', 'payment' ),
        'edit_item' => _x( 'Edit Payment', 'payment' ),
        'new_item' => _x( 'New Payment', 'payment' ),
        'view_item' => _x( 'View Payment', 'payment' ),
        'search_items' => _x( 'Search Payments', 'payment' ),
        'not_found' => _x( 'No Payments found', 'payment' ),
        'not_found_in_trash' => _x( 'No Payments found in Trash', 'payment' ),
        'parent_item_colon' => _x( 'Parent Payment:', 'payment' ),
        'menu_name' => _x( 'Payments', 'payment' ),
    );

    $args = array( 
        'labels' => $labels,
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true
    );

    register_post_type( 'payment', $args );
}


add_action( 'admin_init', 'cbg_project_meta_box_add' );
function cbg_project_meta_box_add()
{
	 add_meta_box( 'cbg_project_meta_box', 'Project Information', 'cbg_project_meta_box', 'project', 'normal', 'high');         
}

function cbg_project_meta_box()
{
	global $post;
    $custom = get_post_custom($post->ID);
    $projectcost = $custom["cbg_projectcost"][0];
?>
        <strong>Project Cost:</strong> $<input type="text" id="cbg_projectcost" name="cbg_projectcost" placeholder="Enter project cost" value="<?php 
                if ($projectcost != "") {
                    echo $projectcost;
                }
           ?>"/> <span id="errmsg1" style="color: #ff0000;margin-left:10px;"></span>
		   
	   <script type="text/javascript">
		  jQuery(document).ready(function($){
			  $("#cbg_projectcost").on("keyup", function(){
				var valid = /^\d{0,10}(\.\d{0,2})?$/.test(this.value),
					val = this.value;
				
				if(!valid){
				this.value = val.substring(0, val.length - 1);
					$("#errmsg1").html("Currency Only").show().fadeOut(2000);
						   return false;					
				}
				});
		  });	   
	   </script>
<?php	
}

add_action( 'save_post', 'cbgproject_save' );
function cbgproject_save() { 
 
   global $post;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
      return;

    if ($post->post_type == 'project') {
       cbgproject_save_event_field("cbg_projectcost");	   
	}	   
}	

function cbgproject_save_event_field($event_field) {
    global $post;
    //Don't save empty metas
    if(!empty($_POST[$event_field])) {
        update_post_meta($post->ID, $event_field, $_POST[$event_field]);
    } else {
        //Also note that disabled fields are not saved. e.g. if "National" is selected, state, finalgeo and lat/lon will all be deleted. If "State" is selected, finalgeo and lat/lon will all be deleted.
        delete_post_meta($post->ID, $event_field);
    }
}

add_action( 'init', 'register_cpt_baseline' );

function register_cpt_baseline() {

    $labels = array( 
        'name' => _x( 'Baselines', 'baseline' ),
        'singular_name' => _x( 'Baseline', 'baseline' ),
        'add_new' => _x( 'Add New', 'baseline' ),
        'all_items' => _x( 'Baselines', 'baseline' ),
        'add_new_item' => _x( 'Add New Baseline', 'baseline' ),
        'edit_item' => _x( 'Edit Baseline', 'baseline' ),
        'new_item' => _x( 'New Baseline', 'baseline' ),
        'view_item' => _x( 'View Baseline', 'baseline' ),
        'search_items' => _x( 'Search Baselines', 'baseline' ),
        'not_found' => _x( 'No baselines found', 'baseline' ),
        'not_found_in_trash' => _x( 'No baselines found in Trash', 'baseline' ),
        'parent_item_colon' => _x( 'Parent Baseline:', 'baseline' ),
        'menu_name' => _x( 'Baselines', 'baseline' ),
    );

    $args = array( 
        'labels' => $labels,
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true
    );

    register_post_type( 'baseline', $args );
}

add_action( 'init', 'register_taxonomy_baseline_types' );

function register_taxonomy_baseline_types() {

    $labels = array( 
        'name' => _x( 'Baseline Types', 'baseline_types' ),
        'singular_name' => _x( 'Baseline Type', 'baseline_types' ),
        'search_items' => _x( 'Search Baseline Types', 'baseline_types' ),
        'popular_items' => _x( 'Popular Baseline Types', 'baseline_types' ),
        'all_items' => _x( 'All Baseline Types', 'baseline_types' ),
        'parent_item' => _x( 'Parent Baseline Type', 'baseline_types' ),
        'parent_item_colon' => _x( 'Parent Baseline Type:', 'baseline_types' ),
        'edit_item' => _x( 'Edit Baseline Type', 'baseline_types' ),
        'update_item' => _x( 'Update Baseline Type', 'baseline_types' ),
        'add_new_item' => _x( 'Add New Baseline Type', 'baseline_types' ),
        'new_item_name' => _x( 'New Baseline Type', 'baseline_types' ),
        'separate_items_with_commas' => _x( 'Separate baseline types with commas', 'baseline_types' ),
        'add_or_remove_items' => _x( 'Add or remove baseline types', 'baseline_types' ),
        'choose_from_most_used' => _x( 'Choose from the most used baseline types', 'baseline_types' ),
        'menu_name' => _x( 'Baseline Types', 'baseline_types' ),
    );

    $args = array( 
        'labels' => $labels,
        'public' => true,
        'show_in_nav_menus' => true,
        'show_ui' => true,
        'show_tagcloud' => true,
        'show_admin_column' => false,
        'hierarchical' => true,

        'rewrite' => true,
        'query_var' => true
    );

    register_taxonomy( 'baseline_types', array('baseline'), $args );
}

add_action( 'init', 'register_cpt_saved_resource' );

function register_cpt_saved_resource() {

    $labels = array( 
        'name' => _x( 'Saved Resources', 'saved_resource' ),
        'singular_name' => _x( 'Saved Resource', 'saved_resource' ),
        'add_new' => _x( 'Add New', 'saved_resource' ),
        'all_items' => _x( 'Saved Resources', 'saved_resource' ),
        'add_new_item' => _x( 'Add New Saved Resource', 'saved_resource' ),
        'edit_item' => _x( 'Edit Saved Resource', 'saved_resource' ),
        'new_item' => _x( 'New Saved Resource', 'saved_resource' ),
        'view_item' => _x( 'View Saved Resource', 'saved_resource' ),
        'search_items' => _x( 'Search Saved Resources', 'saved_resource' ),
        'not_found' => _x( 'No saved resources found', 'saved_resource' ),
        'not_found_in_trash' => _x( 'No saved resources found in Trash', 'saved_resource' ),
        'parent_item_colon' => _x( 'Parent Saved Resource:', 'saved_resource' ),
        'menu_name' => _x( 'Saved Resources', 'saved_resource' ),
    );

    $args = array( 
        'labels' => $labels,
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true
    );

    register_post_type( 'saved_resource', $args );
}

add_action( 'init', 'register_taxonomy_resource_types' );

function register_taxonomy_resource_types() {

    $labels = array( 
        'name' => _x( 'Resource Types', 'resource_types' ),
        'singular_name' => _x( 'Resource Type', 'resource_types' ),
        'search_items' => _x( 'Search Resource Types', 'resource_types' ),
        'popular_items' => _x( 'Popular Resource Types', 'resource_types' ),
        'all_items' => _x( 'All Resource Types', 'resource_types' ),
        'parent_item' => _x( 'Parent Resource Type', 'resource_types' ),
        'parent_item_colon' => _x( 'Parent Resource Type:', 'resource_types' ),
        'edit_item' => _x( 'Edit Resource Type', 'resource_types' ),
        'update_item' => _x( 'Update Resource Type', 'resource_types' ),
        'add_new_item' => _x( 'Add New Resource Type', 'resource_types' ),
        'new_item_name' => _x( 'New Resource Type', 'resource_types' ),
        'separate_items_with_commas' => _x( 'Separate resource types with commas', 'resource_types' ),
        'add_or_remove_items' => _x( 'Add or remove resource types', 'resource_types' ),
        'choose_from_most_used' => _x( 'Choose from the most used resource types', 'resource_types' ),
        'menu_name' => _x( 'Resource Types', 'resource_types' ),
    );

    $args = array( 
        'labels' => $labels,
        'public' => true,
        'show_in_nav_menus' => true,
        'show_ui' => true,
        'show_tagcloud' => true,
        'show_admin_column' => false,
        'hierarchical' => true,

        'rewrite' => true,
        'query_var' => true
    );

    register_taxonomy( 'resource_types', array('saved_resource'), $args );
}