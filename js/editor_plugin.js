        (function() {
    tinymce.PluginManager.add('custom_mce_button', function(editor, url) {
        editor.addButton('custom_mce_button', {
            text: false,
              icon: 'insertdatetime',
              tooltip: "Insert Time",
            onclick: function() {
                editor.windowManager.open({
                    title: 'Subtitle',
                    body: [{
                        type: 'textbox',
                        name: 'subegins',
                        label: 'Begins:',
                        value: ''
                    }, {
                        type: 'textbox',
                        name: 'subends:',
                        label: 'Ends',
                        value: ''
                        
                    }, ],
                    onsubmit: function(e) {
                        editor.insertContent(
                            '[sub data-begin="' +
                            e.data.subegins +
                            '" data-end="' +
                            e.data.subends +
                            '"]' +
                            editor.selection
                            .getContent() +
                            '[/sub]'
                        );
                    }
                });
            }
        });
    });
})();
