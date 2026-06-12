CKEDITOR.plugins.add('phpcode', {
    icons: 'phpcode',
    init: function(editor) {
        editor.addCommand('insertPHPCode', {
            exec: function(editor) {
                var phpCode = prompt('Please enter your PHP code', '<?php echo "Hello, World!"; ?>');
                if (phpCode) {
                    editor.insertHtml('<pre><code class="language-php">' + CKEDITOR.tools.htmlEncode(phpCode) + '</code></pre>');
                }
            }
        });

        editor.ui.addButton('PHPCode', {
            label: 'Insert PHP Code',
            command: 'insertPHPCode',
            toolbar: 'insert'
        });
    }
});