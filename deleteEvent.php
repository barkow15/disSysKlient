<?php

include 'tcpConnection.php';
include 'deleteEventClass.php';

if(isset($_POST['deleteEvent'])){
	$event = new deleteEvent();

	$event->eventid = $_POST['deleteEvent'];

	$response = tcpConnect($event);

	switch ($respone) {
		case 'success':
			header("refresh:2;url=month.php");
			echo "Event deleted succesfully. Redirecting back to calendar. Please wait...";
			break;
		
		case 'fejl':
			header("refresh:2;url=month.php");
			echo "Something went wrong. Please try agian. Redirecting back to calendar. Please wait...";
			break;
	}
}




?>