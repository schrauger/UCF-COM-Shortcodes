<?php

/**
 * Created by PhpStorm.
 * User: stephen
 */
class two_column_shortcode extends com_shortcode {

	const name          = 'two_column'; // the text entered by the user (inside square brackets)
	const section_name  = 'two_column_settings'; //unique section id that organizes each setting
	const section_title = 'Two Column [two_column]'; // Section header for this shortcode's settings

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
		return $this->include_file_once_return_output( plugin_dir_path( __FILE__ ) . 'two-column.php' );
	}
} 