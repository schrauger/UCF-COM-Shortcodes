<?php
require_once( 'base_url_shortcode.php' );
require_once( 'brightcove_shortcode.php' );
require_once( 'eight_box_shortcode.php' );
require_once( 'three_box_shortcode.php' );
require_once( 'two_column_shortcode.php' );
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

	const option_group_name = 'ucf-com-shortcodes-settings-group';
	const page_title        = 'UCF COM Shortcode Settings'; //
	const menu_title        = 'Shortcode Settings';
	const capability        = 'manage_options'; // user capability required to view the page
	const page_slug         = 'ucf-com-shortcodes-settings'; // unique page name, also called menu_slug

/*	const javascript_handle = 'ucf_com_shortcodes_js'; // just a unique handle for WordPress
	const javascript_var    = 'ucf_com_shortcodes_tinymce'; // global javascript variable that holds tinymce menu structure*/

	private $shortcodes_wp_builtin = array();
	private $shortcodes_ucf_com    = array();

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
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'init', array($this, 'page_init'));

		// Add a link from the plugin page to this plugin's settings page
		add_filter( 'plugin_row_meta', array( $this, 'plugin_action_links' ), 10, 2 );

		// Register the tinymce hooks to create buttons
		add_filter( 'mce_external_plugins', array( $this, 'tinymce_shortcodes_js' ) );
		add_filter( 'mce_css', array( $this, 'tinymce_shortcodes_css' ) );
		add_filter( 'mce_buttons', array( $this, 'tinymce_shortcodes_button' ) );

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
	/**
	 * @return com_shortcode[]
	 */
	public static function get_shortcodes() {
		return array(
			new brightcove_shortcode(),
			new base_url_shortcode(),
			new eight_box_shortcode(),
			new three_box_shortcode(),
			new two_column_shortcode()
		);
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

	public function admin_init() {
		$this->shortcodes_ucf_com = self::get_shortcodes();
		foreach ($this->shortcodes_ucf_com as $shortcode){
			$shortcode->init_shortcode_settings();
		}
	}

	public function page_init() {
		$this->shortcodes_ucf_com = self::get_shortcodes();
		foreach ($this->shortcodes_ucf_com as $shortcode){
			$shortcode->init_shortcode();
		}
	}

	/**
	 * Tells WordPress how to output the page
	 */
	public function create_settings_page() {
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


	/**
	 * Include this plugin's javascript file.
	 *
	 * @param $plugin_array
	 *
	 * @return mixed
	 */
	public function tinymce_shortcodes_js( $plugin_array ) {

		/**
		 * Send the dynamic javascript code to plugin.js so that it can create menu structures for tinymce.
		 */
		wp_register_script( self::javascript_handle, plugins_url( 'dummy.js', __FILE__ ) ); // use dummy.js so that we don't include plugin.js twice (avoid duplicate functions)
		wp_localize_script( self::javascript_handle, self::javascript_var, "hello" );

		/**
		 * Include plugin.js in the tinymce way (which doesn't use wp_register_script but rather uses its own function)
		 */
		$plugin_array[ 'ucf_com_shortcodes_key' ] = plugins_url( 'plugin.js', __FILE__ ); // include the javascript for the button, located inside the current plugin folder
		return $plugin_array;
	}

	/**
	 * Include this plugin's css file.
	 *
	 * @param $mce_css
	 *
	 * @return mixed
	 */
	public function tinymce_shortcodes_css( $mce_css ) {
		wp_register_style( 'ucf_com_shortcodes_css_file', plugins_url( 'style.css', __FILE__ ) );
		wp_enqueue_style( 'ucf_com_shortcodes_css_file' );

		return $mce_css;

	}

	/**
	 * Add the buttons on the tinymce interface
	 *
	 * @param $buttons
	 *
	 * @return mixed
	 */
	public function tinymce_shortcodes_button( $buttons ) {
		array_push( $buttons, 'separator', 'ucf_com_shortcodes_key' );

		return $buttons;
	}

	/**
	 * Returns the full javascript array of shortcode settings for tinymce
	 * @return string
	 */
	public function tinymce_array() {
		$return_string = '[ ';
		foreach ( $this->shortcodes_ucf_com as $shortcode ) {
			/** @var $shortcode com_shortcode */
			$return_string = $return_string . $shortcode->get_tinymce_javascript_string() . ', ';
		}
		$return_string = $return_string . ']';

		return $return_string;
	}

}

new ucf_com_shortcodes_settings();
