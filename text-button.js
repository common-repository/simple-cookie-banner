(function() {
    tinymce.PluginManager.add('optout_tc_button', function( editor, url ) {
        editor.addButton( 'optout_tc_button', {
            text: 'Add OPT OUT',
            icon: false,
            onclick: function() {
                editor.windowManager.open( {
                    title: 'Add OPT OUT',
                    body: [{
                        type: 'textbox',
                        name: 'title',
                        label: 'Label text'
                    }],
                    onsubmit: function( e ) {
                        editor.insertContent( '[optout]' + e.data.title + '[/optout]');
                    }
                });
            }
        });
    });
})();