<?php
$shortcodes = ucf_com_shortcodes_settings::get_shortcodes();

?>
(function () {

	function ucf_com_shortcode_onclick(shortcode_options, tinymce_editor) {
		var shortcode_parameters = shortcode_options.parameters;
		if (shortcode_parameters.length > 0) {
			// There are configurable parameters. loop through and add each option to a popup.

			tinymce_editor.windowManager.open({
				title: shortcode_options.text, // visible title should just be the shortcode name
				body: shortcode_parameters, // should be an array of objects
				onsubmit: function (e) {
					// Insert content when the window form is submitted
					tinymce_editor.insertContent(function () {
						var return_string;
						var loop_counter;
						var parameter_name = ''; // in the loop, this gets set to that array index's name
						return_string = return_string + '[ ' + shortcode_options.text + ' ';

						for (loop_counter = 0; loop_counter < shortcode_parameters.length; loop_counter++) {
							// loop through each possible parameter for this shortcode. if the user ended up
							// setting any of the parameters, add that to the return string.

							parameter_name = shortcode_parameters[ loop_counter ].name; // parameter name, which is parsed by the shortcode parser later on.

							if (e.data[ parameter_name ]) {
								// if user specified a value for the parameter_name parameter, put it in the return
								// string (ex if 'width' is specified, return "[ brightcove length=123 ]"
								return_string = return_string + parameter_name + '=' + e.data[ parameter_name ] + ' ';
							}

						}
						return_string = return_string + ']';
						return return_string;

					});
				}
			});
		} else {
			// There are no parameters. Just insert the shortcode.
			tinymce_editor.insertContent(shortcode_options.text);
		}
	}


	tinymce.PluginManager.add('ucf_com_shortcodes', function (tinymce_editor /*, url*/) {
		//var tinymce_options_array = blah; // this variable is set by WordPress via the wp_localize_script function, which passes custom variables directly to the javascript file.
		//#### 1. add a button for custom shortcodes. this will be a menu.
		tinymce_editor.addButton('ucf_com_shortcodes_key', {
			title: 'Custom UCF Shortcodes', // hover text for icon
			text: 'Shortcodes', // actual icon label in the editor
			icon: 'icon dashicons-format-video', // icon
			menu: [
				<?php
					foreach ($shortcodes as $shortcode){
						?>
				{
					title: <?php echo $shortcode->get_name(); ?>,
					onclick: function () {
						<?php menu_setup($shortcode);?>
					}
				}
				<?php
			}
		?>
			]
		}
	});



})();

<?php
function menu_setup(com_shortcode $shortcode){
	$popup_options = $shortcode->get_tinymce_parameters_formatted();
	if ($popup_options){
		// options defined. pop up and let user fill out a form.
		?>
tinymce_editor.windowManager.open({
	title: <?php echo $shortcode->get_name()?>, // visible title should just be the shortcode name
	body: <?php echo $popup_options?>, // should be an array of objects
	onsubmit: function (e) {
		// Insert content when the window form is submitted
		tinymce_editor.insertContent('[ <?php echo $shortcode->get_name();?>' +
			<?php
			foreach ($shortcode->get_tinymce_parameters() as $shortcode_parameter){
			?>
			((e.data.<?php echo $shortcode_parameter['name']?>) ? ' <?php echo $shortcode_parameter['name']?>=' + e.data.<?php echo $shortcode_parameter['name'];?> : '') +
			<?php
			}
			?>
			']'
		)
		;
	}
});
<?php
	} else {
		// no tinymce options defined. don't pop up anything; just return the shortcode.
		?>tinymce_editor.insertContent(shortcode_options.text);<?php
	}
}
?>