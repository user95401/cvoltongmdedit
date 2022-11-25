<?php
//Requesting files
include "../../config/email.php";
include "../../incl/lib/connection.php";
require_once "../incl/dashboardLib.php";
require_once "../../incl/lib/mainLib.php";
require_once "../../incl/lib/generatePass.php";
require_once "../../incl/lib/exploitPatch.php";
$gs = new mainLib();
$gp = new generatePass();
$dl = new dashboardLib();
$ep = new exploitPatch();
$dl = new dashboardLib();
$userName = ExploitPatch::remove($_GET["userName"]);
if ($userName !== ''){$yees = ' value="'.$userName.'" ';}else{$yees = '';}
$urlWas = $_SESSION["urlWas"];

//Checking nothing's emtpy
if(isset($_POST["username"]) && isset($_POST["password"])){
	//Getting form data
	$username = $ep->remove($_POST["username"]);
	$password = $_POST["password"];
	//Checking username and pasword
	if(!$gp->isValidUsrname($username, $password)) $loginerror .= $dl->getLocalizedString("loginInvalid");
	//Getting account info
	$accountID = $gs->getAccountIDFromName($username);
	if(!$accountID) $loginerror .= '<br>'.$dl->getLocalizedString("loginInvalidAccID");
	if($accountID and $gp->isValidUsrname($username, $password)){
	    //Setting data
	    $_SESSION["accountID"] = $accountID;
	    //redicret
        if($urlWas == "http://cvoltongmdedit.webq2e.ru/f/dashboard/login/login.php" or $urlWas == "" or empty($urlWas)) {
            header("Location: ../home.php");
        }
        else{
            header("Location: $urlWas");
        }
	}
	if(!$accountID and !$gp->isValidUsrname($username, $password)){
	//Printing page
	$loginbox = '<form action="" method="post">
							<div class="form-group">
								<label for="usernameField">Username</label>
								<input '.$yees.$userName.' type="text" class="form-control" id="usernameField" name="username" placeholder="Enter username">
							</div>
							<div class="form-group">
								<label for="passwordField">Password</label>
								<input type="password" class="form-control" id="passwordField" name="password" placeholder="Password">
							</div>
								<b class="link-danger">'.$loginerror.'</b><br><br>
							<button type="submit" class="btn btn-primary">Log In</button>
						</form>';
	$dl->printBox($loginbox,$active,true);
	}
}else{
	//Printing page
	$loginbox = '<form action="" method="post">
							<div class="form-group">
								<label for="usernameField">Username</label>
								<input '.$yees.$userName.' type="text" class="form-control" id="usernameField" name="username" placeholder="Enter username">
							</div>
							<div class="form-group">
								<label for="passwordField">Password</label>
								<input type="password" class="form-control" id="passwordField" name="password" placeholder="Password">
							</div>
							<button type="submit" class="btn btn-primary">Log In</button>
						</form>';
	$dl->printBox($loginbox,$active,true);
}
?>