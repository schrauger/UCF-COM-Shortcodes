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
class ucf_com_shortcodes_settings {
	private $brightcove_options;
	const brightcove_name    = 'brightcove';
	const brightcove_section = 'brightcove_settings';

	private $baseurl_options;
	const baseurl_name    = 'baseurl';
	const baseurl_section = 'baseurl_settings';

	const page_title        = 'UCF COM Shortcode Settings';
	const menu_title        = 'Shortcode Settings';
	const capability        = 'manage_options'; // user capability required to view the page
	const page_slug         = 'ucf-com-shortcodes-settings'; // unique page name, also called menu_slug
	const option_group_name = 'ucf-com-shortcodes-settings-group';


	public function __construct() {
		register_activation_hook( __FILE__, array(
			$this,
			'on_activation'
		) ); //call the 'on_activation' function when plugin is first activated
		register_deactivation_hook( __FILE__, array(
			$this,
			'on_deactivation'
		) ); //call the 'on_deactivation' function when plugin is deactivated
		register_uninstall_hook( __FILE__, array(
			$this,
			'on_uninstall'
		) ); //call the 'uninstall' function when plugin is uninstalled completely

		// Register the 'settings' page
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );

		// Add a link from the plugin page to this plugin's settings page
		add_filter( 'plugin_row_meta', array( $this, 'plugin_action_links' ), 10, 2 );

		// Register the tinymce hooks to create buttons
		add_filter( 'mce_external_plugins', array( $this, 'tinymce_brightcove_js' ) );
		add_filter( 'mce_css', array( $this, 'tinymce_brightcove_css' ) );
		add_filter( 'mce_buttons', array( $this, 'tinymce_brightcove_button' ) );

