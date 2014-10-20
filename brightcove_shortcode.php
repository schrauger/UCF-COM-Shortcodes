<?php

/**
 * Created by PhpStorm.
 * User: stephen
 * Date: 10/20/14
 * Time: 10:58 AM
 */
class brightcove_shortcode extends com_shortcode {

	const name          = 'brightcove'; // the text entered by the user (inside square brackets)
	const section_name    = 'brightcove_settings'; //unique section id that organizes each setting
	const section_title = 'Brightcove [brightcove]'; // Section header for this shortcode's settings
	const player_id = 'ucf_com_brightcove_playerID';
	const player_key = 'ucf_com_brightcove_playerKey';
	const player_height = 'ucf_com_brightcove_default_height';
	const player_width = 'ucf_com_brightcove_default_width';

	public function get_name() {
		return self::name;
	}

	public function get_section_name() {
		return self::section_name;
	}

	public function get_section_title() {
		return self::section_title;
	}

	public function print_section_description() {
		echo '<p>Set the defaults for Brightcove videos</p>';
	}

	public function add_settings() {

		$this->add_setting(
			self::player_id,// ID used to identify the field throughout the theme
			'PlayerID (deprecated)',                           // The label to the left of the option interface element
			'PlayerID as defined by your Brightcove account. This has been replaced by the playerKey field.'
		);
		$this->add_setting(
			self::player_key,
			'PlayerKey',
			'PlayerKey as defined by your Brightcove account.'
		);
		$this->add_setting(
			self::player_height,
			'Default height',
			'Default video height (in pixels)'
		);
		$this->add_setting(
			self::player_width,
			'Default width',
			'Default video width (in pixels)'
		);
	}

	public function replacement(array $attrs = null) {
		return '<div style="display:none"></div>

				<script language="JavaScript" type="text/javascript" src="http://admin.brightcove.com/js/BrightcoveExperiences.js"></script>

				<object id="myExperience' . $attrs[ 'id' ] . '" class="BrightcoveExperience ' . $attrs[ 'float' ] . '">
				  <param name="wmode" value="transparent">
				  <param name="bgcolor" value="#FFFFFF" />
				  <param name="playerID" value="' . get_option( self::player_id ) . '" />
				  <param name="playerKey" value="' . get_option( self::player_key ) . '" />
				  <param name="height" value="' . ( ( $attrs[ 'height' ] ) ? $attrs[ 'height' ] : get_option( self::player_height ) ) . '" />
				  <param name="width" value="' . ( ( $attrs[ 'width' ] ) ? $attrs[ 'width' ] : get_option( self::player_width ) ) . '" />
				  <param name="isVid" value="true" />
				  <param name="isUI" value="true" />
				  <param name="dynamicStreaming" value="true" />
				  <param name="@videoPlayer" value="' . $attrs[ 'id' ] . '" />
				</object>

				<script type="text/javascript">brightcove.createExperiences();</script>';
	}
} 