<?php
include("xor.php");
include 'user.php';

error_reporting(E_ALL);

function tcpConnect($input){
	/* Get the port for the WWW service. */
	$service_port = 8989;

	/* Get the IP address for the target host. */
	$address = gethostbyname('localhost');

	/* Create a TCP/IP socket. */
	$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP); 

	$result = socket_connect($socket, $address, $service_port);

	$json = json_encode((array)$input);

	$in = encrypt_str($json);
	$out = '';

	socket_write($socket, $in, strlen($in));

	socket_set_option($socket,SOL_SOCKET, SO_RCVTIMEO, array("sec"=>1, "usec"=>0));

	$reply = '';
	while(true) {
	    $chunk = @socket_read($socket, 10000);

	    if (strlen($chunk) == 0) {
	        // no more data
	        break;
	    }
	    $reply .= $chunk;
	}

	return $reply;

	socket_close($socket);
}


//echo tcpConnect($userInfo);

?>
