var fs = require('fs');

var  key = fs.readFileSync('./ssl/privkey.pem');
var cert = fs.readFileSync('./ssl/cert.pem');


var app = require('express')();
var http = require('https').Server({
    key : key,
    cert : cert
}, app);
var io = require('socket.io')(http);

// presentation 
var nsp = io.of('/pr');
nsp.on('connection', function (socket) {
	console.log(socket.id + ' user connected');

	var room_id;
	socket.on('join room', function (id) {
		room_id = id; 
		socket.join(room_id);
		console.log('join room list', io.sockets.adapter.rooms);
	});

	socket.on('leave room', function () {
		socket.leave(room_id);
		console.log('out room list', io.sockets.adapter.rooms);
	});

	socket.on('disconnect', function () {
		console.log(socket.id + ' disconnected');
	});

	socket.on("message", function(data){
		if (data.all)	{
	 	    nsp.in(room_id).emit('message', data);
		} else {
	 	    socket.broadcast.to(room_id).emit('message', data);
		}
	 });
});	

http.listen(3000, function(){
	  console.log('listening on *:3000');
});
