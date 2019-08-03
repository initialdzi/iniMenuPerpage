<?php
/**
 * Plugin Name: Ini Menu Perpage
 * Plugin URI:  #
 * Description: Plugin for wordpress
 * Version:     1.0.0.1
 * Author:      yudhi
 * Author URI:  mailto:yudhipur19@gmail.com
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: ini-menuperpage 
 *
 * @since 1.0
 */

if(!( function_exists('dhimenu_perpage_add_custom_box') )){
	function dhimenu_perpage_add_custom_box(){
	    $screens = ['post', 'page','balancing','rebooting'];
	    foreach ($screens as $screen) {
	        add_meta_box(
	            'menuperpage_box_id',           // Unique ID
	            'Menu Metabox',  // Box title
	            'dhi_easy_menu_per_page_custom_metaboxes',  // Content callback, must be of type callable
	            $screen                   // Post type
	        );
	    }
	}
add_action('add_meta_boxes', 'dhimenu_perpage_add_custom_box');
}

if(!( function_exists('dhi_easy_menu_per_page_custom_metaboxes') )){
	function dhi_easy_menu_per_page_custom_metaboxes($post){
	    $value = get_post_meta($post->ID, '_tommusrhodus_easy_menu_per_page_menu_override', 1);;
	    ?>
	    <style type="text/css">
	    	 
			.dmp-field {
			    *zoom: 1;
			    margin: 0 0 25px;
			}
			.dmp-label {
			    width: 25%;
			    margin-right: 20px;
			}

			.dmp-label, .dmp-input {
			    vertical-align: top;
			    float: left;
			}
			.dmp-input {
			    width: 70%;
			}
			.dmp-select {
			    min-width: 300px;
			    height: 28px;
			    line-height: 28px;
			    font-size: 12px;
			    padding: 6px;
			}

	    </style>
	    <div class="dmp-field">
	    	<div class="dmp-label">
		    	<label for="wporg_field">Menu Halaman ini </label>
		    </div>
		    <div class="dmp-input"> 
				    <select name="_tommusrhodus_easy_menu_per_page_menu_override" id="wporg_field" class="dmp-select ">
				    	<option value="">Do Not Override Menu On This Page?</option>
				    	<?php
				    		$menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
							if ( is_array( $menus ) && ! empty( $menus ) ) {
								foreach ( $menus as $single_menu ) {
									//if ( is_object( $single_menu ) && isset( $single_menu->name, $single_menu->slug ) ) {
									//	$custom_menus[ $single_menu->slug ] = $single_menu->name;
									//}
									echo '<option value="something" '.selected($value, $single_menu->slug).'>'.$single_menu->name.'</option>';
								}
							}
						?>
				        
				    </select>
		    </div>
		    <div style="clear: both;;"></div>
	    </div>
	    <?php
	}
}

if(!( function_exists('dhimenu_perpage_save_postdata') )){
	function dhimenu_perpage_save_postdata($post_id){
	    if (array_key_exists('_tommusrhodus_easy_menu_per_page_menu_override', $_POST)) {
	        update_post_meta(
	            $post_id,
	            '_tommusrhodus_easy_menu_per_page_menu_override',
	            $_POST['_tommusrhodus_easy_menu_per_page_menu_override']
	        );
	    }
	}
	add_action('save_post', 'dhimenu_perpage_save_postdata');
}

if(!( function_exists('dhimenu_easy_menu_per_page_override_menu') )){
	function dhimenu_easy_menu_per_page_override_menu($args = ''){
		global $post;
	
		if( isset($post->ID) ){
			$override = get_post_meta($post->ID, '_tommusrhodus_easy_menu_per_page_menu_override', 1);
			if(!( 'none' ==  $override || false == $override || '' == $override )){
				if( is_nav_menu($override) ){
					$args['menu'] = $override;
				}	
			}
		}
		
		return $args;
			
	}
	add_filter('wp_nav_menu_args', 'dhimenu_easy_menu_per_page_override_menu', 99);
}
