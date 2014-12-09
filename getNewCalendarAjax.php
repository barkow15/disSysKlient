<?php  

include "getNewCalendar.php";

function getNewCalendars(){
	$calendar = new getNewCalendar();
	$calendar->userName 	= $_SESSION['user']['userID'];

	if (tcpConnect($calendar) == "Calendar wasn't found"){

			return false;
			
		}else{

			return tcpConnect($calendar);
		
		}
}

?>