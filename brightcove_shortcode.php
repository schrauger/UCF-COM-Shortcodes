<?php
require_once( 'com_shortcode.php' );

/**
 * Created by PhpStorm.
 * User: stephen
 * Date: 10/20/14
 * Time: 10:58 AM
 */
class brightcove_shortcode extends com_shortcode {

	const name                  = 'brightcove'; // the text entered by the user (inside square brackets)
	const section_name          = 'brightcove_settings'; //unique section id that organizes each setting
	const section_title         = 'Brightcove [brightcove]'; // Section header for this shortcode's settings

	const plugin_css_file  = 'brightcove.css';

	const player_id             = 'ucf_com_brightcove_playerID';
	const player_key            = 'ucf_com_brightcove_playerKey';
	const tinymce_video_id      = 'id';
	const player_height_default = 'ucf_com_brightcove_default_height';
	const tinymce_video_height  = 'height';
	const player_width_default  = 'ucf_com_brightcove_default_width';
	const tinymce_video_width   = 'width';
    const player_alignment_default = 'ucf_com_brightcove_default_alignment';
	const player_video_alignment = 'alignment';
	const player_float_default  = 'ucf_com_brightcove_default_float';
	const tinymce_video_float   = 'float';


	public function get_name() {
		return self::name;
	}

	public function get_css() {
		return self::plugin_css_file;
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

/*		$this->add_setting_tinymce_input( self::tinymce_video_id, 'Video ID' );*/

		$this->add_setting(
			self::player_height_default,
			'Default height',
			'Default video height (in pixels)'
		);
/*		$this->add_setting_tinymce_input( self::tinymce_video_height, 'Height' );*/

		$this->add_setting(
			self::player_width_default,
			'Default width',
			'Default video width (in pixels)'
		);
/*		$this->add_setting_tinymce_input( self::tinymce_video_width, 'Width' );*/

		$this->add_setting(
			self::player_alignment_default,
			'Alignment',
			'Default video alignment (left, right, center)'
		);

		$this->add_setting(
			self::player_float_default,
			'Deprecated - Default Float',
			'Default video float (left, right, auto). Kept for historical videos; use Alignment instead.'
		);
/*		$this->add_setting_tinymce_input( self::tinymce_video_width, 'Float' );*/

/*		$this->add_setting_tinymce_label( 'size_note', 'Video ID is required. Other fields are optional.' );*/
	}

	public function replacement( $attrs = null ) {
		return '<div style="display:none;"></div>

				<script language="JavaScript" type="text/javascript" src="http://admin.brightcove.com/js/BrightcoveExperiences.js"></script>
				<div class="brightcove_'. ( ( $attrs[ self::player_video_alignment ] ) ? $attrs[ self::player_video_alignment ] : $this->get_database_settings_value( self::player_alignment_default ) ) . '"
						style="width=\'' .  ( ( $attrs[ self::tinymce_video_width ] ) ? $attrs[ self::tinymce_video_width ] : $this->get_database_settings_value( self::player_width_default ) ) . '\'" >
					<object id="myExperience' . $attrs[ self::tinymce_video_id ] . '" class="BrightcoveExperience ' . ( ( $attrs[ self::tinymce_video_float ] ) ? $attrs[ self::tinymce_video_float ] : $this->get_database_settings_value( self::player_float_default ) ) . '">
					  <param name="wmode" value="transparent">
					  <param name="bgcolor" value="#FFFFFF" />
					  <param name="playerID" value="' . $this->get_database_settings_value( self::player_id ) . '" />
					  <param name="playerKey" value="' . $this->get_database_settings_value( self::player_key ) . '" />
					  <param name="height" value="' . ( ( $attrs[ self::tinymce_video_height ] ) ? $attrs[ self::tinymce_video_height ] : $this->get_database_settings_value( self::player_height_default ) ) . '" />
					  <param name="width" value="' . ( ( $attrs[ self::tinymce_video_width ] ) ? $attrs[ self::tinymce_video_width ] : $this->get_database_settings_value( self::player_width_default ) ) . '" />
					  <param name="isVid" value="true" />
					  <param name="isUI" value="true" />
					  <param name="dynamicStreaming" value="true" />
					  <param name="@videoPlayer" value="' . $attrs[ self::tinymce_video_id ] . '" />
					</object>
				</div>
				<script type="text/javascript">brightcove.createExperiences();</script>';
	}
} 