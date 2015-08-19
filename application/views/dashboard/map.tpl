{include file='web/includes/barebones-header.tpl'}

<script type="text/javascript">
    var FILE_IDS = "{$smarty.get.course_ids}";
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

<div id="loader" style="position:fixed; top:30%; left: 0; right: 0; bottom: 0;">
    <div style="margin:auto; width:200px; height: 100px; ">
        <center>
            <img src="/assets/images/loader.gif" /><br />
            <h1 style="color:#2B97D6; font-weight: normal;">Loading data ...</h1>
        </center>
    </div>
</div>

<ul id="options">

</ul>

<div id="map-canvas">

</div>

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


        <a href="/dashboard" class="glyphicon glyphicon glyphicon-th menu"></a>
    </div>
</div>

        <div style="clear: both"></div>
        
    </body>
</html>