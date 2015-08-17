var files, map, markers;
var cancelEvent = true;
var currentIndex = 0;
var currentSpeed = 100;

var avatars;
var pointsremoved;
var first = null, data;

var options = new Object({
    tulcea2013: {
        name: "Tulcea 2013",
        files: ["alex_tulcea2013.gpx", "tulcea_marius_2013.gpx"]
    },
    olimp2013: {
        name: "Olimp 2013",
        files: ["olimp_marius_2013.gpx", "alex_olimp2013.gpx"]
    },
    primaevadare2014: {
        name: "Prima evadare 2014",
        files: ["primaevadare_alex.gpx", "primaevadare_marius.gpx"]
    },
    mogosoaia2014: {
        name: "Mogosoaia 2014",
        files: ["marius_mogosoaia2014.gpx", "alex_mogosoaia2014.gpx"]
    },
    faraasfalt2014: {
        name: "Fara asfalt 2014",
        files: ["alex_faraasfalt2014.gpx", "marius_faraasfalt2014.gpx", "marius_fara_asfalt2013.gpx"]
    },
    marius: {
        name: "Marius Herestrau",
        files: ["herestrau/activity_358098866.gpx", "herestrau/activity_358098883.gpx", "herestrau/activity_519353079.gpx"]
    }
});

$(document).ready(function() {

//    $(function() {
//        $(':file').uploadifive({
//            'auto': true,
//            'queueID': 'queue',
//            'uploadScript': 'upload.php',
//            formData:{
//                'tmpFolder': tmpFolder
//            },
//            onQueueComplete: function(uploads) {
//                
//            },
//            onUploadComplete: function(file, data) {
//                files.push(file.name);
//            },
//            onRemoveQueueItem: function(file) {
//                files = jQuery.grep(files, function(value) {
//                    return value != file.name;
//                });
//            }
//        });
//    });

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

    $('.glyphicon.menu').click(function(e) {
        cancelEvent = true;
        showOptions();
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
                icon: 'img/' + avatars[i] + ".png"
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

    files = new Array();
    var hash = window.location.hash.substr(1);

    if (options[hash] !== undefined) {
        files = options[hash].files;
    } else {
        return showOptions();
    }

    $.get('data.php', {files: files}, function(resp) {
        avatars = new Array();
        data = new Array();

        $.each(resp.files, function(i, el) {
            if (el.indexOf('alex') > -1) {
                avatars[i] = 'alex';
            } else {
                avatars[i] = 'marius';
            }
        });

        data = resp.points;
        currentIndex = 0;
        $(document).trigger('parseDone');

    }, 'json');
}

function showOptions() {
    $ul = $('#options');
    $('#loader').hide();
    $('#map-canvas').hide();
    $('#timer').hide();
    $('#play-controls').hide();

    if (!$ul.children().length && 0) {
        $.each(options, function(i, option) {
            $ul.append(
                    $('<li>').append(
                    $('<a>')
                    .attr('href', '#' + i)
                    .text(option.name)
                    )
                    );
        });
    }

    $('#options').show();
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