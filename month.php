<?php 

include 'events.php';
include 'hentNote.php';
include 'getNewCalendarAjax.php';
include 'getQotdClass.php';

session_start();

if(!isset($_SESSION['user']['userLoggedIn'])){
    header("Location: login.php");
}

$alleKalendere = getNewCalendars(); 

$alleKalendereJson = json_decode($alleKalendere, true);
$alleKalendereIndhold = array();
$counter = 0;
foreach($alleKalendereJson as $enkeltKalender){
    $kalenderNavn = $enkeltKalender['calenderName'];
    $kalenderID   = $enkeltKalender['CalendarID'];

    $alleKalendereIndhold[$counter]['calenderName'][] = $kalenderNavn;
    $alleKalendereIndhold[$counter]['CalendarID'][]   = $kalenderID;
    $counter++;
}

$alleEvents = array();

$events = new events();

$eventsTaeller = 0;
foreach($alleKalendereIndhold as $enkeltKalender2){
    $kalenderID = (int)$enkeltKalender2['CalendarID'][0];

    $dbevents   = json_decode($events->getDBCal($kalenderID), true);

    foreach($dbevents as $event) { 
        $eventID      = $event["eventid"];
        $eventTitle     = $event["name"]; 

        $note = new hentNote();
        $note->eventid = $eventID;
        $notes = json_decode(tcpConnect($note), true);

        foreach($notes as $noteVal){
            $id       = $noteVal['noteId'];
            $noteText = $noteVal['noteText'];

            $alleEvents[$eventsTaeller]['notes'][$id]['noteText'] = $noteText;
            $alleEvents[$eventsTaeller]['notes'][$id]['noteID']   = $id;
        }

        $alleEvents[$eventsTaeller]['eventid'] = $eventID;
        $alleEvents[$eventsTaeller]['name']    = $eventTitle;
        $eventsTaeller++;

    }
}
?>

<html>
<head>
	<link href="style.css" rel="stylesheet" type="text/css">
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,400,600,700,800&subset=latin,greek-ext' rel='stylesheet' type='text/css'>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<script src="js/main.js" type="text/javascript"></script>
    <script src="js/dateSelector.js" type="text/javascript"></script>
	<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
	<meta charset="UTF-8">
</head>

<body>

	<!-- SETTINGS START-->
	<nav class="left-spmenu left-spmenu-vertical left-spmenu-left" id="left-spmenu-s1">
    <div class="settings-button-back" id="showLeftBack"></div>
		<div class="left-spmenu-top">Settings</div>

		<div class="menu-inner-left">
            <a class="logout" href="logout.php">Log Out</a>		    
		</div>
	</nav>

    <nav class="left-spmenu left-spmenu-vertical left-spmenu-right" id="left-spmenu-s2">
        <div class="addEvent-button-back" id="showRightBack"></div>
        <div class="left-spmenu-top" style="margin-left: -1px;">Add Event:</div>

        <div class="menu-inner-right">

            <button id="createCalendar" class="add-event-button">Add / Delete Calendar</button>
                    <form class="add-event" action="action_page.php" id="add-event">
                        <br>
                        <input type="text" id="title" value="Title" onfocus="if (this.value=='Title') this.value='';" onblur="if (this.value=='') this.value='Title';"/>
                        <div class="seperator"></div>
                        <input type="text" id="location" value="Location" onfocus="if (this.value=='Location') this.value='';" onblur="if (this.value=='') this.value='Location';"/>

                        <br><br>

                        <span>Start Time</span><input class="date" id="dateStart" type="date" name="startDate" value="<?php echo date("Y-n-d"); ?>"><input class="time" id="startTime" type="time" name="startTime" value="<?php echo date("G:00"); ?>">
                        <br><br>
                        <span>End Time</span><input class="date" id="dateEnd" type="date" name="endDate" value="<?php echo date("Y-n-d"); ?>"><input class="time" id="endTime" type="time" name="endTime" value="<?php echo date("G")+1; ?>:00">

                        <br><br>

                        <textarea type="text" id="description" rows="3" value="Descrpition" onfocus="if (this.value=='Descrpition') this.value='';" onblur="if (this.value=='') this.value='Descrpition';"/></textarea>

                        <br><br>

                        <span>Type:</span>
                        <select class="select" id="type">
                            <option value="0">Lecture</option>
                            <option value="1">Exercise</option>
                            <option value="2">Other</option>
                        </select><br><br>                    

                        <span>Calendar:</span>
                        <select class="select" id="calendar">
                            <?php 

                            //print_r($alleKalendereIndhold);

                            foreach($alleKalendereIndhold as $enkeltKalender1){
                                $kalenderNavn1 = $enkeltKalender1['calenderName'][0];
                                $kalenderID1   = $enkeltKalender1['CalendarID'][0];
                            ?>

                                <option value="<?= $kalenderID; ?>"><?= $kalenderNavn1; ?></option>
                            
                            <?php } ?>
                            </select><br><br>

                        <input type="submit" class="submit" value="Add Event" id="add-gif"> 
                
                        <center>
                            <div class="loading"></div>
                        </center>
                    </form>

                        <form class="delete-event" action="deleteEvent.php" method="POST">
                            <select class="select" id="event" name="deleteEvent">
                            <?php 
                       
                            foreach($alleEvents as $singleEvent){
                                $eventName = $singleEvent['name'];
                                $eventID   = $singleEvent['eventid'];
                            ?>

                                <option value="<?= $eventID; ?>"><?= $eventName; ?></option>
                            
                            <?php } ?>
                            </select><br><br>

                            <input type="submit" class="submit" value="Delete Event" id="add-gif"> 
                        </form>   

                    <button id="createEvent" class="add-calendar-button">Add / Delete Event</button>
                    <form class="add-calendar" action="action_page.php" id="add-calendar">
                        <br>
                        <input type="text" id="title" value="Title" onfocus="if (this.value=='Title') this.value='';" onblur="if (this.value=='') this.value='Title';"/>

                        <br><br>                  

                        <input type="text" id="sharedto" value="Share With: (comma separated)" onfocus="if (this.value=='Share With: (comma separated)') this.value='';" onblur="if (this.value=='') this.value='Share With: (comma separated)';"/>   

                        <br><br>                   

                        <input type="submit" class="submit" value="Add Calendar"> 

                        <center>
                            <div class="loading"></div>
                        </center>
                    </form>      

                        <form class="delete-calendar" action="deleteCalendar.php" method="POST">
                            <select class="select" id="deleteCalendar" name="deleteCalendar">
                            <?php 

                            //print_r($alleKalendereIndhold);

                            
                            foreach($alleKalendereIndhold as $enkeltKalender){
                                $kalenderNavn = $enkeltKalender['calenderName'][0];
                                $kalenderID   = $enkeltKalender['CalendarID'][0];
                            
                            ?>

                                <option value="<?= $kalenderNavn; ?>"><?= $kalenderNavn; ?></option>
                            
                            <?php } ?>
                            </select><br><br>

                            <input type="submit" class="submit" value="Delete Calendar"> 
                        </form>                                  

        </div>
    </nav>    
	
	<!-- SETTINGS END-->	
