<?php
include "../../incl/lib/connection.php";
include_once "../../config/security.php";
require "../../incl/lib/generatePass.php";
require_once "../../incl/lib/exploitPatch.php";
include_once "../../incl/lib/defuse-crypto.phar";
require_once "../incl/dashboardLib.php";
require_once "../../incl/lib/mainLib.php";
$dl = new dashboardLib();
$gs = new mainLib();
use Defuse\Crypto\KeyProtectedByPassword;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;
//redicret if not logined
if(!isset($_SESSION["accountID"]) || !$_SESSION["accountID"]) exit(header("Location: ../login/login.php"));
$urlWas = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$urlWas = explode('?', $urlWas); $_SESSION["urlWas"] = $urlWas[0];

$userName = ExploitPatch::remove($_POST["username"]);
$oldpass = $_POST["oldPassword"];
$newpass = $_POST["newPassword"];

$username = ExploitPatch::remove($_POST["username"]);
$oldPassword = $_POST["oldPassword"];
$newPassword = $_POST["newPassword"];

$salt = "";


if($userName != "" AND $newpass != "" AND $oldpass != ""){
$pass = GeneratePass::isValidUsrname($userName, $oldpass);
if ($pass == 1) {
	//creating pass hash
	$passhash = password_hash($newpass, PASSWORD_DEFAULT);
	$query = $db->prepare("UPDATE accounts SET password=:password, salt=:salt WHERE userName=:userName");	
	$query->execute([':password' => $passhash, ':userName' => $userName, ':salt' => $salt]);
	GeneratePass::assignGJP2($accid, $pass);
	//Printing page
	$dl->printBox('<h1>'.$dl->getLocalizedString("changePassword").'</h1>
				<form action="" method="post">
					<div class="form-group">
						<input required type="text" class="form-control" id="changePasswordUsername" name="username" value="'.$gs->getAccountName($_SESSION["accountID"]).'" placeholder="'.$dl->getLocalizedString("changePasswordUserNameFieldPlaceholder").'"><br>
						<input required autofocus type="password" class="form-control" id="changePasswordPassword" name="oldPassword" value="'.$oldPassword.'" placeholder="'.$dl->getLocalizedString("changePasswordOldPasswordFieldPlaceholder").'"><br>
						<input required autofocus minlength="6" type="password" class="form-control is-valid" id="changeUsernameNewPassword" name="newPassword" value="'.$newPassword.'" placeholder="'.$dl->getLocalizedString("changePasswordNewPasswordFieldPlaceholder").'">
					    <div class="valid-feedback">'.$dl->getLocalizedString("passwordChanged").'</div>
					</div>
					<button type="submit" class="btn btn-primary btn-block">'.$dl->getLocalizedString("changeBTN").'</button>
				</form>',"account");
	//decrypting save
	$query = $db->prepare("SELECT accountID FROM accounts WHERE userName=:userName");	
	$query->execute([':userName' => $userName]);
	$accountID = $query->fetchColumn();
	$saveData = file_get_contents("../../data/accounts/$accountID");
	if(file_exists("../../data/accounts/keys/$accountID")){
		$protected_key_encoded = file_get_contents("../../data/accounts/keys/$accountID");
		if($protected_key_encoded != ""){
			$protected_key = KeyProtectedByPassword::loadFromAsciiSafeString($protected_key_encoded);
			$user_key = $protected_key->unlockKey($oldpass);
			try {
				$saveData = Crypto::decrypt($saveData, $user_key);
			} catch (Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException $ex) {
				exit("Unable to update save data encryption");	
			}
			file_put_contents("../../data/accounts/$accountID",$saveData);
			file_put_contents("../../data/accounts/keys/$accountID","");
		}
	}
}else{
	//Printing error
		$errorDesc = $dl->getLocalizedString("changePasswordError-1");
		exit($dl->printBox('
		<h1>'.$dl->getLocalizedString("changePassword").'</h1>
				<form action="" method="post">
					<div class="form-group">
						<input required type="text" class="form-control" id="changePasswordUsername" name="username" value="'.$gs->getAccountName($_SESSION["accountID"]).'" placeholder="'.$dl->getLocalizedString("changePasswordUserNameFieldPlaceholder").'"><br>
						<input required autofocus type="password" class="form-control is-invalid" id="changePasswordPassword" name="oldPassword" value="'.$oldPassword.'" placeholder="'.$dl->getLocalizedString("changePasswordOldPasswordFieldPlaceholder").'">
                        <div class="invalid-feedback">'.$errorDesc.'</div><br>
						<input required autofocus type="password" class="form-control" id="changeUsernameNewPassword" name="newPassword" value="'.$newPassword.'" placeholder="'.$dl->getLocalizedString("changePasswordNewPasswordFieldPlaceholder").'">
					</div>
					<button type="submit" class="btn btn-primary btn-block">'.$dl->getLocalizedString("tryAgainBTN").'</button>
				</form>',"account"));

}
}else{
    //Printing page
	$dl->printBox('<h1>'.$dl->getLocalizedString("changePassword").'</h1>
				<form action="" method="post">
					<div class="form-group">
						<input required maxlength="30" minlength="6" type="text" class="form-control" id="changePasswordUsername" name="username" value="'.$gs->getAccountName($_SESSION["accountID"]).'" placeholder="'.$dl->getLocalizedString("changePasswordUserNameFieldPlaceholder").'"><br>
						<input required maxlength="30" minlength="6" autofocus type="password" class="form-control" id="changePasswordPassword" name="oldPassword" value="'.$oldPassword.'" placeholder="'.$dl->getLocalizedString("changePasswordOldPasswordFieldPlaceholder").'"><br>
						<input required maxlength="30" minlength="6" type="password" class="form-control" id="changeUsernameNewPassword" name="newPassword" value="'.$newPassword.'" placeholder="'.$dl->getLocalizedString("changePasswordNewPasswordFieldPlaceholder").'">
					</div>
					<button type="submit" class="btn btn-primary btn-block">'.$dl->getLocalizedString("changeBTN").'</button>
				</form>',"account");
}
?>
