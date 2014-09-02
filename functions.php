<?php
/*
 Plugin Name: UCF COM Shortcodes
Plugin URI: https://github.com/medweb/UCF-COM-Shortcodes
Description: Adds custom shortcodes, used in the UCF College of Medicine website.
Version: 1.0
Author: Stephen Schrauger
Author URI: https://www.schrauger.com/
License: GPLv2 or later
*/

/**
 * Settings|config page for plugin
 */

add_action('admin_init', 'ucf_com_shortcodes_settings');
function ucf_com_shortcodes_settings() {
	$settings_page = 'writing'; // display all of these settings on the 'writing' settings page
	$brightcove_section = 'brightcove_settings';
	$promo_video_section = 'promo_video_settings';
	
	/**
	 * Brightcove Options
	 */
	add_settings_section(
		$brightcove_section,
		'Custom Shortcode Options - Brightcove (brightcove)',
		'brightcove_options_callback',
		$settings_page
	);
	add_settings_field(
		'ucf_com_shortcodes_brightcove_playerID',                      // ID used to identify the field throughout the theme
		'PlayerID',                           // The label to the left of the option interface element
		'ucf_com_shortcodes_input_text',   // The name of the function responsible for rendering the option interface
		$settings_page,                          // The page on which this option will be displayed
		$brightcove_section,         // The name of the section to which this field belongs
		array(                              // The array of arguments to pass to the callback.
			'ucf_com_shortcodes_brightcove_playerID', 
			'PlayerID as defined by your Brightcove account'
		)
	);
	register_setting(
		$settings_page,
		'ucf_com_shortcodes_brightcove_playerID'
	);
	add_settings_field(
		'ucf_com_shortcodes_brightcove_playerKey',
		'PlayerKey',
		'ucf_com_shortcodes_input_text',
		$settings_page,
		$brightcove_section,
		array(
			'ucf_com_shortcodes_brightcove_playerKey',
			'PlayerKey as defined by your Brightcove account'
		)
	);
	register_setting(
		$settings_page,
		'ucf_com_shortcodes_brightcove_playerKey'
	);
	add_settings_field(
		'ucf_com_shortcodes_brightcove_default_height',
		'Default height',
		'ucf_com_shortcodes_input_text',
		$settings_page,
		$brightcove_section,
		array(
			'ucf_com_shortcodes_brightcove_default_height',
			'Default video height (in pixels)'
		)
	);
	register_setting(
		$settings_page,
		'ucf_com_shortcodes_brightcove_default_height'
	);
	add_settings_field(
		'ucf_com_shortcodes_brightcove_default_width',
		'Default width',
		'ucf_com_shortcodes_input_text',
		$settings_page,
		$brightcove_section,
		array(
			'ucf_com_shortcodes_brightcove_default_width',
			'Default video width (in pixels)'
		)
	);
	register_setting(
		$settings_page,
		'ucf_com_shortcodes_brightcove_default_width'
	);

	
	
	/** 
	 * Promo Video Options
	 */
	add_settings_section(
		$promo_video_section,
		'Custom Shortcode Options - Promo Video (promo_video)',
		'promo_video_options_callback',
		$settings_page
	);
	add_settings_field(
		'ucf_com_shortcodes_promo_bcpid',
		'Page ID',
		'ucf_com_shortcodes_input_text',
		$settings_page,
		$promo_video_section,
		array(
			'ucf_com_shortcodes_promo_bcpid',
			'Video ID'
		)
	);
	register_setting(
		$settings_page,
		'ucf_com_shortcodes_promo_bcpid'
	);
	add_settings_field(
		'ucf_com_shortcodes_promo_bckey',
		'Key',
		'ucf_com_shortcodes_input_text',
		$settings_page,
		$promo_video_section,
		array(
			'ucf_com_shortcodes_promo_bckey',
			'Video Key'
		)
	);
	register_setting(
		$settings_page,
		'ucf_com_shortcodes_promo_bckey'
	);
	add_settings_field(
		'ucf_com_shortcodes_promo_height',
		'Video Height',
		'ucf_com_shortcodes_input_text',
		$settings_page,
		$promo_video_section,
		array(
			'ucf_com_shortcodes_promo_height',
			'Height of the Promo Video'
		)
	);
	register_setting(
		$settings_page,
		'ucf_com_shortcodes_promo_height'
	);
	add_settings_field(
		'ucf_com_shortcodes_promo_width',
		'Video Width',
		'ucf_com_shortcodes_input_text',
		$settings_page,
		$promo_video_section,
		array(
			'ucf_com_shortcodes_promo_width',
			'Width of the Promo Video'
		)
	);
	register_setting(
		$settings_page,
		'ucf_com_shortcodes_promo_width'
	);

}