<div class="settings-button" id="showLeftPush"></div>
<div class="addEvent-button" id="showRightPush"></div>
<!-- Header med ugenummer  -->
<div class="header">
	<!-- <button class="prev-day weekcontrol">&#8592;</button> -->
  	CBS Calendar
 	<!-- <button class="next-day weekcontrol">&#8594;</button> -->
</div>

<div class="sidebar">
	<h2>Select Date:</h2>    
	  	<center>
            <div class="week-picker" id="picker"></div>
		</center>
</div>

<!-- indeholde kalendarmodul -->
<div class="calendarbody">
  <div class="timebody">
    <div class="time">&nbsp;</div>
    <div class="time">8:00</div>
    <div class="time">9:00</div>
    <div class="time">10:00</div>
    <div class="time">11:00</div>
    <div class="time">12:00</div>
    <div class="time">13:00</div>
    <div class="time">14:00</div>
    <div class="time">15:00</div>
    <div class="time">16:00</div>
    <div class="time">17:00</div>
    <div class="time">18:00</div>
    <div class="time">19:00</div>
    <div class="time">20:00</div>
    <div class="time">21:00</div>
    <div class="time">22:00</div>
  </div>
<?php 
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


for ($i = 0; $i < 7; $i++) {
    $weekday_year   = date("Y", $ts + $i * 86400) . "\n";
    $weekday_month  = date("m", $ts + $i * 86400) . "\n";
    $weekday_day    = date("l", $ts + $i * 86400) . "\n";
    $weekday_day_number    = date("j", $ts + $i * 86400) . "\n";
    $weekday_date   = date("d", $ts + $i * 86400) . "\n";
    $currentDateValidate = intval($weekday_year).intval($weekday_month).intval($weekday_day_number);


    // Tidskonvetering for event
    $events = new events();

    $jsonDecode = $events->getCBSCal();

    ?>
    <div class="weekdaybody">
        <?php 
            foreach ($jsonDecode['events'] as $event) { 

            $dateYearStart      = intval($event["start"][0]);
            $dateMonthStart     = intval($event["start"][1]);

            $dateDayStart       = intval($event["start"][2]);
            $dateTimeStartHour  = intval($event["start"][3]);
            $dateTimeStartMin   = intval($event["start"][4]);

            $dateDayEnd         = intval($event["end"][2]);
            $dateTimeEndHour    = intval($event["end"][3]);
            $dateTimeEndMin     = intval($event["end"][4]);


            $eventType      = $event["type"];
            $eventTitle     = $event["description"];
            $eventLocation  = $event["location"]; 
            $startPos = (($dateTimeStartHour-8) * 60) + $dateTimeStartMin+60;
            $endPos = (($dateTimeEndHour-8) * 60) + $dateTimeEndMin+60;
            $duration = $endPos-$startPos;

            $eventTitleCssClass = str_replace(' ', '', $eventTitle);

            $jsonDateValidate = $dateYearStart.$dateMonthStart.$dateDayStart;
                if($jsonDateValidate == $currentDateValidate){
                    ?>
                    <div class="event <?php echo $eventTitleCssClass; ?>" style="top: <?= $startPos; ?>px; height: <?= $duration; ?>;">
                        <div class="event-header"><?= str_pad($dateTimeStartHour, 2, '0', STR_PAD_LEFT); ?>:<?= str_pad($dateTimeStartMin, 2, '0', STR_PAD_LEFT); ?> - <?= str_pad($dateTimeEndHour, 2, '0', STR_PAD_LEFT); ?>:<?= str_pad($dateTimeEndMin, 2, '0', STR_PAD_LEFT); ?></div>

                        <span class="event-course"><?= $eventTitle; ?></span><br>
                        <span class="event-type"><?= $eventType; ?></span><br>
                        <span class="event-location"><?= $eventLocation; ?></span><br>
                    </div>
                    <?php
                }
            }

            //print_r($alleKalendereIndhold);

            
            foreach($alleKalendereIndhold as $enkeltKalender2){
                $kalenderID = (int)$enkeltKalender2['CalendarID'][0];

                $dbevents   = json_decode($events->getDBCal($kalenderID), true);
                foreach($dbevents as $event) { 

                $dateYearStart      = intval($event["startTime"][0] . $event["startTime"][1] . $event["startTime"][2] . $event["startTime"][3]);
                $dateMonthStart     = intval($event["startTime"][5] . $event["startTime"][6]);

                $dateDayStart       = intval($event["startTime"][8] . $event["startTime"][9]);
                $dateTimeStartHour  = intval($event["startTime"][11] . $event["startTime"][12]);
                $dateTimeStartMin   = intval($event["startTime"][14] . $event["startTime"][15]);

                $dateDayEnd         = intval($event["endTime"][8] . $event["endTime"][9]);
                $dateTimeEndHour    = intval($event["endTime"][11] . $event["endTime"][12]);
                $dateTimeEndMin     = intval($event["endTime"][14] . $event["endTime"][15]);

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


                $startPos = (($dateTimeStartHour-8) * 60) + $dateTimeStartMin+60;
                $endPos = (($dateTimeEndHour-8) * 60) + $dateTimeEndMin+60;
                $duration = $endPos-$startPos;

                $eventTitleCssClass = substr(str_replace(' ', '', $eventTitle), 0, -4);

                $jsonDateValidate = $dateYearStart.$dateMonthStart.$dateDayStart;
                    if($jsonDateValidate == $currentDateValidate){
                        
                        echo "<div class='event ".dkRemove($eventTitleCssClass)."' style='top:". $startPos . "px; height: ". $duration . "'>
                            <div class='event-header'>". str_pad($dateTimeStartHour, 2, '0', STR_PAD_LEFT) . ":". str_pad($dateTimeStartMin, 2, '0', STR_PAD_LEFT) . " - ". str_pad($dateTimeEndHour, 2, '0', STR_PAD_LEFT) .":". str_pad($dateTimeEndMin, 2, '0', STR_PAD_LEFT) . "</div>
                            <span class='event-course'>" .$eventTitle. "</span><br>
                            <span class='event-type'>" . $eventType . "</span><br>
                            <span class='event-location'>" . $eventLocation . "</span>

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
                                    echo "<ol class='note-list'>";

                                        foreach($singleEvent['notes'] as $noteValOutput){
                                            $noteText = $noteValOutput['noteText'];
                                            $noteID   = $noteValOutput['noteID'];

                                            ?>

                                            <li>
                                                <span><?= $noteText; ?></span>

                                                <form action="deleteNote.php" method="POST" class="note-delete-form">
                                                    <input type="hidden" value="<?= $noteID; ?>" name="noteID" />
                                                    <input type="submit" value="x" class="note-delete-submit"/>
                                                </form>
                                            </li>

                                            <?php  
                                        }

                                        echo "</ol>";

                                    }

                                }
                            }
                            /*
                            foreach($event['notes'] as $eventNotes){
                                print_r($eventNotes);
                            }
                            */
                        echo "</div>";
                    
                    }
                }
            }
        ?>
        <div class="weekday no-bg"><?= $weekday_day; ?><br><span class="date"><?= $weekday_date; ?></span></div>
        <div class="weekday"></div>
        <div class="weekday"></div>
        <div class="weekday"></div>
        <div class="weekday"></div>
        <div class="weekday"></div>
        <div class="weekday"></div>
        <div class="weekday"></div>
        <div class="weekday"></div>
        <div class="weekday"></div>
        <div class="weekday"></div>
        <div class="weekday"></div>
        <div class="weekday"></div>
        <div class="weekday"></div>
        <div class="weekday"></div>
        <div class="weekday"></div>
    </div>
    <?php
}

?>
</div>

<div class="footer" style="color:#FFFFFF;">
    <?php 
        $dagensCitat = new getQuote();

        $citat = json_decode(tcpConnect($dagensCitat->overallID), true);
        
        echo "\"" . $citat['quote'] . "\"" .  "<br/> - " . $citat['author'];
    ?>
    <br>     
</div>

<script src="js/menu-left.js"></script>

</body>
</html>