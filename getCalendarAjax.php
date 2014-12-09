<?php 

include 'events.php';
include 'hentNote.php';
include 'getNewCalendarAjax.php';

session_start();

$alleKalendere = getNewCalendars(); 

$alleKalendereJson = json_decode($alleKalendere, true);
$alleKalendereIndhold = array();
$taeller = 0;
foreach($alleKalendereJson as $enkeltKalender){
    $calName = $enkeltKalender['calenderName'];
    $calID   = $enkeltKalender['CalendarID'];

    $alleKalendereIndhold[$taeller]['calenderName'][] = $calName;
    $alleKalendereIndhold[$taeller]['CalendarID'][]   = $calID;
    $taeller++;
}

$alleEvents = array();

$events = new events();

$taellerEvents = 0;
foreach($alleKalendereIndhold as $enkeltKalender2){
    $calID = (int)$enkeltKalender2['CalendarID'][0];

    $dbevents   = json_decode($events->getDBCal($calID), true);

    foreach($dbevents as $event) { 
        $eventID      = $event["eventid"];
        $eventTitle     = $event["name"]; 

        $note = new hentNote();
        $note->eventid = $eventID;
        $notes = json_decode(tcpConnect($note), true);

        foreach($notes as $noteVal){
            $id       = $noteVal['noteId'];
            $noteText = $noteVal['noteText'];

            $alleEvents[$taellerEvents]['notes'][$id]['noteText'] = $noteText;
            $alleEvents[$taellerEvents]['notes'][$id]['noteID']   = $id;
        }

        $alleEvents[$taellerEvents]['eventid'] = $eventID;
        $alleEvents[$taellerEvents]['name']    = $eventTitle;
        $taellerEvents++;

    }
}


$ugeStart = $_REQUEST['dateStart'];
$ugeSlut = $_REQUEST['dateEnd'];

// set current date
$dato = $ugeStart;
// parse about any English textual datetime description into a Unix timestamp
$ts = strtotime($dato);
// calculate the number of days since Monday
$dow = date('w', $ts);
$offset = $dow - 1;
if ($offset < 0) {
    $offset = 6;
}
// calculate timestamp for the Monday
$ts = $ts - $offset*86400;
// print current week

$weekdaybody_html = array();

