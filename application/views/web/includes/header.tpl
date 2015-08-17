<header id="top" >
    <div class="col-md-3 col-xs-3 col-sm-3">

    </div>
    <div class="col-md-6 col-xs-6 col-sm-6 col-lg-6" >
        <ul class="list-unstyled main-menu">
            <li {if $smarty.server.REQUEST_URI == "/dashboard"}class="active"{/if}><a href="/dashboard">Courses</a></li>
            <li {if $smarty.server.REQUEST_URI == "/dashboard/friends"}class="active"{/if}><a href="/dashboard/friends">Friends</a></li>
            <li {if $smarty.server.REQUEST_URI == "/account/profile"}class="active"{/if}><a href="/account/profile">Profile</a></li>
        </ul>
    </div>
    <div class="col-md-3 col-xs-3 col-sm-3 col-lg-3 ">
        <a class="logout pull-right" href="/user/logout" title="Log out">
            <img src="/assets/images/logout.svg">
        </a>
    </div>
    <div class="clear"></div>
</header>