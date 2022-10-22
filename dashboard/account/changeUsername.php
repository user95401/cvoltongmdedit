<?php
//Checking if logged in
session_start();
if(!isset($_SESSION["accountID"]) || !$_SESSION["accountID"]) exit(header("Location: ../login/login.php"));
//Requesting files
include "../../config/email.php";
include "../../incl/lib/connection.php";
require_once "../incl/dashboardLib.php";
require_once "../../incl/lib/mainLib.php";
require_once "../../incl/lib/exploitPatch.php";
require_once "../../incl/lib/generatePass.php";
$gs = new mainLib();
$ep = new exploitPatch();
$dl = new dashboardLib();
$generatePass = new generatePass();
//Checking nothing's empty
if(isset($_POST["username"]) && isset($_POST["newUsername"]) && isset($_POST["password"])){
	//Getting form data
	$username = $ep->remove($_POST["username"]);
	$baseUsername = base64_encode($username);
	$newUsername = $ep->remove($_POST["newUsername"]);
	$baseNewUsername = base64_encode($newUsername);
	$password = $ep->remove($_POST["password"]);
	$basePassword = base64_encode($password);
	//Checking pass
	if ($generatePass->isValidUsrname($username, $password) AND strlen($baseUsername) < 20 AND strlen($baseNewUsername) < 20) {
		//Checking if username exists
		$query = $db->prepare("SELECT username FROM accounts WHERE userName = :username LIMIT 1");
		$query->execute([':username' => $newUsername]);
		if($query->rowCount() > 0){
			//Printing error
			$errorDesc = sprintf($dl->getLocalizedString("changeUsernameError-2"), $newUsername);
			exit($dl->printBox('<h1>'.$dl->getLocalizedString("changeUsername").'</h1>
		<form action="" method="post">
					<div class="form-group">
						<input required maxlength="20" type="text" class="form-control is-invalid" id="changeUsernameUserName" name="username" value="'.$gs->getAccountName($_SESSION["accountID"]).'" placeholder="'.$dl->getLocalizedString("changeUsernameUserNameFieldPlaceholder").'">
						<div class="invalid-feedback">'.$errorDesc.'</div><br>
						<input required autofocus maxlength="20" type="text" class="form-control" id="changeUsernameNewUserName" name="newUsername" value="'.$newUsername.'" placeholder="'.$dl->getLocalizedString("changeUsernameNewUserFieldPlaceholder").'"><br>
						<input required type="password" class="form-control" id="changeUsernamePassword" name="password" value="'.$password.'" placeholder="'.$dl->getLocalizedString("changeUsernamePasswordFieldPlaceholder").'">
					</div>
					<button type="submit" class="btn btn-primary btn-block">'.$dl->getLocalizedString("tryAgainBTN").'</button>
				</form>',"account"));
		}
			//Updating username
			$query = $db->prepare("UPDATE acccomments SET userName = :newUsername WHERE userName = :username");
			$query->execute([':newUsername' => $newUsername, ':username' => $username]);
			$query = $db->prepare("UPDATE comments SET userName = :newUsername WHERE userName = :username");
			$query->execute([':newUsername' => $newUsername, ':username' => $username]);
			$query = $db->prepare("UPDATE levels SET userName = :newUsername WHERE userName = :username");
			$query->execute([':newUsername' => $newUsername, ':username' => $username]);
			$query = $db->prepare("UPDATE messages SET userName = :newUsername WHERE userName = :username");
			$query->execute([':newUsername' => $newUsername, ':username' => $username]);
			$query = $db->prepare("UPDATE users SET userName = :newUsername WHERE userName = :username");
			$query->execute([':newUsername' => $newUsername, ':username' => $username]);
			$query = $db->prepare("UPDATE accounts SET userName = :newUsername WHERE userName = :username");	
			$query->execute([':newUsername' => $newUsername, ':username' => $username]);
			if(!$query->rowCount()){
				//Printing error
				$errorDesc = $dl->getLocalizedString("changeUsernameError-1");
				exit($dl->printBox('<h1>'.$dl->getLocalizedString("changeUsername").'</h1>
		<form action="" method="post">
					<div class="form-group">
						<input required maxlength="20" type="text" class="form-control" id="changeUsernameUserName" name="username" value="'.$gs->getAccountName($_SESSION["accountID"]).'" placeholder="'.$dl->getLocalizedString("changeUsernameUserNameFieldPlaceholder").'"><br>
						<input required autofocus maxlength="20" type="text" class="form-control" id="changeUsernameNewUserName" name="newUsername" value="'.$newUsername.'" placeholder="'.$dl->getLocalizedString("changeUsernameNewUserFieldPlaceholder").'"><br>
						<input required type="password" class="form-control is-invalid" id="changeUsernamePassword" name="password" value="'.$password.'" placeholder="'.$dl->getLocalizedString("changeUsernamePasswordFieldPlaceholder").'">
						<div class="invalid-feedback">'.$errorDesc.'</div>
					</div>
					<button type="submit" class="btn btn-primary btn-block">'.$dl->getLocalizedString("tryAgainBTN").'</button>
				</form>',"account"));
			}else{
				$dl->printBox('<h1>'.$dl->getLocalizedString("changeUsername").'</h1>
				<form action="" method="post">
					<div class="form-group">
						<input required maxlength="20" type="text" class="form-control" id="changeUsernameUserName" name="username" value="'.$gs->getAccountName($_SESSION["accountID"]).'" placeholder="'.$dl->getLocalizedString("changeUsernameUserNameFieldPlaceholder").'"><br>
						<input required autofocus maxlength="20" type="text" class="form-control is-valid" id="changeUsernameNewUserName" name="newUsername" placeholder="'.$dl->getLocalizedString("changeUsernameNewUserFieldPlaceholder").'">
						<div class="valid-feedback">'.sprintf($dl->getLocalizedString("usernameChanged"), $newUsername).'</div><br>
						<input required type="password" class="form-control" id="changeUsernamePassword" name="password" value="'.$password.'" placeholder="'.$dl->getLocalizedString("changeUsernamePasswordFieldPlaceholder").'">
					</div>
					<button type="submit" class="btn btn-primary btn-block">'.$dl->getLocalizedString("changeBTN").'</button>
				</form>',"account");
			}
	}else{
		//Printing error
		$errorDesc = $dl->getLocalizedString("changeUsernameError-1");
		exit($dl->printBox('<h1>'.$dl->getLocalizedString("changeUsername").'</h1>
		<form action="" method="post">
					<div class="form-group">
						<input required maxlength="20" type="text" class="form-control" id="changeUsernameUserName" name="username" value="'.$gs->getAccountName($_SESSION["accountID"]).'" placeholder="'.$dl->getLocalizedString("changeUsernameUserNameFieldPlaceholder").'"><br>
						<input required autofocus maxlength="20" type="text" class="form-control" id="changeUsernameNewUserName" name="newUsername" value="'.$newUsername.'" placeholder="'.$dl->getLocalizedString("changeUsernameNewUserFieldPlaceholder").'"><br>
						<input required type="password" class="form-control is-invalid" id="changeUsernamePassword" name="password" value="'.$password.'" placeholder="'.$dl->getLocalizedString("changeUsernamePasswordFieldPlaceholder").'">
						<div class="invalid-feedback">'.$dl->getLocalizedString("errorGeneric").' '.$errorDesc.'</div>
					</div>
					<button type="submit" class="btn btn-primary btn-block">'.$dl->getLocalizedString("tryAgainBTN").'</button>
				</form>',"account"));
	}
}else{
	//Printing page
	$dl->printBox('<h1>'.$dl->getLocalizedString("changeUsername").'</h1>
				<form action="" method="post">
					<div class="form-group">
						<input required maxlength="20" type="text" class="form-control" id="changeUsernameUserName" name="username" value="'.$gs->getAccountName($_SESSION["accountID"]).'" placeholder="'.$dl->getLocalizedString("changeUsernameUserNameFieldPlaceholder").'"><br>
						<input required autofocus maxlength="20" type="text" class="form-control" id="changeUsernameNewUserName" name="newUsername" value="'.$newUsername.'" placeholder="'.$dl->getLocalizedString("changeUsernameNewUserFieldPlaceholder").'"><br>
						<input required type="password" class="form-control" id="changeUsernamePassword" name="password" value="'.$password.'" placeholder="'.$dl->getLocalizedString("changeUsernamePasswordFieldPlaceholder").'">
					</div>
					<button type="submit" class="btn btn-primary btn-block">'.$dl->getLocalizedString("changeBTN").'</button>
				</form>',"account");
}
?>