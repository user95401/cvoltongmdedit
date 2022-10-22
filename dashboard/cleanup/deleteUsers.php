<?php
//Checking if logged in
session_start();
if(!isset($_SESSION["accountID"]) || !$_SESSION["accountID"]) exit(header("Location: ../login/login.php"));
//Requesting files
include "../../incl/lib/connection.php";
require_once "../incl/dashboardLib.php";
require_once "../../incl/lib/mainLib.php";
require_once "../../incl/lib/exploitPatch.php";
$gs = new mainLib();
$ep = new exploitPatch();
$dl = new dashboardLib();
//Checking permissions
if(!$gs->checkPermission($_SESSION["accountID"], "AdminTools")){
	//Printing error
	$errorDesc = $dl->getLocalizedString("errorNoPerm");
	exit($dl->printBox('<h1>'.$dl->getLocalizedString("errorGeneric")."</h1>
					<p>$errorDesc</p>
					<a class='btn btn-primary btn-block' href='".$_SERVER["REQUEST_URI"]."'>".$dl->getLocalizedString("tryAgainBTN")."</a>","cron"));
}
//Checking nothing's empty
if(!empty($_POST["word"])){
$word = $_POST["word"];
if (!empty($_POST["type"])) {$type = $_POST['type'];} else {$type = 1;}
switch ($type) {
		case 1:
			$option1 = "selected";
		    $sqlo = "DELETE FROM users WHERE `userName` LIKE '%".$word."%'";
		    break;
		case 2: 
			$option2 = "selected";
		    $sqlo = "DELETE FROM users WHERE `ip` LIKE '".$word."'";
		    break;
		default:
			$option1 = "selected";
		    $sqlo = "DELETE FROM users WHERE `userName` LIKE '%".$word."%'";
		    break;
}
$doneOrNo = "is-valid";
$db->exec($sqlo) or $doneOrNo = "is-invalid";
$query = $db->prepare($sqlo);
$query->execute();
$query = $db->prepare("SELECT count(*) FROM users");
$query->execute();
$counted = $query->fetchColumn();
$query = $db->prepare("ALTER TABLE `users` auto_increment = $counted");
$query->execute();
	//Printing page
	$dl->printBox('<h1>'.$dl->getLocalizedString("deleteUsers").'</h1>
				<form action="" method="post">
					<div class="form-group">
					<label>'.$dl->getLocalizedString("deleteUsersInfo").'</label>
					<select name="type" class="form-select">
					<option '.$option1.' value="1">userName</option>
					<option '.$option2.' value="2">ip</option>
					</select>
					<br>
						<input autofocus required type="text" class="form-control '.$doneOrNo.'" id="word" name="word">
					</div>
					<button type="submit" class="btn btn-primary btn-block">'.$dl->getLocalizedString("deleteUsers").'</button>
				</form>',"cron");
}else{
	//Printing page
	$dl->printBox('<h1>'.$dl->getLocalizedString("deleteUsers").'</h1>
				<form action="" method="post">
					<div class="form-group">
					<label>'.$dl->getLocalizedString("deleteUsersInfo").'</label>
					<select name="type" class="form-select">
					<option '.$option1.' value="1">userName</option>
					<option '.$option2.' value="2">ip</option>
					</select>
					<br>
						<input autofocus required type="text" class="form-control" id="word" name="word">
					</div>
					<button type="submit" class="btn btn-primary btn-block">'.$dl->getLocalizedString("deleteUsers").'</button>
				</form>',"cron");
}
?>