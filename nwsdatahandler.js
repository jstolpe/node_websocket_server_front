/**
 * Setup and connect a user to a room on the node websocket server.
 *
 * @version 1.0
 * @author Justin Stolpe
 * @source https://github.com/jstolpe/node_websocket_server_front
 * @param Object args 
 *		{
 *			socket: socketConnectionToTheNodeWebsocketServer,
 *			userKey: 'uniqueUserId',
 *			roomName: 'nameOfRoomUserIsJoining',
 *			nodeServerDataReceiver: function( data ) { 
 *				// do stuff with the data when node websocket server broadcasts data to the room
 *			},
 *			customDataReceiver: function( data ) { 
 *				// do stuff when custom data is broadcast to the node websocket server
 *			}
 *		}
 * @license 
 *		Copyright (c) 2017 Justin Stolpe
 *
 *		Permission is hereby granted, free of charge, to any person obtaining a copy
 *		of this software and associated documentation files (the "Software"), to deal
 *		in the Software without restriction, including without limitation the rights
 *		to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 *		copies of the Software, and to permit persons to whom the Software is
 *		furnished to do so, subject to the following conditions:
 *
 *		The above copyright notice and this permission notice shall be included in all
 *		copies or substantial portions of the Software.
 *
 *		THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *		IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *		FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 *		AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *		LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *		OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 *		SOFTWARE.
 *
 * @return nwsDataHandler
 */
var nwsDataHandler = (function( args ) {
	/**
	 * Constructor which connects a user to a room on the node websocket server.
	 *
	 * @param Object args 
	 *		{
	 *			socket: socketConnectionToTheNodeWebsocketServer,
	 *			userKey: 'uniqueUserId',
	 *			roomName: 'nameOfRoomUserIsJoining',
	 *			nodeServerDataReceiver: function( data ) { 
	 *				// do stuff when node websocket server broadcasts data
	 *			},
	 *			customDataReceiver: function( data ) { 
	 *				// do stuff when custom data is broadcast to the node websocket server
	 *			}
	 *		}
	 *
	 * @return void
	 */
	var nwsDataHandler = function( args ) {
		var self = this;

		// socket connection to use
		self.socket = args.socket;

		// name of room user is joining
		self.roomName = args.roomName;

		// unique user key for the user connecting to the room
		self.userKey = args.userKey;

		if ( typeof args.nodeServerDataReceiver !== 'undefined' ) { // bind custom function
			// get the function override passed in from args
		    self.nodeServerDataReceiver = args.nodeServerDataReceiver;

		    // bind function to self cause we love self
		    self.nodeServerDataReceiver.bind(self);
		}

		if ( typeof args.customDataReceiver !== 'undefined' ) { // bind custom function
			// get the function override passed in from args
		    self.customDataReceiver = args.customDataReceiver;

		    // bind function to self cause we love self
		    self.customDataReceiver.bind(self);
		}

		// connect to room on the node websocket server
		self.connectToRoom( self.roomName );

		// do things when the node websocket server sends us data
		self.setupRoomListener();
	};

	/**
	 * Create/setup and connect to a room on the node websocket server
	 *
	 * @param void
	 *
	 * @return void
	 */
	nwsDataHandler.prototype.connectToRoom = function() {
		var self = this;

		//self.socket.on('connect', function() { // connect to a room on the node server
			// get room data for connecting
			var roomData = self.getRoomData( self.roomName );

			// emit room data to the node websocket server so the user gets added to the node server room
			self.socket.emit( 'room', roomData );
		//});
	};

	/**
	 * Get room data for creating and setting up a room
	 *
	 * @param void
	 *
	 * @return object
	 */
	nwsDataHandler.prototype.getRoomData = function() {
		var self = this;

		return { // user and name of the room the user is joining
			userKey: self.userKey,
   			name: self.roomName
   		}
	};

	/**
	 * Listen to the room on the node websocket server for any
	 * data broacast out to the room.
	 *
	 * @param void
	 *
	 * @return object
	 */
	nwsDataHandler.prototype.setupRoomListener = function() {
		var self = this;

		self.socket.on(self.roomName, function( data ) { // listen to room
			if ( 'node' == data.type ) { // handle data received from the node websocket server for the room
				self.nodeServerDataReceiver( data );
	  		} else if ( 'custom' == data.type ) { // incoming custom data!
	  			self.customDataReceiver( data );
	  		}
	  	});
	};

	// yas! return it all!
	return nwsDataHandler;
})();