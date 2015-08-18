var files, map, markers;
var cancelEvent = true;
var currentIndex = 0;
var currentSpeed = 100;

var avatars;
var pointsremoved;
var first = null, data;

$(document).ready(function() {

    $(window).bind('hashchange', function() {
        loadData();
    });

    $('.glyphicon-pause').click(function(e) {
        cancelEvent = true;
    });

    $('.glyphicon-play').click(function(e) {
        cancelEvent = false;
        showPoint();
    });

    $('.glyphicon-forward').click(function(e) {
        cancelEvent = true;
        currentIndex += 20;
        showPoint();
    });

    $('.glyphicon-backward').click(function(e) {
        cancelEvent = true;
        currentIndex -= 20;
        showPoint();
    });
    
    $('#speed-controls a').click(function(e) {
        currentSpeed = parseInt($(this).attr('data-speed'));
    });

    $('#play-controls a').click(function() {
        $(this).siblings('.active').removeClass('active');
        $(this).addClass('active');
    });

    $(document).bind('parseDone', function() {

        var mapOptions = {
            center: new google.maps.LatLng(-34.397, 150.644),
            zoom: 8
        };

        $('#loader').hide();
        $("#map-canvas").show();
        $('#play-controls').show();

        map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);

        var bounds = new google.maps.LatLngBounds();
        var traseu = new Array();

        $.each(data, function(i, points) {
            $.each(points, function(j, el) {
                if (!j) {
                    var p = new google.maps.LatLng(el.lat, el.lon);
                    traseu.push(p);
                    bounds.extend(p);
                }
            });
        });

        if (traseu.length > 0) {
            drawTraseu(traseu, map);
        }

        markers = new Array();

        for (i = 0; i < data[0].length; i++) {
            markers[i] = new google.maps.Marker({
                position: new google.maps.LatLng(data[0][i].lat, data[0][i].lon),
                map: map,
                title: avatars[i],
                icon: avatars[i]
            });
        }

        $('#timer').show();
        map.fitBounds(bounds);

        cancelEvent = true;

        showPoint();
    });

    loadData();
});

function drawTraseu(traseu, map) {
    var poly = new google.maps.Polyline({
        path: traseu,
        geodesic: true,
        strokeColor: "#FF0000",
        strokeOpacity: 0.7,
        strokeWeight: 2
    });

    poly.setMap(map);
    traseu = new Array();
}

function loadData() {
    $('#loader').show();
    $('#timer').hide();
    $('#options').hide();
    $('#map-canvas').hide();
    cancelEvent = true;


    $.get('/dashboard/get_compare_data', {course_ids: FILE_IDS}, function(resp) {
        avatars = resp.avatars;
        data = resp.points;
        currentIndex = 0;
        $(document).trigger('parseDone');

    }, 'json');
}

function comparedistance(a, b) {
    if (a.d < b.d)
        return 1;
    if (a.d > b.d)
        return -1;
    return 0;
}

function showPoint() {
    var p = data[currentIndex++];

    if (p !== undefined) {

        var dist = new Array();

        $.each(p, function(i, el) {
            var pnt = new google.maps.LatLng(el.lat, el.lon);

            if (markers[i].getPosition() !== pnt) {
                markers[i].setPosition(pnt);
            }

            dist.push(new Object({index: i, d: el.d}));
        });

        if (dist.length > 0) {
            dist.sort(comparedistance);
            var $gapd = $('#gap-distance').html('');
            var $gapt = $('#gap-time').html('');

            for (var i = 1; i < dist.length; i++) {
                var j = dist[i].index;
                $gapd.append($('<span>').css('clear', 'left').text((p[j].g.d / 1000).toFixed(2) + ' km'));
                $gapt.append($('<span>').css('clear', 'left').text(moment.utc(p[j].g.t * 1000).format("HH:mm:ss")));
            }

            first = p[dist[0].index];

            $('#tdistance').text((first.d / 1000).toFixed(2) + " km");
            $('#clock').text(moment.utc(p[0].t * 1000).format("HH:mm:ss"));
        }
    }

    if (data.length > currentIndex) {

        if (cancelEvent !== true) {
            setTimeout(function() {
                showPoint();
            }, 1000 / currentSpeed);
        }
    }
}
