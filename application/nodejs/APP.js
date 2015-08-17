//node APP.js http_ip=127.0.0.1 http_port=8080  udp_ip=127.0.0.1 upd_port=7659
var options = new Object(),
        Gearman = require('gearman2'),
        client = Gearman.createClient(4730, 'localhost');

process.argv.forEach(function(val, index, array) {
    if (val.indexOf('=') != -1) {
        var arr = val.split('=');
        options[arr[0]] = arr[1];
    }
});

function log(msg) {
    console.log(new Date().toISOString() + " " + msg);
}

function runServiceComplete(socket, data, resp) {
    var token = 'runservicecomplete';
    
    if (typeof(data.service) !== "undefined" && typeof(data.service) === "string") {
        token += '-' + data.service;
    }
    
    socket.emit(token, resp);
}

function runService(socket, data) {
    if (typeof(data.service) === "undefined" || typeof(data.service) !== "string") {
        return runServiceComplete(socket, data, {
            'success': false,
            'error': 'service must be specified as a string'
        });
    }
    
    log("run Service " + data.service)
    
    var job = client.submitJob(data.service, JSON.stringify(data.data), {
        background: false,
        encoding: 'UTF-8'
    }).on("complete", function(jobdata) {
        var reply = JSON.parse(jobdata);
        
        log(jobdata);
        
        return runServiceComplete(socket, data, reply);
    });
}

if (options.ip !== undefined || options.port !== undefined) {

    var app;

    app = require('http').createServer(function(req, res) {
        res.writeHead(200, {'Content-Type': 'text/plain'});
        res.end('Hello World\n');
    });

    var io = require('socket.io').listen(app) // http://socket.io/#home
            , udpSocket = require('dgram').createSocket('udp4')

    io.sockets.on('forceDisconnect', function(socket) {
        log("Client disconected");
        socket.disconnect();
    });

    io.sockets.on('connection', function(socket) {

        socket.on('runservice', function(data) {
            runService(socket, data);
        });
        
        socket.on('register_channel', function(data) {
            if (typeof(data.channel) === "string") {
                socket.join(data.channel);
                log ("Client connected to " + data.channel);
            }
        });
        
        socket.on('register_notify', function(data) {
            log ("Got client " + data.id + ":" + data.password);
            socket.join(data.id + ":" + data.password);
        });

    });

    app.listen(options.port, options.ip);
    
    udpSocket.on('message', function(content, rinfo) {
        var buf = new Buffer(content);
        var obj = JSON.parse(buf.toString('utf-8'));
        
        if (obj.user_id && obj.password) {
            log("Got UDP message for " + obj.user_id+":"+obj.password);
            io.sockets.in(obj.user_id+":"+obj.password).emit("notify", obj);
        } else if (obj.channel && obj.message) {
            log("Got UDP message for channel " + obj.channel + " - " + JSON.stringify(obj.message));
            io.to(obj.channel).emit(obj.channel, obj.message);
        }
    });
    
    udpSocket.bind(7659);
    
} else {
    console.log('You should specify --ip and --port')
}