function brightcove_options_callback(){
	echo '<p>Set the defaults for Brightcove videos</p>';
}
function promo_video_options_callback(){
	echo '<p>Set the defaults for Promo Video</p>';
}

function ucf_com_shortcodes_input_text($args){
	// Note the ID and the name attribute of the element should match that of the ID in the call to add_settings_field
	$html = '<input type="text" id="' . $args[0] . '" name="' . $args[0] . '" value="'.get_option($args[0]) .'"/>';
	
	// Here, we will take the first argument of the array and add it to a label next to the input
	$html .= '<label for="' . $args[0] . '"> '  . $args[1] . '</label>';
	echo $html;
}

// Promo Video Options
$promo_id = get_option('ucf_com_shortcodes_promo_id');


/**
 * base_url Short Code
 * This returns the base url of the current site.
 * 
 */
add_shortcode( 'shortcode', 'base_url' );
function base_url( $attrs ) {
	extract( shortcode_atts( array(
	'attribute' => 'default parameter'
			), $attrs ) );

	return get_bloginfo( 'url' );
}

/**
 * brightcove Short Code
 * This places an inline video on the page. The video is hosted on Brightcove.com.
 */

/*
 *
*  TODO: Add variables for width and height
*
*/

add_shortcode( 'brightcove', 'bc_func' );
function bc_func( $attrs ) {

	return '<div style="display:none"></div>

	<script language="JavaScript" type="text/javascript" src="http://admin.brightcove.com/js/BrightcoveExperiences.js"></script>

	<object id="myExperience'.$attrs['id'].'" class="BrightcoveExperience '.$attrs['float'].'">
	  <param name="wmode" value="transparent">
	  <param name="bgcolor" value="#FFFFFF" />
	  <param name="width" value="'.(($attrs['width'])?$attrs['width']:get_option('ucf_com_shortcodes_brightcove_default_width')).'" />
	  <param name="height" value="'.(($attrs['height'])?$attrs['height']:get_option('ucf_com_shortcodes_brightcove_default_height')).'" />
	  <param name="playerID" value="'.get_option('ucf_com_shortcodes_brightcove_playerID').'" />
	  <param name="playerKey" value="'.get_option('ucf_com_shortcodes_brightcove_playerKey').'" />
	  <param name="isVid" value="true" />
	  <param name="isUI" value="true" />
	  <param name="dynamicStreaming" value="true" />
	  <param name="@videoPlayer" value="'.$attrs['id'].'" />
	</object>

	<script type="text/javascript">brightcove.createExperiences();</script>';

}

//------------------------------------------------------------EMbed promo video shortcode

add_shortcode( 'promo_video', 'promo_func' );
function promo_func( $attrs ) {

	return '<div class="half home-video white-box"><a href="http://video.med.ucf.edu/services/player/bcpid'.get_option('ucf_com_shortcodes_promo_bcpid').'?bckey='.get_option('ucf_com_shortcodes_promo_bckey').'&width='.get_option('ucf_com_shortcodes_promo_width').'&height='.get_option('ucf_com_shortcodes_promo_height').'" class="video-prev fancybox-video">Play the '.get_bloginfo( 'name' ).' Video</a></div>';

}
?>