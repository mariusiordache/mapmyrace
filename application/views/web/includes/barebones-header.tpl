<!DOCTYPE html>
<html>
    <head>

        <script src="/bower_components/requirejs/require.js"></script>

        {$conditionalCSS}
        {$css}
        {$less}
        {$conditionalJS}

        {$external_js_links|default:''}
        <script type="text/javascript">
            require([
                "/assets/includes.js"
            ], function () {
                require([{$dependencies_js}], function () {
                    require([
                        {$require_js_files}
                    ])
                })
            })
        </script>

        <script type="text/javascript">
            {if isset($js_page_data)}
            PAGE_DATA = {$js_page_data|json_encode};
            {/if}
            {if isset($user)}
            USER_DATA = {$user|json_encode};
            {/if}
        </script>

        {literal}
            <script>
                (function (i, s, o, g, r, a, m) {
                    i['GoogleAnalyticsObject'] = r;
                    i[r] = i[r] || function () {
                        (i[r].q = i[r].q || []).push(arguments)
                    }, i[r].l = 1 * new Date();
                    a = s.createElement(o),
                            m = s.getElementsByTagName(o)[0];
                    a.async = 1;
                    a.src = g;
                    m.parentNode.insertBefore(a, m)
                })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

                ga('create', 'UA-40125707-2', 'androidmakeup.com');
                ga('send', 'pageview');
            </script>
        {/literal}

        
        {$meta}
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body> 
        
        {if isset($is_remote_db)}
            <div id="topwarning">On remote database: {$is_remote_db}</div>
        {/if}
