<html>
	<head>
		<script src="socket.io.js" type="text/javascript"></script>
		<script src="nwsdatahandler.js" type="text/javascript"></script>
		<script>
			var nws = new nwsDataHandler({
				websocketUrl: 'http://localhost:3000',
				userKey: 'guest_' + Math.floor( Math.random() * 100000 ),
				roomName: 'active_users',
				nodeServerDataReceiver: function( data ) {
					console.log('------Data received from node websocket server1');
					console.log( data );
				},
			});
		</script>
	</head>
	<body>
		<h1>Node Websocket Server Front</h1>
	</body>
</html>