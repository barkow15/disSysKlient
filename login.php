<?php 

include 'tcpConnection.php';

session_start();


if($_POST){
	$brugernavn = $_POST['username'];
	$adgangskode = $_POST['password'];


	$brugerInfo = new user();

	$brugerInfo->overallID = "logIn";
	$brugerInfo->email 	 = $brugernavn;
	$brugerInfo->password  = $adgangskode;
	$brugerInfo->isAdmin   = "false";

	$brugerInteraktion = tcpConnect($brugerInfo);
	
	switch ($brugerInteraktion[0]) {
		case "0":
			$_SESSION['user'] = array();
			$_SESSION['user']['userLoggedIn'] = 1;
			$_SESSION['user']['username'] = $brugernavn;
			$_SESSION['user']['userID'] = $brugerInteraktion[1];
			
			//echo $_SESSION['user']['userID'];
			header("Location: month.php");

			break;

		case "3":
			$serverFejl = "Your password does not match the given username";
			break;

		case "2":
			$serverFejl = "Your user is not active";
			break;

		case "1":
			$serverFejl = "We did not recognize the given username. Please try again.";
			break;						
		
		
		default:
			break;
	}
}


?>

<html>
<link href="style.css" rel="stylesheet" type="text/css">
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,400,600,700,800&subset=latin,greek-ext' rel='stylesheet' type='text/css'>
	<body style="background: #ccc">

	<div class="header">
	  	CBS Calendar
	</div>

	<?php if(isset($serverFejl)){ ?>

		<div class="login">
			<form action="" method="POST">
				<input class="user" name="username" type="text" value="" placeholder="username" />
				<input class="submit" type="submit" value="Login" />
				<input class="pass" name="password" type="password" value="" placeholder="password" />
			</form>
			<br><br><br>
			<center>
				<div id="serverError" class="error"><?php echo $serverFejl; ?></div>
			</center>
		</div>		
	<?php } ?>

	<?php if(!isset($brugerInteraktion)){ ?>

	<div class="login">
		<form action="" method="POST">
			<input class="user" name="username" type="text" value="" placeholder="username" />
			<input class="submit" type="submit" value="Login" />
			<input class="pass" name="password" type="password" value="" placeholder="password" />
		</form>
	</div>

	<?php } ?>

<div class="footer">
	Copenhagen Business School &copy;  <?php echo date("Y") ?> 
</div>	
	</body>
</html>