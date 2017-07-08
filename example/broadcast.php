<?php
	// url to connect to the node websocket server
	$websocketUrl = 'http://localhost:3000';

	// room name to broadcast data out to
	$roomName = 'active_users';

	// must match defines.js nodeServerHeaderKey in node_websocket_server repo
	$secretheaderkey = '12345';

	// this is the endpoint for broadcasting data to the node websocket server
	$broadcastUrl = $websocketUrl . '/broadcast';

	// this data will be broadcast out to all users in the room specified on the front end
	$broadcastData = array(
		'type' => 'custom', // required! this sends the data straight to the customDataReceiver function
		'room_name' => $roomName, // required! 
		'first_name' =>$_POST['first_name'],
		'last_name' => $_POST['last_name'],
		'message' => $_POST['message'],
		'user_key' => $_POST['user_key']
	);

	// encode data
	$data = json_encode( $broadcastData );

	// curl call to broadcast the data to the room on the websocket server
	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL, $broadcastUrl );
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
		'secretheaderkey: ' . $secretheaderkey,
        'Content-Type: application/json',
        'Content-Length: ' . strlen( $data ) ) );
    curl_setopt( $ch, CURLOPT_POST, 1 );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );
    $resp = curl_exec( $ch );
    curl_close( $ch );