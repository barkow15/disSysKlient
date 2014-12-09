<?php

include 'tcpConnection.php';
include 'deleteCalendarClass.php';

if(isset($_POST['deleteCalendar'])){
	$cal = new deleteCalendar();

	$cal->calenderName = $_POST['deleteCalendar'];
	$cal->userName = $_SESSION['user']['username'];

	$response = tcpConnect($cal);
	if($response == "Calender has been deleted"){
		header("refresh:2;url=month.php");
		echo "Calender has been deleted. Redirecting back to calendar. Please wait...";
	}else{
		header("refresh:2;url=month.php");
		echo $response . ". Redirecting back to calendar. Please wait...";
	}
}




?>