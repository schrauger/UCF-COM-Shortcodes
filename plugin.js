(function () {
    tinymce.PluginManager.add('ucf_com_shortcodes_key', function (editor, url){

        // Add a button that opens a window
        editor.addButton('ucf_com_shortcodes_key', {
            title: 'Custom UCF Shortcodes',
            text: 'Shortcodes',
            icon: false,
            menu: [
                {
                    text: 'Brightcove',
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
                },
                {
                    text: 'Eight Box',
                    onclick: function(){
                        editor.insertContent('[eight_box]');
                    }
                },
                {
                    text: 'Three Box',
                    onclick: function(){
                        editor.insertContent('[three_box]');
                    }
                },
                {
                    text: 'Two Column',
                    onclick: function(){
                        editor.insertContent('[two_column]');
                    }
                },
                {
                    text: 'Base URL',
                    onclick: function(){
                        editor.insertContent('[base_url]');
                    }
                }
            ]


        });

    });


})();