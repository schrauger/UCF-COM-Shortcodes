<?php

/**
 * Created by PhpStorm.
 * User: stephen
 * Date: 10/16/14
 * Time: 4:09 PM
 */
abstract class com_shortcode {

	const prefix            = 'ucf_com_shortcodes:'; // prefix for generic names to make sure they are unique. colons are acceptable and help visibly when viewing the database manually.


	protected $page_slug         = 'ucf-com-shortcodes-settings'; // unique page name, also called menu_slug
	protected $option_group_name = 'ucf-com-shortcodes-settings-group'; //all ucf com shortcodes will fall into this group. then we call settings_fields(option_group_name) to get all ucf com shortcodes' settings
	private $requires_custom_field_group = false; // If true, this will not add the shortcode unless the custom field is defined on the page. do not allow children to set this manually.

	public function __construct($page_slug = null, $option_group_name = null){
		if ($page_slug){
			$this->page_slug = $page_slug;
		}
		if ($option_group_name){
			$this->option_group_name = $option_group_name;
		}
	}

	public function init_shortcodes(){
		$this->add_settings_section();
		add_action( 'admin_init', array( $this, 'page_init' ) );
	}

	/**
	 * Database key which stores the serialized options for this specific shortcode.
	 * @return string
	 */
	public function get_option_database_key(){
		return self::prefix . $this->get_name(); // database serialized array of settings. just reuse the shortcode name (doesn't have to be that way, though)
	}

	/**
	 * Grabs the database value for the $settings_id option. The value is stored in a serialized array in the database.
	 * It returns the value after sanitizing it.
	 * @param $settings_id
	 *
	 * @return string|void
	 */
	public function get_database_settings_value($settings_id){
		$data = get_option($this->get_option_database_key());
		return esc_attr($data[$settings_id]);
	}

	/**
	 * Add the settings section for this shortcode to the plugin settings page.
	 * @return mixed
	 */
	public function add_settings_section() {

		register_setting(
			$this->option_group_name,
			$this->get_option_database_key,
			array( $this, 'sanitize' ) // sanitize function
		);

		add_settings_section(
			$this->get_section_name(),
			$this->get_section_title(), // start of section text shown to user
			array( $this, 'print_section_description' ),
			$this->get_page_slug()
		);
	}

	/**
	 * Returns the unique page slug for the UCF plugin settings page.
	 * @return string
	 */
	public function get_page_slug() {
		return $this->page_slug;
	}

	/**
	 * Short sentence or paragraph just under the section title, used to describe the section
	 * @return mixed
	 */
	public function print_section_description() {
		echo '<p>Set the defaults for the [' . $this->get_section_name() . '] shortcode</p>';
	}

	/**
	 * Adds an input field to save settings. The $setting_id can be referenced
	 * by the shortcode replacement function. Generally, this is used to set
	 * defaults for optional fields the user can define.
	 *
	 * @param string $setting_id          This must be unique. Prepend with shortcode name.
	 * @param string $setting_description Optional - A description of the input.
	 * @param string $setting_label       Optional - A text label (<label> element) linked to the input.
	 */
	public function add_setting( string $setting_id, string $setting_description = "", string $setting_label = "" ) {
		add_settings_field(
			$setting_id,                      // ID used to identify the field throughout the theme
			$setting_description,                           // The label to the left of the option interface element
			array(
				$this,
				'shortcodes_input_text'
			),   // The name of the function responsible for rendering the option interface
			$this->get_page_slug(),                         // The page on which this option will be displayed
			$this->get_section_name(),         // The name of the section to which this field belongs
			array(   // The array of arguments to pass to the callback.
			         'id'      => $setting_id, // copy/paste id here
			         'label'   => $setting_label,
			         'section' => $this->get_section_name(),
			         'value'   => get_database_settings_value($setting_id)
			)
		);
	}

	/**
	 * Flags the shortcode as requiring a Custom Field group to be present,
	 * and adds a setting for the plugin to link this shortcode to a Custom Field group id.
	 */
	public function add_setting_custom_fields_group() {
		if ( ! ( $this->requires_custom_field_group ) ) {
			// only add these settings once, even if the programmer calls this function multiple times.
			$this->requires_custom_field_group = true;

			$this->add_setting(
				'ucf_com_' . $this->get_name() . '_custom_field',
				'Custom Field Group ID',
				'The ID of the custom fields\' group. The shortcode will only be replaced if this group is added to the page in the Custom Fields settings, and if specific Custom Fields within that group are properly set by the user.'
			);
		}
	}

	/**
	 * Return true if this shortcode should only be parsed if a "Custom Fields" field is
	 * set by the page. This will
	 * @return bool
	 */
	public function requires_custom_field_group() {
		return $this->requires_custom_field_group;
	}

	/**
	 * Creates the HTML code that is printed for each input on the UCF COM Shortcodes options page under this shortcode's section.
	 * @param $args
	 */
	public function shortcodes_input_text( $args ) {
		// Note the ID and the name attribute of the element should match that of the ID in the call to add_settings_field.
		// Because we only call register_setting once, all the options are stored in an array in the database. So we
		// have to name our inputs with the name of an array. ex <input type="text" id=option_key name="option_group_name[option_key]" />.
		// WordPress will automatically serialize the inputs that are in this array form and store it under
		// the option_group_name field. Then get_option will automatically unserialize and grab the value already set and pass it in via the $args as the 'value' parameter.
		$html = '<input type="text" id="' . $args[ 'id' ] . '" name="' . $args[ 'section' ] . '[' . $args[ 'id' ] . ']" value="' . ( $args[ 'value' ] ) . '"/>';

		// Here, we will take the first argument of the array and add it to a label next to the input
		$html .= '<label for="' . $args[ 'id' ] . '"> ' . $args[ 'label' ] . '</label>';
		echo $html;
	}

	/**
	 * Returns the shortcode name, the text entered by the user (inside square brackets)
	 * @return string
	 */
	abstract public function get_name();

	/**
	 * Returns the Section unique id for this shortcode's settings
	 * @return string
	 */
	abstract public function get_section_name();

	/**
	 * Returns the Section header text for this shortcode's settings
	 * @return string
	 */
	abstract public function get_section_title();




	/**
	 * Return (do not echo) the text/html that will replace the inline shortcode.
	 *
	 * @param array $attrs Optional - These are defined in the add_setting section.
	 *                     If the shortcode doesn't need any default settings, this
	 *                     array will be empty or null.
	 *
	 * @return string Must RETURN the output. Do not echo the output in this function.
	 */
	abstract public function replacement( array $attrs = null );
}
