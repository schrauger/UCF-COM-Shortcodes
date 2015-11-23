(function () {
    tinymce.PluginManager.add('ucf_com_shortcodes_key', function (editor, url){

        // Add a button that opens a window
        editor.addButton('ucf_com_shortcodes_key', {
            title: 'Custom UCF Shortcodes',
            text: 'Shortcodes',
            icon: false,
            type: 'menubutton',
            menu: [
                /*{
                    title: 'Brightcove Videos',
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
                                    type: 'textbox',
                                    name: 'float', // called float for historical reasons; doesn't actually use float for center
                                    label: 'Alignment (left, right, center)'
                                },
                                {
                                    type: 'label',
                                    name: 'size_note',
                                    text: 'Video ID is required. Other fields are optional.'
                                }
                            ],
                            onsubmit: function (e) {
                                // Insert content when the window form is submitted
                                editor.insertContent('[brightcove id=' + e.data.id +
                                ((e.data.width) ? ' width=' + e.data.width : '') +
                                ((e.data.height) ? ' height=' + e.data.height : '') +
                                ((e.data.float) ? ' float=' + e.data.float : '' ) +
                                ']');
                            }

                        });
                    }
                },*/
                {
                    title: 'Eight Box image layout',
                    text: 'Eight Box',
                    icon: 'icon dashicons-format-image', // video icon
                    onclick: function(){
                        editor.insertContent('[eight_box]');
                    }
                },
                {
                    title: 'Three Box image layout',
                    text: 'Three Box',
                    icon: 'icon dashicons-format-image', // video icon
                    onclick: function(){
                        editor.insertContent('[three_box]');
                    }
                },
                {
                    title: 'Two columns, side by side',
                    text: 'Two Column',
                    icon: 'icon dashicons-welcome-widgets-menus', // video icon
                    onclick: function(){
                        editor.insertContent('[two_column]');
                    }
                },
                {
                    title: 'Domain portion of URL',
                    text: 'Base URL',
                    icon: 'icon dashicons-wordpress', // video icon
                    onclick: function(){
                        editor.insertContent('[base_url]');
                    }
                }
            ]


        });

    });


})();