( function() {
    tinymce.PluginManager.add( 'ucf_com_brightcove', function( editor, url ) {

        // Add a button that opens a window
        editor.addButton( 'ucf_com_brightcove_key', {

            text: 'Brightcove',
            icon: 'dashicons-media-video', // video icon
            onclick: function() {
                // Open window
                editor.windowManager.open( {
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
                    	},
                    ],
                    onsubmit: function( e ) {
                        // Insert content when the window form is submitted
                        editor.insertContent( '[brightcove id=' + e.data.id 
                        		+ ((e.data.width) ? ' width=' + e.data.width : '')
                        		+ ((e.data.height) ? ' height=' + e.data.height : '')
                        		+ ']');
                    }

                } );
            }

        } );

    } );

} )();