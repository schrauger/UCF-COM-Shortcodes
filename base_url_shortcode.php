<?php
require_once('com_shortcode.php');
/**
 * Created by PhpStorm.
 * User: stephen
 */
class base_url_shortcode extends com_shortcode {

	const name          = 'base_url'; // the text entered by the user (inside square brackets)
	const section_name  = 'base_url_settings'; //unique section id that organizes each setting
	const section_title = 'Base URL [base_url]'; // Section header for this shortcode's settings

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
		extract( shortcode_atts( array(
			                         'attribute' => 'default parameter'
		                         ), $attrs ) );

		return get_bloginfo( 'url' );
	}
} 