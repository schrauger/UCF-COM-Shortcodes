(function () {

    function insertContent(e, shortcode_options, shortcode_parameters){
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
                // string (ex if 'width' is specified, return "[ brightcove width=123 ]"
                return_string = return_string + parameter_name + '="' + e.data[ parameter_name ] + '" ';
            }

        }
        return_string = return_string + ']';
        return return_string;
    }

    function ucf_com_shortcode_onclick(shortcode_options, tinymce_editor) {
        var shortcode_parameters = shortcode_options.parameters;
        if (shortcode_parameters.length > 0) {
            // There are configurable parameters. loop through and add each option to a popup.

            tinymce_editor.windowManager.open({
                title: shortcode_options.text, // visible title should just be the shortcode name
                body: shortcode_parameters, // should be an array of objects
                onsubmit: function (e) {
                    // Insert content when the window form is submitted
                    tinymce_editor.insertContent(insertContent(e, shortcode_options, shortcode_parameters));
                }
            });
        } else {
            // There are no parameters. Just insert the shortcode.
            tinymce_editor.insertContent(shortcode_options.text);
        }
    }



    tinymce.PluginManager.add('ucf_com_shortcodes', function (tinymce_editor /*, url*/) {
        var tinymce_options_array = ucf_com_shortcodes_tinymce; // this variable is set by WordPress via the wp_localize_script function, which passes custom variables directly to the javascript file.
        // array will be something like:
        /*
        [
          {
            title: "hovertext",
            shortcode: "shortcode1",
            parameters: [
              {
                name: "input1",
                type: "textbox",
                label: "Label for input1"
              },
              {
                name: "input2",
                type: "textbox",
                label: "Label for input2"
              },
            ]
          },
          {
            title: "hovertext",
            shortcode: "shortcode2",
            parameters: [
              {
                name: "input1",
                type: "textbox",
                label: "Label for input1"
              }
            ]
          }
        ]



         */
        function build_menu(tinymce_options_array, tinymce_editor) {


            var return_array = []; // tinymce requires an array of objects (with the object containing specific parameters)
            var loop_counter;

            //#### 2. add a menu item for each shortcode.
            for (loop_counter = 0; loop_counter < tinymce_options_array.length; loop_counter++) {
                return_array.push(
                    {
                        title: tinymce_options_array[ loop_counter ].title, // hover text for shortcode (describe what it does)
                        text: tinymce_options_array[ loop_counter ].shortcode, // actual shortcode name
                        onclick: ucf_com_shortcode_onclick(tinymce_options_array[ loop_counter ], tinymce_editor)
                        //#### 3. on click, check for any customizable parameters. if any exist, pop up with an interface for setting the options. otherwise, just insert the shortcode.
                    }
                );
            }
            return return_array;
        }

        var build_menu_obj = build_menu(tinymce_options_array, tinymce_editor);

        //#### 1. add a button for custom shortcodes. this will be a menu.
        tinymce_editor.addButton('ucf_com_shortcodes_key', {
            title: 'Custom UCF Shortcodes', // hover text for icon
            text: 'Shortcodes', // actual icon label in the editor
            icon: 'icon dashicons-format-video', // icon
            menu: build_menu_obj
        });

    });

    /* tinymce.PluginManager.add('ucf_com_brightcove', function (editor, url) {

         // Add a button that opens a window
         editor.addButton('ucf_com_brightcove_key', {
             title: 'Brightcove Video Shortcode',
             text: 'Brightcove',
             icon: 'icon dashicons-format-video', // video icon
             onclick: function () {
                 // Open window
                 editor.windowManager.open({
                     title: 'Brightcove Video',
                     body: [
                         {
                             type: 'textbox',
                             name: 'id',
                             label: 'Video ID'
                         },
                         {
                             type: 'textbox',
                             name: 'width',
                             label: 'Width'
                         },
                         {
                             type: 'textbox',
                             name: 'height',
                             label: 'Height'
                         },
                         {
                             type: 'label',
                             name: 'size_note',
                             text: 'Leave height/width blank for default'
                         }
                     ],
                     onsubmit: function (e) {
                         // Insert content when the window form is submitted
                         editor.insertContent('[brightcove id=' + e.data.id +
                         ((e.data.width) ? ' width=' + e.data.width : '') +
                         ((e.data.height) ? ' height=' + e.data.height : '') +
                         ']');
                     }

                 });
             }

         });

     });
    */
})
();