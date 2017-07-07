<html>
	<head>
		<!-- Page Title -->
		<title>Node Websocket Server Front End Example</title>

		<!-- Socket.io needed for connection the node server -->
		<script src="js/socket.io.js" type="text/javascript"></script>

		<!-- Include helper I wrote to make things easier -->
		<script src="js/nwsdatahandler.js" type="text/javascript"></script>

		<!-- Using google font cause I cant stand looking at the default -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

		<!-- Using jQuery cause lazy -->
		<link href="http://fonts.googleapis.com/css?family=Oswald:400,300,700" rel="stylesheet" type="text/css">

		<!-- Style things up a little bit -->
		<link href="css/site.css" rel="stylesheet" type="text/css">
		
		<script>
			// generate random guest user for the example
			var userKey = 'guest_' + Math.floor( Math.random() * 100000 );

			// create a name for our room that will be created on the node websocket server
			var roomName = 'active_users';
			
			$(function(){ // do stuff on page load
				// populate the user key and room name dom elements
				$('#user_key_container').html( userKey );
				$('#room_name_container').html( roomName );
			});

			/**
			 * Connet user to our room "active_users" on the node websocket server
			 */
			var nws = new nwsDataHandler({
				/**
				 * Url with the port our node websocket server is running on
				 */
				websocketUrl: 'http://localhost:3000',
				
				/**
				 * Unique user key for joining the room
				 */
				userKey: userKey,
				
				/**
				 * Name of the room we want the user joining
				 */
				roomName: roomName,
				
				/**
				 * Specify what happens when our node websocket server sends us new data
				 * on users joining/leaving the room
				 *
				 * @param object data from the node websocket server
				 *
				 * @return void
				 */
				nodeServerDataReceiver: function( data ) {
					// create html to display the data the node websocket server just sent us about the room
					var html = '<div style="margin-bottom:3px;margin-top:3px;border-bottom:1px solid #e3e3e3;padding-bottom:3px">'+
						'<div>User Key: <b>' + data.action.userKey + '</b> ----- Action: <b>' + data.action.name + '</b> ----- Room: <b>' + data.room.name + '</b></div>' +
					'</div>';

					// append the html to our room updates container
					$('#room_output_container').append( html );

					// update the room user count
					$('#room_user_count').html( data.room.users.length );

					// clear our the list of users in the room
					$('#users_list_container').html( '' );

					for ( var i = 0; i < data.room.users.length; i++ ) { // loop over the new updated users list and display them
						$('#users_list_container').append('<div>' + data.room.users[i] + '</div>');
					}
				},
			});
		</script>
	</head>
	<body>
		<div class="header">
			<div class="header-title">Node Websocket Server Front End Example</div>
			<div class="header-sub-title">
				You are online as <span id="user_key_container"></span> 
				in room <span id="room_name_container">active_users</span>.
				There are <span id="room_user_count">0</span> users in the room.
			</div>
		</div>
		<div class="main-content">
			<div class="main-left">
				<div class="left-top">		
					<div class="paddings">
						<div class="container-heading"><b>nodeServerDataReceiver</b></div>
						<div id="room_output_container" class="output-container"></div>
					</div>
				</div>
				<div class="left-bottom">		
					<div class="paddings">
						<div class="container-heading"><b>Users List</b></div>
						<div id="users_list_container" class="output-container"></div>
					</div>
				</div>
			</div>
			<div class="main-right">
				<div class="right-contents">		
					<div class="paddings">
						<div class="container-heading">customDataReceiver</div>
						<div class="output-container-right"></div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>