		$this->init_shortcodes();
	}

	/**
	 * Function that is run when the plugin is activated via the plugins page
	 */
	public function on_activation() {
		// stub
	}

	public function on_deactivation() {
		// stub
	}

	public function on_uninstall() {
		// stub
	}

	/**
	 * Adds a link to this plugin's setting page directly on the WordPress plugin list page
	 *
	 * @param $links
	 * @param $file
	 *
	 * @return array
	 */
	public function plugin_action_links( $links, $file ) {
		if ( strpos( __FILE__, $file ) !== false ) {
			$links = array_merge(
				$links,
				array(
					'settings' => '<a href="' . admin_url( 'options-general.php?page=' . self::page_slug ) . '">' . __( 'Settings', self::page_slug ) . '</a>'
				)
			);
		}

		return $links;
	}

	/**
	 * Calls all of the 'add_shortcode' api calls, after running duplication checks
	 */
	public function init_shortcodes() {
		$this->_init_shortcodes( 'brightcove', array( $this, 'shortcode_brightcove_function' ) );
		$this->_init_shortcodes( 'base_url', array( $this, 'shortcode_base_url_function' ) );
		$this->_init_shortcodes( 'three_box', array( $this, 'shortcode_three_bar_function' ) );
		$this->_init_shortcodes( 'eight_box', array( $this, 'shortcode_eight_box_function' ) );

	}

	/**
	 * Adds a shortcode, but only if the shortcode isn't already defined. If it is,
	 * simply skip that shortcode (and probably log the event so the user doesn't
	 * get frustrated trying to figure out why the shortcode isn't working the way
	 * they wanted).
	 *
	 * @param string   $tag  Shortcode tag to be searched in post content.
	 * @param callable $func Hook to run when shortcode is found.
	 */
	private function _init_shortcodes( $tag, $func ) {
		if ( ! ( shortcode_exists( $tag ) ) ) {
			add_shortcode( $tag, $func );
		}

	}

	/**
	 * Tells WordPress about a new page and what function to call to create it
	 */
	public function add_plugin_page() {
		// This page will be under "Settings" menu. add_options_page is merely a WP wrapper for add_submenu_page specifying the 'options-general' menu as parent
		add_options_page(
			self::page_title,
			self::menu_title,
			self::capability,
			self::page_slug,
			array(
				$this,
				'create_settings_page'
			) // since we are putting settings on our own page, we also have to define how to print out the settings
		);
	}

	/**
	 * Tells WordPress how to output the page
	 */
	public function create_settings_page() {
		// Set class property
		$this->brightcove_options = get_option( self::brightcove_name );
		$this->baseurl_options    = get_option( self::baseurl_name );
		?>
		<div class="wrap" >

			<h2 ><?php echo self::page_title ?></h2 >

			<form method="post" action="options.php" >
				<?php
				// This prints out all hidden setting fields
				settings_fields( self::option_group_name );
				do_settings_sections( self::page_slug );
				submit_button();
				?>
			</form >
		</div >
	<?php
	}

	public function page_init() {
		/**
		 * We only need to register_setting once for each options_array_name. Using an array
		 * means we don't have to call register_setting for each individual option.
		 */
		register_setting(
			self::option_group_name,
			self::brightcove_name, // brightcove array of options
			array( $this, 'sanitize' ) // sanitize function
		);

		add_settings_section(
			self::brightcove_section,
			'Brightcove (brightcove)', // start of section text shown to user
			array( $this, 'brightcove_section_info' ),
			self::page_slug
		);

		add_settings_section(
			self::baseurl_section,
			'Base URL (base_url)', // start of section text shown to user
			array( $this, 'base_url_section_info' ),
			self::page_slug
		);

		add_settings_field(
			'brightcove_playerID',                      // ID used to identify the field throughout the theme
			'PlayerID (deprecated)',                           // The label to the left of the option interface element
			array(
				$this,
				'shortcodes_input_text'
			),   // The name of the function responsible for rendering the option interface
			self::page_slug,                          // The page on which this option will be displayed
			self::brightcove_section,         // The name of the section to which this field belongs
			array(                              // The array of arguments to pass to the callback.
			                                    'id'      => 'brightcove_playerID', // copy/paste id here
			                                    'label'   => 'PlayerID as defined by your Brightcove account. This has been replaced by the playerKey field.',
			                                    'section' => self::brightcove_section
			)
		);
		add_settings_field(
			'brightcove_playerKey',
			'PlayerKey',
			array(
				$this,
				'shortcodes_input_text'
			),   // The name of the function responsible for rendering the option interface
			self::page_slug,                          // The page on which this option will be displayed
			self::brightcove_section,         // The name of the section to which this field belongs
			array(                              // The array of arguments to pass to the callback.
			                                    'id'      => 'brightcove_playerKey', // copy/paste id here
			                                    'label'   => 'PlayerKey as defined by your Brightcove account.',
			                                    'section' => self::brightcove_section
			)
		);
		add_settings_field(
			'brightcove_default_height',
			'Default height',
			array(
				$this,
				'shortcodes_input_text'
			),   // The name of the function responsible for rendering the option interface
			self::page_slug,                          // The page on which this option will be displayed
			self::brightcove_section,         // The name of the section to which this field belongs
			array(                              // The array of arguments to pass to the callback.
			                                    'id'      => 'brightcove_default_height', // copy/paste id here
			                                    'label'   => 'Default video height (in pixels)',
			                                    'section' => self::brightcove_section
			)
		);
		add_settings_field(
			'brightcove_default_width',
			'Default width',
			array(
				$this,
				'shortcodes_input_text'
			),   // The name of the function responsible for rendering the option interface
			self::page_slug,                          // The page on which this option will be displayed
			self::brightcove_section,         // The name of the section to which this field belongs
			array(                              // The array of arguments to pass to the callback.
			                                    'id'      => 'brightcove_default_width', // copy/paste id here
			                                    'label'   => 'Default video width (in pixels)',
			                                    'section' => self::brightcove_section
			)
		);
	}

	public function brightcove_section_info() {
		echo '<p>Set the defaults for Brightcove videos</p>';
	}

	/**
	 * Prints out the HTML <input> and <label> code for each item on this plugin's settings page.
	 *
	 * @param $args
	 */
	public function shortcodes_input_text( $args ) {
		// Note the ID and the name attribute of the element should match that of the ID in the call to add_settings_field
		$html = '<input type="text" id="' . $args[ 'id' ] . '" name="' . $args[ 'section' ] . '[' . $args[ 'id' ] . ']" value="' . get_option( $args[ 'id' ] ) . '"/>';
		// @TODO make sure the input is sanitized. it should be from the sanitize function on save, but probably should check on display as well.

		// Here, we will take the first argument of the array and add it to a label next to the input
		$html .= '<label for="' . $args[ 'id' ] . '"> ' . $args[ 'label' ] . '</label>';
		echo $html;
	}

	/**
	 * brightcove Short Code
	 * Replaces the 'brightcove' keyword with the actual HTML desired and returns it to wordpress.
	 * This places an inline video on the page. The video is hosted on Brightcove.com.
	 *
	 * @param array $attrs Attributes are passed by wordpress automatically.
	 *
	 * @return string
	 *
	 */
	public function shortcode_brightcove_function( $attrs ) {

		return '<div style="display:none"></div>

	<script language="JavaScript" type="text/javascript" src="http://admin.brightcove.com/js/BrightcoveExperiences.js"></script>

	<object id="myExperience' . $attrs[ 'id' ] . '" class="BrightcoveExperience ' . $attrs[ 'float' ] . '">
	  <param name="wmode" value="transparent">
	  <param name="bgcolor" value="#FFFFFF" />
	  <param name="width" value="' . ( ( $attrs[ 'width' ] ) ? $attrs[ 'width' ] : get_option( 'ucf_com_shortcodes_brightcove_default_width' ) ) . '" />
	  <param name="height" value="' . ( ( $attrs[ 'height' ] ) ? $attrs[ 'height' ] : get_option( 'ucf_com_shortcodes_brightcove_default_height' ) ) . '" />
	  <param name="playerID" value="' . get_option( 'ucf_com_shortcodes_brightcove_playerID' ) . '" />
	  <param name="playerKey" value="' . get_option( 'ucf_com_shortcodes_brightcove_playerKey' ) . '" />
	  <param name="isVid" value="true" />
	  <param name="isUI" value="true" />
	  <param name="dynamicStreaming" value="true" />
	  <param name="@videoPlayer" value="' . $attrs[ 'id' ] . '" />
	</object>

	<script type="text/javascript">brightcove.createExperiences();</script>';

	}

	/**
	 * base_url Short Code
	 * Replaces the 'base_url' keyword with the actual HTML desired and returns it to wordpress.
	 * This returns the base url of the current site. That way, a user can create an absolute
	 * link without knowing the current domain.
	 *
	 * @param array $attrs Attributes are passed by wordpress automatically.
	 *
	 * @return string
	 *
	 */
	public function shortcode_base_url_function( $attrs ) {
		extract( shortcode_atts( array(
			                         'attribute' => 'default parameter'
		                         ), $attrs ) );

		return get_bloginfo( 'url' );
	}

	public function shortcode_eight_box_function() {
		if ( '' !== get_field( 'eight_image_box_1_title' ) ) {
			return $this->include_file_once_return_output( plugin_dir_path( __FILE__ ) . 'eight-image.php' );
		} else {
			return '';
		}
	}

	public function shortcode_three_bar_function() {
		if ( '' !== get_field( 'left_item_title' ) ) {
			return $this->include_file_once_return_output( plugin_dir_path( __FILE__ ) . 'three-bar.php' );
		} else {
			return '';
		}
	}

	/**
	 * Includes a php file once. If the php file prints or echos anything,
	 * this function will prevent it from echoing out and will instead
	 * return the entire echo contents inside a string.
	 * If called multiple times with the same file path, it will only
	 * include the file the first time.
	 *
	 * @param $file_path
	 *
	 * @return string
	 */
	public function include_file_once_return_output( $file_path ) {
		return $this->_include_file_return_output($file_path, true);
	}

	/**
	 * Includes a php file. If the php file prints or echos anything,
	 * this function will prevent it from echoing out and will instead
	 * return the entire echo contents inside a string.
	 *
	 * @param $file_path
	 *
	 * @return string
	 */
	public function include_file_return_output( $file_path ) {
		return $this->_include_file_return_output($file_path, false);
	}

	private function _include_file_return_output( $file_path, $include_once = false){
		ob_start(); // create a new buffer
		chdir( dirname( $_SERVER[ 'SCRIPT_FILENAME' ] ) ); // apache may reset file paths when a new buffer is started. reset to current.
		if ( ! empty( $file_path ) ) {
			if ($include_once) {
				/** @noinspection PhpIncludeInspection */
				include_once( $file_path ); // only include the first time the short code is used.
			} else {
				/** @noinspection PhpIncludeInspection */
				include( $file_path ); // only include the first time the short code is used.
			}
		}

		$output = ob_get_clean(); // stop the buffer and get the contents that would have been echoed out.
		return $output;
	}

	/**
	 * Include this plugin's javascript file.
	 *
	 * @param $plugin_array
	 *
	 * @return mixed
	 */
	public function tinymce_brightcove_js( $plugin_array ) {
		$plugin_array[ 'ucf_com_brightcove' ] = plugins_url( '/plugin.js', __FILE__ ); // include the javascript for the button, located inside the current plugin folder
		return $plugin_array;
	}

	/**
	 * Include this plugin's css file.
	 *
	 * @param $mce_css
	 *
	 * @return mixed
	 */
	public function tinymce_brightcove_css( $mce_css ) {
		wp_register_style( 'ucf_com_brightcove_css_file', plugins_url( 'style.css', __FILE__ ) );
		wp_enqueue_style( 'ucf_com_brightcove_css_file' );

		return $mce_css;

	}

	/**
	 * Add the buttons on the tinymce interface
	 *
	 * @param $buttons
	 *
	 * @return mixed
	 */
	public function tinymce_brightcove_button( $buttons ) {
		array_push( $buttons, 'separator', 'ucf_com_brightcove_key' );

		return $buttons;
	}

}

$ucf_com_shortcodes_settings_object = new ucf_com_shortcodes_settings();