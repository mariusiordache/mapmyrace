<header id="top" >
    <div class="col-md-3 col-xs-3 col-sm-3 logo">
        RaceProgress.com
    </div>
    <div class="col-md-6 col-xs-6 col-sm-6 col-lg-6" >
        {if !empty($user) && $user.id}
        <ul class="list-unstyled main-menu">
            <li {if $smarty.server.REQUEST_URI == "/dashboard"}class="active"{/if}><a href="/dashboard">Trasee</a></li>
            <li {if strstr($smarty.server.REQUEST_URI, "events")}class="active"{/if}><a href="/dashboard/events">Evenimente</a></li>
            <li {if $smarty.server.REQUEST_URI == "/dashboard/friends"}class="active"{/if}>
                <a href="/dashboard/friends">Prieteni  <span class="counter" id="pending-request-counter" {if !$pending_requests_count}style="display:none"{/if}>{$pending_requests_count}</span></a>
            </li>
            <li {if $smarty.server.REQUEST_URI == "/account/profile"}class="active"{/if}><a href="/account/profile">Profil</a></li>
        </ul>
        {/if}
    </div>
    <div class="col-md-3 col-xs-3 col-sm-3 col-lg-3 ">
        {if !empty($user) && $user.id}
        <a class="user-image-wrapper pull-left" href="/account/profile" title="">
            <div class="user-image pull-right">
                <img src="{$user.profile_pic_url_thumb}">
            </div>
            <div class="user-name pull-right" style="color:white;">
                Hi {$user.username}
            </div>
        </a>
        <a class="logout pull-right" href="/user/logout" title="Log out">
            <img src="/assets/images/logout.svg">
        </a>
        {/if}
    </div>
    <div class="clear"></div>
</header>