<?php
//Requesting files
include "../../incl/lib/connection.php";
require_once "../incl/dashboardLib.php";
require_once "../../incl/lib/mainLib.php";
require_once "../../incl/lib/exploitPatch.php";
$gs = new mainLib();
$ep = new exploitPatch();
$dl = new dashboardLib();
//redicret if not logined
$urlWas = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$urlWas = explode('?', $urlWas); $_SESSION["urlWas"] = $urlWas[0];
if(!isset($_SESSION["accountID"]) || !$_SESSION["accountID"]) exit(header("Location: ../login/login.php"));
//Checking permissions
if(!$gs->checkPermission($_SESSION["accountID"], "toolLeaderboardsban")){
	//Printing errors
	$errorDesc = $dl->getLocalizedString("errorNoPerm");
	exit($dl->printBox('<h1>'.$dl->getLocalizedString("errorGeneric")."</h1>
					<p>$errorDesc</p>
					<a class='btn btn-primary btn-block' href='".$_SERVER["REQUEST_URI"]."'>".$dl->getLocalizedString("tryAgainBTN")."</a>","mod"));
}

//Checking nothing's empty
if(!empty($_POST["userID"])){
	//Getting form data
	$userID = $ep->remove($_POST["userID"]);
	//Checking if is numeric
	if(!is_numeric($userID)){
		//Printing error
		$errorDesc = $dl->getLocalizedString("banError-2").'🤭';
		exit($dl->printBox('<h1>'.$dl->getLocalizedString("leaderboardBan").'</h1>
		            <form action="" method="post">
					<div class="form-group">
						<input type="text" class="form-control is-invalid" id="banUserID" name="userID" placeholder="'.$dl->getLocalizedString("banUserIDFieldPlaceholder").'">
					<div class="invalid-feedback">'.$errorDesc.'</div>
					</div>
					<button type="submit" class="btn btn-primary btn-block">'.$dl->getLocalizedString("banBTN").'</button>
				</form>',"mod"));
	}
	//Checking if was already banned
	if($gs->isBanned($userID, "leaderboards")){
		//Printing error
		$errorDesc = $dl->getLocalizedString("banError-3");
		exit($dl->printBox('<h1>'.$dl->getLocalizedString("leaderboardBan").'</h1>
		            <form action="" method="post">
					<div class="form-group">
						<input autofocus required type="number" class="form-control is-invalid" id="banUserID" name="userID" placeholder="'.$dl->getLocalizedString("banUserIDFieldPlaceholder").'">
					<div class="invalid-feedback">'.$errorDesc.'</div>
					</div>
					<button type="submit" class="btn btn-primary btn-block">'.$dl->getLocalizedString("banBTN").'</button>
				</form>',"mod"));
	}
	//Banning user
	$query = $db->prepare("UPDATE users SET isBanned = 1 WHERE userID = :id");
	$query->execute([':id' => $userID]);
	if($query->rowCount() != 0){
		//Printing box
		$dl->printBox('<h1>'.$dl->getLocalizedString("leaderboardBan").'</h1>
		            <form action="" method="post">
					<div class="form-group">
						<input autofocus required type="number" class="form-control is-valid" id="banUserID" name="userID" placeholder="'.$dl->getLocalizedString("banUserIDFieldPlaceholder").'">
					<div class="valid-feedback">'.sprintf($dl->getLocalizedString("banned"), $userID).'</div>
					</div>
					<button type="submit" class="btn btn-primary btn-block">'.$dl->getLocalizedString("banBTN").'</button>
				</form>',"mod");
	}else{
		//Printing error
		$errorDesc = sprintf($dl->getLocalizedString("banError-1"), $userID);
		exit($dl->printBox('<h1>'.$dl->getLocalizedString("leaderboardBan").'</h1>
		            <form action="" method="post">
					<div class="form-group">
						<input autofocus required type="number" class="form-control is-invalid" id="banUserID" name="userID" placeholder="'.$dl->getLocalizedString("banUserIDFieldPlaceholder").'">
					<div class="invalid-feedback">'.$errorDesc.'</div>
					</div>
					<button type="submit" class="btn btn-primary btn-block">'.$dl->getLocalizedString("banBTN").'</button>
				</form>',"mod"));
	}
}else{
	//Printing page
	$dl->printBox('<h1>'.$dl->getLocalizedString("leaderboardBan").'</h1>
				<form action="" method="post">
					<div class="form-group">
						<input autofocus required type="number" class="form-control" id="banUserID" name="userID" placeholder="'.$dl->getLocalizedString("banUserIDFieldPlaceholder").'">
					</div>
					<button type="submit" class="btn btn-primary btn-block">'.$dl->getLocalizedString("banBTN").'</button>
				</form>',"mod");
}
?>