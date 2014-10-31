<?php
require_once('com_shortcode.php');
/**
 * Created by PhpStorm.
 * User: stephen
 */
class three_box_shortcode extends com_shortcode {

	const name          = 'three_box'; // the text entered by the user (inside square brackets)
	const section_name  = 'three_box_settings'; //unique section id that organizes each setting
	const section_title = 'Three Box [three_box]'; // Section header for this shortcode's settings

	public function get_name() {
		return self::name;
	}

	public function get_section_name() {
		return self::section_name;
	}

	public function get_section_title() {
		return self::section_title;
	}

	public function add_settings() {
		$this->add_setting_custom_fields_group();
	}

	public function replacement( array $attrs = null ) {
		return $this->include_file_once_return_output( plugin_dir_path( __FILE__ ) . 'three-image.php' );
	}
} 