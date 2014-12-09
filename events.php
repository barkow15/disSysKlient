<?php 

	include "hentNytEvent.php";
	include 'tcpConnection.php';

	class events{

		function getCBSCal(){
			$format 		= "json";
			$userIdString 	= "mino13ab";
			$hash_key 		=  md5($userIdString . "v.eRyzeKretW0r_t");

			$getCBSCal = "https://calendar.cbs.dk/events.php/".$userIdString."/".$hash_key.".".$format;

			$json = file_get_contents($getCBSCal);
			$obj = json_decode($json, true);

			return $obj;
		}
		function getDBCal($calID){
			$calendar = new hentNytEvent();
			$calendar->CalenderID = $calID;

			return tcpConnect($calendar);
		}

		public $overallID;
		public $eventTitle;
		public $eventLocation;
		public $eventStartDate;
		public $eventEndDate;
		public $eventShareWith;
		public $eventCalendar;
	}


function dkRemove($remove) {
	    $remove = strtolower($remove);
	    $remove=str_replace('æ','ae',$remove);
	    $remove=str_replace('ø','oe',$remove);
	    $remove=str_replace('å','aa',$remove);    
	    $remove=str_replace('0','',$remove);  
	    $remove=str_replace('1','',$remove);  
	    $remove=str_replace('2','',$remove);  
	    $remove=str_replace('3','',$remove);  
	    $remove=str_replace('4','',$remove);  
	    $remove=str_replace('5','',$remove);  
	    $remove=str_replace('6','',$remove);  
	    $remove=str_replace('7','',$remove);  
	    $remove=str_replace('8','',$remove);  
	    $remove=str_replace('9','',$remove);  
	    $remove=str_replace('(','',$remove);  
	    $remove=str_replace(')','',$remove);  
	    $remove=str_replace('[','',$remove);  
	    $remove=str_replace(']','',$remove);  
	    $remove=str_replace('-','',$remove);  
	    $remove=str_replace(',','',$remove);  	    	    
    return $remove;
}

?>