<?php
// Ugehåndtering

	// set current date
	$date = date("m/d/y"); 
	// parse about any English textual datetime description into a Unix timestamp
	$ts = strtotime($date);
	// calculate the number of days since Monday
	$dow = date('w', $ts);
	$offset = $dow - 1;
	if ($offset < 0) {
	    $offset = 6;
	}
	// calculate timestamp for the Monday
	$ts = $ts - $offset*86400;
	// print current week
	print "Current week\n";
	for ($i = 0; $i < 7; $i++) {
	    print date("m/d/Y l", $ts + $i * 86400) . "\n";


	    // Tidskonvetering for event
		$jsonString = "{\"activityid\":\"BINTO1067U_LA_E14\",\"eventid\":\"BINTO1067U_LA_E14_714ff8c1a1d8f5e918829fef3ff92a0f_23e125dbca8f1d6655b7a40a77481a82\",\"type\":\"Exercise\",\"title\":\"BINTO1067U.LA_E14\",\"description\":\"Distribuerede systemer (LA)\",\"start\":[\"2014\",8,\"15\",\"8\",\"00\"],\"end\":[\"2014\",8,\"15\",\"9\",\"40\"],\"location\":\"SP213\"}";
		$jsonDecode = json_decode($jsonString, true);

			$dateDayStart		= intval($jsonDecode["start"][2]);
			$dateTimeStartHour	= intval($jsonDecode["start"][3]);
			$dateTimeStartMin	= intval($jsonDecode["start"][4]);

			$dateDayEnd 		= intval($jsonDecode["end"][2]);
			$dateTimeEndHour	= intval($jsonDecode["end"][3]);
			$dateTimeEndMin		= intval($jsonDecode["end"][4]);

			$startPos = (($dateTimeStartHour-8) * 60) + $dateTimeStartMin+60;
			$endPos = (($dateTimeEndHour-8) * 60) + $dateTimeEndMin+60;
			$duration = $endPos-$startPos;

			echo $startPos;
			echo $endPos;
			echo $duration;
	}
 ?>