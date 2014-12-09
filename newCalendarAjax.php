<?php  

/*
error_reporting(E_ALL);
ini_set('display_errors', 1);
*/

include "createCalendar.php";
include "tcpConnection.php";

session_start();

if($_POST){
	$calendar = new createCalendar();

	$calendar->calenderName	= $_POST['title'];
	$calendar->userName 	= $_SESSION['user']['username'];
	$calendar->sharedto 	= $_POST['sharedto'];
	$calendar->PublicOrPrivate = $_POST['privatPublic'];

	switch (tcpConnect($calendar)) {
		case 'sucess':
			echo "Calendar succesfully created!";
			break;
		
		default:
			echo "Calendar was not created, try again";
			break;
	}
}




?>