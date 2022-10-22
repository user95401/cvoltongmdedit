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

//Checking if already logged in
session_start();
if(isset($_SESSION["accountID"])) exit($dl->printLoginBox("<p>You are already logged in. <a class='a' onclick='window.history.back()'>Click here to continue</a><br>Or you want <a href='login/logout.php'>logout?</a></p>"));

$userName = ExploitPatch::remove($_GET["userName"]);

//Checking nothing's emtpy
if(isset($_POST["username"]) && isset($_POST["password"])){
	//Getting form data
	$username = $ep->remove($_POST["username"]);
	$password = $_POST["password"];
	//Checking username and pasword
	if(!$gp->isValidUsrname($username, $password)) exit($dl->printLoginBoxInvalid());
	//Getting account info
	$accountID = $gs->getAccountIDFromName($username);
	if(!$accountID) exit($dl->printLoginBoxError("Invalid accountID"));
	//Setting data
	$_SESSION["accountID"] = $accountID;
	//Printing message
	echo "<body onload='window.history.back()'>";
}else{
    if ($userName !== ''){$yees = ' value="'.$userName.'" ';}else{$yees = '';}
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
	$dl->printLoginBox($loginbox);
}
?>