(function ($) {
    // Cookie functions, adapted from http://w3schools.com/js/js_cookies.asp
    function setCookie(key, value, lifetime_in_days) {
        var value = escape(value),
            expires;
        if (lifetime_in_days !== null) {
            expires = new Date();
            expires.setDate(expires.getDate() + lifetime_in_days);            
            value += '; expires=' + expires.toUTCString();
        }
        document.cookie = key + '=' + value;
    }
    function getCookie(key) {
        var value = document.cookie;
        var left  = value.indexOf(' ' + key + '=');
        if (left == -1) {
            left = value.indexOf(key + '=');
        }
        if (left == -1) {
            return null;
        }
        left = value.indexOf('=', left) + 1;
        var right = value.indexOf(';', left);
        if (right == -1) {
            right = value.length;
        }
        return unescape(value.substring(left, right));
    }

    var editor = false;

    $(document).on('change keyup', '#theme_chooser', function (event) {
        var theme = $(this).val();
        if (editor) {
            editor.setOption('theme', theme);
        }
        setCookie('customcss-theme', theme, 365);
    }).on('click', '#share_via_blubber', function () {
        var content = $('<div>').addClass('customcss-share').text('Jetzt Ihr CSS im globalen Blubberstream veröffentlichen?'),
            dialog  = $('<div>').append(content);
         dialog.dialog({
            title: 'CSS teilen',
            resizable: false,
            height: 'auto',
            modal: true,
            buttons: {
                'Teilen': function () {
                    $.ajax({
                        url: STUDIP.URLHelper.getURL('plugins.php/customcss/share'),
                        type: 'post',
                        dataType: 'json',
                        success: function () {
                            content.text('Ihr CSS steht jetzt im globalen Blubberstream.');
                            content.addClass('customcss-share-shared')

                            dialog.dialog('option', 'buttons', {
                                'Schliessen': function () {
                                    dialog.dialog('close');
                                }
                            });
                        }
                    });
                },
                'Abbrechen': function () {
                    $(this).dialog('close');
                }
            }
        });
        return false;
    }).ready(function () {
        var element = $('#customcss-editor'),
            mode    = element.data().mode || 'less';
        editor = CodeMirror.fromTextArea(element[0], {
            mode: mode,
            theme: getCookie('customcss-theme') || 'default',
            lineNumbers: true,
            styleActiveLine: true,
            matchBrackets: true,
            indentUnit: 4,
            lineWrapping: true
        });
        
        $('.CodeMirror').resizable({
          resize: function() {
            editor.setSize($(this).width(), $(this).height());
          }
        });        
    });
    
}(jQuery));
