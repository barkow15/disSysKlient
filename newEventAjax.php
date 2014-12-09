<?php  

/*
error_reporting(E_ALL);
ini_set('display_errors', 1);
*/

include "createEvents.php";
include "tcpConnection.php";

session_start();

if($_POST){
	$event = new createEvents();

	
	$event->eventid = 1;
	$event->name 	  	= $_POST['title'];
	$event->location 	= $_POST['location'];
	$event->startTime	= $_POST['startDate'];
	$event->endTime  	= $_POST['endDate'];
	$event->type 		= $_POST['type'];
	$event->CalenderID 	= $_POST['calendar'];
	$event->text 		= $_POST['description'];
	$event->createdby   = $_SESSION['user']['userID'];

	if(tcpConnect($event) == 1){
		echo "The event was succesfully created!";
	}

}




?>