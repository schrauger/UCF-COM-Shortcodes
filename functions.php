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

class ucf_com_shortcodes_settings{
	private $brightcove_options;
	const brightcove_name = 'brightcove';
	const brightcove_section = 'brightcove_settings';
	
	private $baseurl_options;
	const baseurl_name = 'baseurl';
	const baseurl_section = 'baseurl_settings';
	
	const page_title = 'UCF COM Shortcode Settings';
	const menu_title = 'UCF COM Shortcode Settings';
	const capability = 'manage_options'; // user capability required to view the page
	const page_slug = 'ucf-com-shortcodes-settings'; // unique page name, also called menu_slug
	const option_group_name = 'ucf-com-shortcodes-settings-group';
	
	
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
	}
	
	/**
	 * Tells wordpress about a new page and what function to call to create it
	 */
	public function add_plugin_page(){
		// This page will be under "Settings" menu. add_options_page is merely a WP wrapper for add_submenu_page specifying the 'options-general' menu as parent
		add_options_page(
			self::page_title,
			self::menu_title,
			self::capability,
			self::page_slug,
			array( $this, 'create_settings_page') // since we are putting settings on our own page, we also have to define how to print out the settings
		);
	}
	
	/**
	 * Tells Wordpress how to output the page
	 */
	public function create_settings_page() {
		// Set class property
		$this->brightcove_options = get_option( self::brightcove_name );
		$this->baseurl_options = get_option( self::baseurl_name );
		?>
	        <div class="wrap">
	            <?php screen_icon(); ?>
	            <h2>My Settings</h2>           
	            <form method="post" action="options.php">
	            <?php
	                // This prints out all hidden setting fields
	                settings_fields( self::option_group_name );   
	                do_settings_sections( self::page_slug );
	                submit_button(); 
	            ?>
	            </form>
	        </div>
        <?php
	}
	
	public function page_init() {
		register_setting(
			self::option_group_name,
			self::brightcove_name, // brightcove array of options
			array( $this, 'sanitize') // sanitize function
		);
		
		add_settings_section(
			self::brightcove_section,
			'Custom Shortcode Options - Brightcove (brightcove)',
			array( $this, 'brightcove_section_info'),
			self::page_slug
		);
		
		add_settings_field(
			'brightcove_playerID',                      // ID used to identify the field throughout the theme
			'PlayerID (deprecated)',                           // The label to the left of the option interface element
			array( $self, 'shortcodes_input_text'),   // The name of the function responsible for rendering the option interface
			$settings_page,                          // The page on which this option will be displayed
			self::brightcove_section,         // The name of the section to which this field belongs
			array(                              // The array of arguments to pass to the callback.
				'id' => 'brightcove_playerID', // copy/paste id here
				'label' => 'PlayerID as defined by your Brightcove account. This has been replaced by the playerKey field.',
				'section' => self::brightcove_section
			)
		);
	}
	
	public function brightcove_section_info(){
		echo '<p>Set the defaults for Brightcove videos</p>';
	}
	public function shortcodes_input_text($args){
		// Note the ID and the name attribute of the element should match that of the ID in the call to add_settings_field
		$html = '<input type="text" id="' . $args['id'] . '" name="' . $args['section'] . '[' . $args['id'] . ']" value="'.get_option($args['id']) .'"/>';
		// @TODO make sure the input is sanitized. it should be from the sanitize function on save, but probably
		//  should check on display as well.
		
		// Here, we will take the first argument of the array and add it to a label next to the input
		$html .= '<label for="' . $args['id'] . '"> '  . $args['label'] . '</label>';
		echo $html;
	}

}

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
		'PlayerID (deprecated)',                           // The label to the left of the option interface element
		'ucf_com_shortcodes_input_text',   // The name of the function responsible for rendering the option interface
		$settings_page,                          // The page on which this option will be displayed
		$brightcove_section,         // The name of the section to which this field belongs
		array(                              // The array of arguments to pass to the callback.
			'ucf_com_shortcodes_brightcove_playerID', 
			'PlayerID as defined by your Brightcove account. This has been replaced by the playerKey field.'
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


/**
 * TinyMCE Buttons
 */
//add_action( 'admin_head', 'ucf_com_tinymce');	// only add these filters with admin pages
//function ucf_com_tinymce(){
	add_filter('mce_external_plugins','ucf_com_tinymce_brightcove_js');
	add_filter('mce_css', 'ucf_com_tinymce_brightcove_css');
	add_filter('mce_buttons','ucf_com_tinymce_brightcove_button');
//}
function ucf_com_tinymce_brightcove_js($plugin_array) {
	$plugin_array['ucf_com_brightcove'] = plugins_url( '/plugin.js', __FILE__); // include the javascript for the button, located inside the current plugin folder
	return $plugin_array;
}
function ucf_com_tinymce_brightcove_css($mce_css) {
	wp_register_style('ucf_com_brightcove_css_file', plugins_url('style.css', __FILE__));
	wp_enqueue_style('ucf_com_brightcove_css_file');
	/*
	  
	if ( ! empty( $mce_css ) )
		$mce_css .= ',';

	$mce_css .= plugins_url( 'style.css', __FILE__ );
	
	
	*/
	return $mce_css;
	
}
function ucf_com_tinymce_brightcove_button($buttons){
	array_push($buttons, 'separator', 'ucf_com_brightcove_key');
	return $buttons;
}

?>