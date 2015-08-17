{if isset($breadcrumbs) && count($breadcrumbs) > 0}
    <ol class="breadcrumb" style="background:none;">
        {foreach from=$breadcrumbs item=breadcrumb}
            {if $breadcrumb.url && $breadcrumb.url != $smarty.server.REQUEST_URI}
                <li><a href="{$breadcrumb.url}">{$breadcrumb.label}</a></li>
            {else}
                <li class="active">{$breadcrumb.label}</li>
            {/if}
        {/foreach}
    </ol>
{/if}