for ($i = 0; $i < 7; $i++) {
    $weekday_year        = date("Y", $ts + $i * 86400) . "\n";
    $weekday_month       = date("m", $ts + $i * 86400) . "\n";
    $weekday_day         = date("l", $ts + $i * 86400) . "\n";
    $weekday_day_number  = date("j", $ts + $i * 86400) . "\n";
    $weekday_date        = date("d", $ts + $i * 86400) . "\n";
    $currentDateValidate = intval($weekday_year).intval($weekday_month).intval($weekday_day_number);

    $events = new events();

    $jsonDecode = $events->getCBSCal();

    $weekdaybody_html_var = "<div class='weekdaybody'>";
    //$weekdaybody_html_var.= "<pre>" . json_decode($dbevents, true) . "</pre>";
            

            foreach ($jsonDecode['events'] as $event) { 

            $datoYearStart      = intval($event["start"][0]);
            $datoMonthStart     = intval($event["start"][1]);

            $datoDayStart       = intval($event["start"][2]);
            $datoTimeStartHour  = intval($event["start"][3]);
            $datoTimeStartMin   = intval($event["start"][4]);

            $datoDayEnd         = intval($event["end"][2]);
            $datoTimeEndHour    = intval($event["end"][3]);
            $datoTimeEndMin     = intval($event["end"][4]);

            $eventType      = $event["type"];
            $eventTitle     = $event["description"];
            $eventLocation  = $event["location"]; 


            $startPos = (($datoTimeStartHour-8) * 60) + $datoTimeStartMin+60;
            $endPos = (($datoTimeEndHour-8) * 60) + $datoTimeEndMin+60;
            $duration = $endPos-$startPos;

            $eventTitleCssClass = substr(str_replace(' ', '', $eventTitle), 0, -4);

            
            //Get events from CBS calender API
            $jsonDateValidate = $datoYearStart.$datoMonthStart.$datoDayStart;
                if($jsonDateValidate == $currentDateValidate){
                    
                    $weekdaybody_html_var.="<div class='event ".dkRemove($eventTitleCssClass)."' style='top:". $startPos . "px; height: ". $duration . "'>
                        <div class='event-header'>". str_pad($datoTimeStartHour, 2, '0', STR_PAD_LEFT) . ":". str_pad($datoTimeStartMin, 2, '0', STR_PAD_LEFT) . " - ". str_pad($datoTimeEndHour, 2, '0', STR_PAD_LEFT) .":". str_pad($datoTimeEndMin, 2, '0', STR_PAD_LEFT) . "</div>

                        <span class='event-course'>" .$eventTitle. "</span><br>
                        <span class='event-type'>" . $eventType . "</span><br>
                        <span class='event-location'>" . $eventLocation . "</span><br>
                    </div>";
                    
                }
            }


            foreach($alleKalendereIndhold as $enkeltKalender2){
                $calID = (int)$enkeltKalender2['CalendarID'][0];

                $dbevents   = json_decode($events->getDBCal($calID), true);
                foreach($dbevents as $event) { 

                $datoYearStart      = intval($event["startTime"][0] . $event["startTime"][1] . $event["startTime"][2] . $event["startTime"][3]);
                $datoMonthStart     = intval($event["startTime"][5] . $event["startTime"][6]);

                $datoDayStart       = intval($event["startTime"][8] . $event["startTime"][9]);
                $datoTimeStartHour  = intval($event["startTime"][11] . $event["startTime"][12]);
                $datoTimeStartMin   = intval($event["startTime"][14] . $event["startTime"][15]);

                $datoDayEnd         = intval($event["endTime"][8] . $event["endTime"][9]);
                $datoTimeEndHour    = intval($event["endTime"][11] . $event["endTime"][12]);
                $datoTimeEndMin     = intval($event["endTime"][14] . $event["endTime"][15]);


                switch ($event['type']) {
                    case '0':
                        $eventType = "Lecture";
                        break;
                    case '1':
                        $eventType = "Exercise";
                        break;
                    case '2':
                        $eventType = "Other";
                        break;
                }
                //$eventType      = $event["type"];
                $eventTitle     = $event["name"];
                $eventLocation  = $event["location"]; 


                $startPos = (($datoTimeStartHour-8) * 60) + $datoTimeStartMin+60;
                $endPos = (($datoTimeEndHour-8) * 60) + $datoTimeEndMin+60;
                $duration = $endPos-$startPos;

                $eventTitleCssClass = substr(str_replace(' ', '', $eventTitle), 0, -4);

                $jsonDateValidate = $datoYearStart.$datoMonthStart.$datoDayStart;
                    if($jsonDateValidate == $currentDateValidate){
                        
                        $weekdaybody_html_var .= "<div class='event ".dkRemove($eventTitleCssClass)."' style='top:". $startPos . "px; height: ". $duration . "'>
                            <div class='event-header'>". str_pad($datoTimeStartHour, 2, '0', STR_PAD_LEFT) . ":". str_pad($datoTimeStartMin, 2, '0', STR_PAD_LEFT) . " - ". str_pad($datoTimeEndHour, 2, '0', STR_PAD_LEFT) .":". str_pad($datoTimeEndMin, 2, '0', STR_PAD_LEFT) . "</div>
                            <span class='event-course'>" .$eventTitle. "</span><br>
                            <span class='event-type'>" . $eventType . "</span><br>
                            <span class='event-location'>" . $eventLocation . "</span><br>

                            <div class='note-form-event'>
                                <form class='note-form' action='saveNote.php' method='POST'>
                                    <textarea class='notebox' maxlength='50' name='note' placeholder='Insert Note (max 50 caracters)'></textarea>

                                    <input type='submit' class='note-button' value='create note' />
                                    <input type='hidden' value='".$event['eventid']."' name='eventid'/>
                                </form> 
                            </div>";
                            foreach($alleEvents as $singleEvent){
                                $eventName = $singleEvent['name'];
                                $eventID   = $singleEvent['eventid'];


                                if($eventID == $event['eventid']){

                                    if(isset($singleEvent['notes'])){
                                    $weekdaybody_html_var .= "<ol class='note-list'>";

                                        foreach($singleEvent['notes'] as $noteValOutput){
                                            $noteText = $noteValOutput['noteText'];
                                            $noteID   = $noteValOutput['noteID'];

                                            

                                            $weekdaybody_html_var .= "
                                            <li>
                                                <span>" . $noteText . "</span>

                                                <form action='deleteNote.php' method='POST' class='note-delete-form'>
                                                    <input type='hidden' value='" . $noteID . "' name='noteID' />
                                                    <input type='submit' value='x' class='note-delete-submit'/>
                                                </form>
                                            </li>";

                                            
                                        }

                                        $weekdaybody_html_var .= "</ol>";

                                    }

                                }
                            }
                            /*
                            foreach($event['notes'] as $eventNotes){
                                print_r($eventNotes);
                            }
                            */
                        $weekdaybody_html_var .= "</div>";
                    
                    }
                }
            }

            /*
            foreach($dbevents as $event) { 

            $datoYearStart      = intval($event["startTime"][0] . $event["startTime"][1] . $event["startTime"][2] . $event["startTime"][3]);
            $datoMonthStart     = intval($event["startTime"][5] . $event["startTime"][6]);

            $datoDayStart       = intval($event["startTime"][8] . $event["startTime"][9]);
            $datoTimeStartHour  = intval($event["startTime"][11] . $event["startTime"][12]);
            $datoTimeStartMin   = intval($event["startTime"][14] . $event["startTime"][15]);

            $datoDayEnd         = intval($event["endTime"][8] . $event["endTime"][9]);
            $datoTimeEndHour    = intval($event["endTime"][11] . $event["endTime"][12]);
            $datoTimeEndMin     = intval($event["endTime"][14] . $event["endTime"][15]);

            $eventType      = $event["type"];
            $eventTitle     = $event["name"];
            $eventLocation  = $event["location"]; 


            $startPos = (($datoTimeStartHour-8) * 60) + $datoTimeStartMin+60;
            $endPos = (($datoTimeEndHour-8) * 60) + $datoTimeEndMin+60;
            $duration = $endPos-$startPos;

            $eventTitleCssClass = substr(str_replace(' ', '', $eventTitle), 0, -4);

            $jsonDateValidate = $datoYearStart.$datoMonthStart.$datoDayStart;
                if($jsonDateValidate == $currentDateValidate){
                    
                    $weekdaybody_html_var.="<div class='event ".dkRemove($eventTitleCssClass)."' style='top:". $startPos . "px; height: ". $duration . "'>
                        <div class='event-header'>". str_pad($datoTimeStartHour, 2, '0', STR_PAD_LEFT) . ":". str_pad($datoTimeStartMin, 2, '0', STR_PAD_LEFT) . " - ". str_pad($datoTimeEndHour, 2, '0', STR_PAD_LEFT) .":". str_pad($datoTimeEndMin, 2, '0', STR_PAD_LEFT) . "</div>

                        <span class='event-course'>" .$eventTitle. "</span><br>
                        <span class='event-type'>" . $eventType . "</span><br>
                        <span class='event-location'>" . $eventLocation . "</span><br>
                    </div>";
                    
                }
            }
            */
        
    $weekdaybody_html_var.="<div class='weekday no-bg'>". $weekday_day."<br><span class='date'>". $weekday_date ."</span></div>
        <div class='weekday'></div>
        <div class='weekday'></div>
        <div class='weekday'></div>
        <div class='weekday'></div>
        <div class='weekday'></div>
        <div class='weekday'></div>
        <div class='weekday'></div>
        <div class='weekday'></div>
        <div class='weekday'></div>
        <div class='weekday'></div>
        <div class='weekday'></div>
        <div class='weekday'></div>
        <div class='weekday'></div>
        <div class='weekday'></div>
        <div class='weekday'></div>
    </div>";

    array_push($weekdaybody_html, $weekdaybody_html_var);

    //echo($weekdaybody_html_var);
    json_encode($weekdaybody_html, JSON_HEX_QUOT | JSON_HEX_TAG);
}

?>

<?php foreach($weekdaybody_html as $weekdaybody_html_output){ 
        echo $weekdaybody_html_output;
    }
?>



