<?php

include 'tcpConnection.php';
include 'deleteNoteClass.php';

if(isset($_POST['noteID'])){
	$note = new deleteNote();

	$note->noteId = $_POST['noteID'];

	$response = tcpConnect($note);
	if($response == "note has been deleted"){
		header("refresh:2;url=month.php");
		echo "Note has been deleted. Redirecting back to calendar. Please wait...";
	}else{
		header("refresh:2;url=month.php");
		echo ". Redirecting back to calendar. Please wait...";
	}
}




?>