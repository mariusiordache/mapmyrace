{include file='web/includes/barebones-header.tpl'}
<script type="text/javascript">
    var tmpFolder = '7295281c7f7d163009dcb5';
</script>
<div id="timer" style="display:none; position:fixed; z-index:9999; top:30px; right: 10px; text-align: right">
    <div class="box">
        <span class="label">
            <span class="glyphicon glyphicon-time"></span>time
        </span>
        <span id="clock" class="value">00:00</span>
    </div>
    <div class="box">
        <span class="label">
            <span class="glyphicon glyphicon-road"></span>distance
        </span>
        <span id="tdistance" class="value"></span>
    </div>
    <div class="box">
        <span class="label">
            <span class="glyphicon glyphicon-time"></span>Gap
        </span>
        <span id="gap-time" class="value"></span>
    </div>
    <div class="box">
        <span class="label">
            <span class="glyphicon glyphicon-road"></span>Gap
        </span>
        <span id="gap-distance" class="value"></span>
    </div>
</div>
<div id="app">

    <div class="container">
        <h1>Welcome to My Race Map &trade;</h1>
        <p>My Race Map &trade; let you compare your traings or races with other friends. Just upload the .GPX file that you can export from  your Garmin Connect &TRADE; or RunKepper&TRADE; accounts.</p>

        <ul id="options">

        </ul>
        <div class="col-md-6">
            <div class="login-wrapper">
                <h2>Login</h2>
                <form action="" method="post" class="login-form">
                    <div class="form-group">
                        <input type="text" name="username" value="" placeholder="Username" class="form-control" />
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" value="" placeholder="Password" class="form-control" />
                    </div>
                    <div class="form-group">
                        <a href="javascript:" class="login-btn btn btn-default">Login</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-6">
            <div class="register-wrapper">
                <h2>Register</h2>
                <form method="POST" name="register-form" class="register-form">
                    <div class="form-group">
                        <input type="text" class="form-control" name="username" value="" placeholder="username" />
                    </div>     
                    <div class="form-group">
                        <input type="text" class="form-control" name="email" value="" placeholder="email" />
                    </div>     
                    <div class="form-group">
                        <input type="password" class="form-control" name="password" value="" placeholder="password" />
                    </div>        
                    <div class="form-group">
                        <a class="btn btn-default create-account">Create your account</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {*<div id="map-canvas">
    
    </div>*}
    <div id="play-controls" style="display: none;">

        <div class="pull-left" id="speed-controls">
            <a href="javascript:" data-speed="10">10X</a>
            <a href="javascript:" data-speed="20">20X</a>
            <a href="javascript:" data-speed="50">50X</a>
            <a href="javascript:" data-speed="100" class="active">100X</a>
            <a href="javascript:" data-speed="200">200X</a>
        </div>

        <div class="pull-right">
            <a href="javascript:" class="glyphicon glyphicon-backward"></a>
            <a href="javascript:" class="glyphicon glyphicon-play"></a>
            <a href="javascript:" class="glyphicon glyphicon-pause active"></a>
            <a href="javascript:" class="glyphicon glyphicon-forward"></a>


            <a href="javascript:" class="glyphicon glyphicon glyphicon-th menu"></a>
        </div>
    </div>