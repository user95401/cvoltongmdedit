<?php
include "../../incl/lib/connection.php";
require "../../incl/lib/generatePass.php";
require_once "../../incl/lib/exploitPatch.php";
require_once "../incl/dashboardLib.php";
require_once "../../incl/lib/mainLib.php";
require_once "../../incl/lib/generatePass.php";
$dl = new dashboardLib();
$gs = new mainLib();
$generatePass = new generatePass();
//redicret if not logined
$urlWas = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$urlWas = explode('?', $urlWas); $_SESSION["urlWas"] = $urlWas[0];
if(!isset($_SESSION["accountID"]) || !$_SESSION["accountID"]) exit(header("Location: ../login/login.php"));

$username = ExploitPatch::remove($_POST["username"]);
$password = $_POST["password"];
$accountID = $_SESSION["accountID"];


//validation check and delete
if($username != "" AND $password != "" and $generatePass->isValid($accountID, $password)){
	$query = $db->query("DELETE FROM accounts WHERE accountID = '".$accountID."'");	
	$query->execute();
	$query = $db->query("DELETE FROM links WHERE accountID = '".$accountID."'");	
	$query->execute();
	$query = $db->query("DELETE FROM roleassign WHERE accountID = '".$accountID."'");	
	$query->execute();
	unlink("../../data/accounts/$accountID");
	unlink("../../data/accounts/keys/$accountID");
	header("Location: ../login/logout.php?deleted=ses");
}else{
	//Printing page
		$dl->printBox('
		<h1>'.$dl->getLocalizedString("deleteOwnAccount").'</h1>
				<form action="" method="post">
					<div class="form-group">
						<input required type="text" class="form-control" id="changePasswordUsername" name="username" value="'.$gs->getAccountName($_SESSION["accountID"]).'" placeholder="'.$dl->getLocalizedString("changePasswordUserNameFieldPlaceholder").'"><br>
						<input required autofocus type="password" class="form-control" name="password" value="'.$password.'" placeholder="'.$dl->getLocalizedString("password").'">
					</div>
					<h6 class="text-danger">'.$dl->getLocalizedString("deleteOwnAccountInfo").'</h6>
					<button type="submit" class="btn btn-danger btn-block">'.$dl->getLocalizedString("deleteOwnAccount").'</button>
				</form>',"account");
}
?>