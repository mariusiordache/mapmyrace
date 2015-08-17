{include file='web/includes/barebones-header.tpl'}
<header id="top" class="bootstro" data-bootstro-placement="bottom" data-bootstro-content="This is where you start: the header contains buttons to your Dashboard (the active page you are now on), to Create Themes (start work on a theme) and to My Themes (pick one of your draft themes to continue work on).
" >
    <div class="col-md-3 col-xs-3 col-sm-3">
        {$page = 'Dashboard' }
        {include file="web/dashboardV2/structure/header-top-left.tpl"}
    </div>
    <div class="col-md-6 col-xs-6 col-sm-6 col-lg-6" >
{*        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="menu-toggler"></a>
        <!-- END RESPONSIVE MENU TOGGLER      nav navbar-nav navbar-right    -->
        <ul class="nav navbar-nav">
            {foreach from=$topnav item=item}
                <li {if $item.active}class="active"{/if}>
                    <a href="{$item.url}">{$item.label}</a>

                    {if (!empty($item.submenu))}
                        <ul class="dropdown-menu">
                            <li>
                                <div class="mega-menu-content">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <ul class="mega-menu-submenu">
                                                {foreach from=$item.submenu item=subitem}
                                                    <a href="{$subitem.url}">{$subitem.label}</a>
                                                {/foreach}
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    {/if}
                </li>
            {/foreach}
        </ul>*}
    </div>
    <div class="col-md-3 col-xs-3 col-sm-3 col-lg-3 ">
        {include file="web/dashboardV2/structure/header-top-right.tpl"}
    </div>
    <a href="javascript:" class="btn-create-theme">
        <img src="/assets/dashboardv2/plus.svg" title="create theme" alt="create theme">
    </a>
    <div class="clear"></div>
</header>