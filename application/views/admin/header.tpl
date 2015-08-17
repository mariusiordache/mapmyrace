<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
        <title>{$config.project.name}</title>
        {$css}
        <!--[if IE]> <link href="css/ie.css" rel="stylesheet" type="text/css"> <![endif]-->
        {$js}

        <script type="text/javascript">
            {if isset($js_page_data)}
                PAGE_DATA = {$js_page_data|json_encode};
            {/if}
            {if isset($user)}
                USER_DATA = {$user|json_encode};
            {/if}
        </script>

    </head>

    <body {if isset($window)}data-window="{$window}"{/if}>

        {if !isset($window) || $window!='popup'}
            <!-- Top line begins -->
            <div id="top">
                <div class="wrapper">
                    <a href="/dashboard" title="" class="logo"><img src="{$config.assets.img_url}/aquincum/images/logo.png" alt="" /></a>

                    <!-- Right top nav -->
                    <div class="topNav">
                        <ul class="userNav">
                            <li><a title="" class="search"></a></li>
                            <li><a href="#" title="" class="screen"></a></li>
                            <li><a href="#" title="" class="settings"></a></li>
                            <li><a href="#" title="" class="logout"></a></li>
                            <li class="showTabletP"><a href="#" title="" class="sidebar"></a></li>
                        </ul>
                        <a title="" class="iButton"></a>
                        <a title="" class="iTop"></a>
                        <div class="topSearch">
                            <div class="topDropArrow"></div>
                            <form action="">
                                <input type="text" placeholder="search..." name="topSearch" />
                                <input type="submit" value="" />
                            </form>
                        </div>
                    </div>

                    <div class="clear"></div>
                </div>
            </div>
            <!-- Top line ends -->


            <!-- Sidebar begins -->
            <div id="sidebar">
                <div class="mainNav">
                    <div class="user">
                        <a title="" class="leftUserDrop"><img src="{$config.assets.img_url}/aquincum/images/user.png" alt="" /><span><strong>3</strong></span></a><span>Eugene</span>
                        <ul class="leftUser">
                            <li><a href="#" title="" class="sProfile">My profile</a></li>
                            <li><a href="#" title="" class="sMessages">Messages</a></li>
                            <li><a href="#" title="" class="sSettings">Settings</a></li>
                            <li><a href="#" title="" class="sLogout">Logout</a></li>
                        </ul>
                    </div>

                    <!-- Main nav -->
                    <ul class="nav">
                        <li><a href="{$config.admin_url}/dashboard" title="" {if $current_menu==""}class="active"{/if}><img src="{$config.assets.img_url}/aquincum/images/icons/mainnav/dashboard.png" alt="" /><span>Dashboard</span></a></li>
                        <li><a href="{$config.admin_url}/content" title="" {if $current_menu=="content"}class="active"{/if}><img src="{$config.assets.img_url}/aquincum/images/icons/color/archives.png" alt="" /><span>Content</span></a>
                            <ul>
                                <li><a href="{$config.admin_url}/deals" title=""><span class="icol-list"></span>Deals</a></li>
                                <li><a href="{$config.admin_url}/tags" title=""><span class="icol-tag"></span>Tags</a></li>
                                <li><a href="{$config.admin_url}/photos" title=""><span class="icol-photos"></span>Photos</a></li>
                            </ul>
                        </li>
                        <li><a href="forms.html" title=""><img src="{$config.assets.img_url}/aquincum/images/icons/mainnav/forms.png" alt="" /><span>Forms stuff</span></a>
                            <ul>
                                <li><a href="forms.html" title=""><span class="icol-list"></span>Inputs &amp; elements</a></li>
                                <li><a href="form_validation.html" title=""><span class="icol-alert"></span>Validation</a></li>
                                <li><a href="form_editor.html" title=""><span class="icol-pencil"></span>File uploader &amp; WYSIWYG</a></li>
                                <li><a href="form_wizards.html" title=""><span class="icol-signpost"></span>Form wizards</a></li>
                            </ul>
                        </li>
                        <li><a href="messages.html" title=""><img src="{$config.assets.img_url}/aquincum/images/icons/mainnav/messages.png" alt="" /><span>Messages</span></a></li>
                        <li><a href="statistics.html" title=""><img src="{$config.assets.img_url}/aquincum/images/icons/mainnav/statistics.png" alt="" /><span>Statistics</span></a></li>
                        <li><a href="tables.html" title=""><img src="{$config.assets.img_url}/aquincum/images/icons/mainnav/tables.png" alt="" /><span>Tables</span></a>
                            <ul>
                                <li><a href="tables.html" title=""><span class="icol-frames"></span>Standard tables</a></li>
                                <li><a href="tables_dynamic.html" title=""><span class="icol-refresh"></span>Dynamic table</a></li>
                                <li><a href="tables_control.html" title=""><span class="icol-bullseye"></span>Tables with control</a></li>
                                <li><a href="tables_sortable.html" title=""><span class="icol-transfer"></span>Sortable and resizable</a></li>
                            </ul>
                        </li>
                        <li><a href="other_calendar.html" title=""><img src="{$config.assets.img_url}/aquincum/images/icons/mainnav/other.png" alt="" /><span>Other pages</span></a>
                            <ul>
                                <li><a href="other_calendar.html" title=""><span class="icol-dcalendar"></span>Calendar</a></li>
                                <li><a href="other_gallery.html" title=""><span class="icol-images2"></span>Images gallery</a></li>
                                <li><a href="other_file_manager.html" title=""><span class="icol-files"></span>File manager</a></li>
                                <li><a href="#" title="" class="exp"><span class="icol-alert"></span>Error pages <span class="dataNumRed">6</span></a>
                                    <ul>
                                        <li><a href="other_403.html" title="">403 error</a></li>
                                        <li><a href="other_404.html" title="">404 error</a></li>
                                        <li><a href="other_405.html" title="">405 error</a></li>
                                        <li><a href="other_500.html" title="">500 error</a></li>
                                        <li><a href="other_503.html" title="">503 error</a></li>
                                        <li><a href="other_offline.html" title="">Website is offline error</a></li>
                                    </ul>
                                </li>
                                <li><a href="other_typography.html" title=""><span class="icol-create"></span>Typography</a></li>
                                <li><a href="other_invoice.html" title=""><span class="icol-money2"></span>Invoice template</a></li>
                            </ul>
                        </li>
                    </ul>
                </div> 
            {/if}	