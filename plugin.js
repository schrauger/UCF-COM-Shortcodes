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
                    title: 'Staff Listing',
                    text: 'Staff',
                    icon: 'icon dashicons-admin-users', // user silhouette icon
                    onclick: function () {
                        // Open window
                        editor.windowManager.open({
                            title: 'Staff Listing',
                            body: [
                                {
                                    type: 'textbox',
                                    name: 'category',
                                    label: 'Staff Category. If unspecified, all profiles are shown.'
                                },
                                {
                                    type: 'textbox',
                                    name: 'hide_photo',
                                    label: 'Hide Staff Photos. Leave blank to show photos.'
                                }
                            ],
                            onsubmit: function (e) {
                                // Insert content when the window form is submitted
                                editor.insertContent('[staff ' +
                                    ((e.data.category) ? ' category="' + e.data.category + '"' : '') +
                                    ((e.data.hide_photo) ? ' hide_photo="' + e.data.hide_photo + '"' : '') +
                                    ']');
                            }

                        });
                    }
                },
                {
                    title: 'Newsfeed Listings',
                    text: 'Newsfeed',
                    icon: 'icon dashicons-welcome-widgets-menus', // kind of newspaper icon
                    onclick: function () {
                        // Open window
                        editor.windowManager.open({
                            title: 'Newsfeed Listings',
                            body: [
                                {
                                    type: 'textbox',
                                    name: 'category',
                                    label: 'News Category. If unspecified, all articles are shown.'
                                },
                                {
                                    type: 'textbox',
                                    name: 'hide_news',
                                    label: 'Hide news listing. Useful if you only want the slider.'
                                },
                                {
                                    type: 'textbox',
                                    name: 'show_slider',
                                    label: 'Show slider. If unspecified, news image slider will not be included.'
                                },
                                {
                                    type: 'textbox',
                                    name: 'news_count',
                                    label: 'How many articles to include in news listing. If unspecified, all articles are shown  (unless a global default is defined).'
                                },
                                {
                                    type: 'textbox',
                                    name: 'slider_count',
                                    label: 'How many articles to include in slider. If unspecified, it will be equal to news_count.'
                                },
                                {
                                    type: 'textbox',
                                    name: 'blog',
                                    label: 'Slug or numeric id of the blog from which to pull news. If unspecified, it will pull from current blog.'
                                }
                            ],
                            onsubmit: function (e) {
                                // Insert content when the window form is submitted
                                editor.insertContent('[newsfeed ' +
                                ((e.data.category) ? ' category="' + e.data.category + '"' : '') +
                                ((e.data.hide_news) ? ' hide_news="' + e.data.hide_news + '"' : '') +
                                ((e.data.show_slider) ? ' show_slider="' + e.data.show_slider + '"' : '') +
                                ((e.data.news_count) ? ' news_count="' + e.data.news_count + '"' : '') +
                                ((e.data.slider_count) ? ' slider_count="' + e.data.slider_count + '"' : '') +
                                ((e.data.blog) ? ' blog="' + e.data.blog + '"' : '') +
                                ']');
                            }

                        });
                    }
                },
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