<html>
	<head>
		<!-- Page Title -->
		<title>Node Websocket Server</title>

		<!-- Socket.io needed for connection the node server -->
		<script src="../socket.io.js" type="text/javascript"></script>

		<!-- Include helper I wrote to make things easier -->
		<script src="../nwsdatahandler.js" type="text/javascript"></script>

		<!-- Using google font cause I cant stand looking at the default -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

		<!-- Load font awesome -->
		<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

		<!-- Using jQuery cause lazy -->
		<link href="https://fonts.googleapis.com/css?family=Oswald:400,300,700" rel="stylesheet" type="text/css">

		<!-- Style things up a little bit -->
		<link href="css.css" rel="stylesheet" type="text/css">

		<!-- so meta... -->
		<meta charset="utf-8">
		<meta name="description" content="This page is an example of a running node websocket server." />
		<meta name="keywords" content="Node, Websocket, Websockets, Node Websocket Server, Node js" />
		<meta name="author" content="Justin Stolpe" />
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />

		<!-- Include server defines.php file -->
		<?php include( 'defines.php' ); ?>
		
		<script>
			// url to connect to the node websocket server
			var websocketUrl = '<?php echo WEBSOCKET_URL; ?>';

			// make connection to the websocket server
			var socket = io( websocketUrl );

			// generate random guest user for the example
			var userKey = 'guest_' + Math.floor( Math.random() * 100000 );

			// create a name for the room that will be created on the node websocket server
			var roomName = 'active_users';
			
			$(function(){ // do stuff on page load
				// populate the user key and room name dom elements
				$('#user_key_container').html( userKey );
				$('#user_key').val( userKey );
				$('#room_name_container').html( roomName );

				$('#broadcast_button').on('click', function(){
					var firstName = $('#first_name').val();
					var lastName = $('#last_name').val();
					var message = $('#message').val();

					if ( !firstName || !lastName || !message ) { // really good client side validation
						alert( 'First name, last name, and message must be filled in!' );
					} else { // all good!
						$.ajax({ // ajax call posts form to broadcast.php which broadcasts the data to the node server
							url: 'broadcast.php',
							data: $('#broadcast_form').serialize(),
							dataType: 'json',
							type: 'post',
						});
					}
				});
			});

			/**
			 * Connet user to the room "active_users" on the node websocket server
			 */
			var nws = new nwsDataHandler({
				/**
				 * Socket connection to the node websocket server
				 */
				socket: socket,
				
				/**
				 * Unique user key for joining the room
				 */
				userKey: userKey,
				
				/**
				 * Name of the room we want the user joining
				 */
				roomName: roomName,
				
				/**
				 * Specify what happens when the node websocket server sends us new data
				 * on users joining/leaving the room
				 *
				 * @param object data from the node websocket server
				 *
				 * @return void
				 */
				nodeServerDataReceiver: function( data ) {console.log(data);
					// create html to display the data the node websocket server just sent us about the room
					var html = '<div class="output-row">' +
						'<div><span class="fa fa-user"></span> User Key: <b>' + data.action.userKey + '</b> ----- Action: <b>' + data.action.name + '</b> ----- Room: <b>' + data.room.name + '</b></div>' +
					'</div>';

					// append the html to the room updates container
					$('#room_output_container').append( html );

					// scroll to bottom to see newest
					$("#room_output_container").scrollTop( $("#room_output_container")[0].scrollHeight );

					// update the room user count
					$('#room_user_count').html( data.room.users.length );

					// clear the the list of users in the room
					$('#users_list_container').html( '' );

					for ( var i = 0; i < data.room.users.length; i++ ) { // loop over the new updated users list and display them
						$('#users_list_container').append('<div><span class="fa fa-user"></span> ' + data.room.users[i] + '</div>');
					}
				},

				/**
				 * Specify what happens when custom data is broadcast and received
				 * on the node websocket server
				 *
				 * @param object broadcast data from the node websocket server
				 *
				 * @return void
				 */
				customDataReceiver: function( data ) {console.log(data);
		  			// create html to display the data the node websocket server just sent us about the room
					var html = '<div class="output-row">' +
						'<div><b>' + data.user_key + '</b> <span class="fa fa-comment"></span> </div>' +
						'<div><b>' + data.first_name + ' ' + data.last_name + '</b>: ' + data.message + '</div>' +
					'</div>';

					// append the html to the room updates container
					$('#custom_output_container').append( html );

					// scroll to bottom to see newest
					$("#custom_output_container").scrollTop( $("#custom_output_container")[0].scrollHeight );
				}
			});
		</script>
	</head>
	<body>
		<div class="all-contents">
			<div class="all-sub-contents">
				<div class="header">
					<div class="header-title"><span class="fa fa-code"></span> Node Websocket Server</div>
					<div class="github-container">
						- This page runs on these two git repositores:<br />
						<span class="fa fa-github"></span> <a target="_blank" href="https://github.com/jstolpe/node_websocket_server">https://github.com/jstolpe/node_websocket_server</a><br />
						<span class="fa fa-github"></span> <a target="_blank" href="https://github.com/jstolpe/node_websocket_server_front">https://github.com/jstolpe/node_websocket_server_front</a>
					</div>
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
								<div class="container-heading"><span class="fa fa-server"></span> <b>nodeServerDataReceiver</b></div>
								<div id="room_output_container" class="output-container"></div>
							</div>
						</div>
						<div class="left-bottom">		
							<div class="paddings">
								<div class="container-heading"><span class="fa fa-users"></span> <b>Users List</b></div>
								<div id="users_list_container" class="output-container"></div>
							</div>
						</div>
					</div>
					<div class="main-right">
						<div class="right-contents-broadcast">		
							<div class="paddings">
								<div class="container-heading"><span class="fa fa-bullhorn"></span> <b>Broadcast to customDataReceiver</b></div>
								<div class="output-container-broadcast">
									<form id="broadcast_form" name="broadcast_form">
										<div class="input-row">
											<input type="text" id="first_name" name="first_name" placeholder="first name" />
											<input type="text" id="last_name" name="last_name" placeholder="last name" />
											<input type="hidden" id="user_key" name="user_key" value="" />
										</div>
										<div class="input-row">
											<textarea id="message" name="message" placeholder="broadcast message..."></textarea>
										</div>
									</form>
									<div class="broadcast-action">
										<div id="broadcast_button">
											<div class="paddings">Broadcast</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="right-contents">		
							<div class="paddings">
								<div class="container-heading"><span class="fa fa-server"></span> <b>customDataReceiver</b></div>
								<div id="custom_output_container" class="output-container-right"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>