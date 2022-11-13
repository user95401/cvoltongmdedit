<?php
//Requesting files
include "../../incl/lib/connection.php";
require_once "../../incl/lib/generatePass.php";
require_once "../../incl/lib/exploitPatch.php";
require_once "../incl/dashboardLib.php";
$dl = new dashboardLib();
$generatePass = new generatePass();
$ep = new exploitPatch();
//redicret if not logined
$urlWas = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$urlWas = explode('?', $urlWas); $_SESSION["urlWas"] = $urlWas[0];
if(!isset($_SESSION["accountID"]) || !$_SESSION["accountID"]) exit(header("Location: ../login/login.php"));
//Getting data
if(!empty($_POST["user"]) AND !empty($_POST["pass"])){
	$pass = $_POST["pass"];
	$username = $_POST["user"];
	$accountID = $_SESSION["accountID"];
	if ($generatePass->isValidUsrname($username, $pass)) {
	//update
	if(!empty($_POST["user"]) and !empty($_POST["pass"]) and $generatePass->isValidUsrname($username, $pass) and !empty($_POST["YouTube"]) or !empty($_POST["Twitter"]) or !empty($_POST["Twitch"])) {
    $query = $db->prepare("UPDATE accounts SET youtubeurl=:youtubeurl, twitter=:twitter, twitch=:twitch WHERE accountID=:accountID");
    $query->execute([':youtubeurl' => $_POST["YouTube"], ':accountID' => $accountID, ':twitch' => $_POST["Twitch"], ':twitter' => $_POST["Twitter"]]);
	}
$query = $db->prepare("SELECT * FROM accounts WHERE accountID = :id LIMIT 1");
$query->execute([':id' => $accountID]);
$result = $query->fetch();

	//Printing page
	$dl->printBox('<h1>'.$dl->getLocalizedString("account_settings").'</h1>
	            '.$dl->getLocalizedString("userName").': '.$result["userName"].'
	            <br>'.$dl->getLocalizedString("email").': <span class="blur-hover">'.$result["email"].'</span>
	            <br>'.$dl->getLocalizedString("ID").': '.$result["accountID"].'
	            <br>'.$dl->getLocalizedString("banned?").': '.$result["isBanned"].'
	            <br>'.$dl->getLocalizedString("registerDate").': '.$dl->convertToDate($result["registerDate"]).' ago
	            <br>IP: <span class="blur-hover">'.$result["ip"].'</span>
	            
				<form action="" method="post">
					<div class="form-group">
                        <div class="input-group mb-3">
                          <span class="input-group-text"><a href="http://youtube.com/channel/'.$result["youtubeurl"].'" target="_blank">YouTube:</span></a>
                            <input type="text" class="form-control" id="YouTube" name="YouTube" value="'.$result["youtubeurl"].'" placeholder="youtube.com/channel/...">
                        </div>
                        <div class="input-group mb-3">
                          <span class="input-group-text"><a href="http://twitter.com/'.$result["twitter"].'" target="_blank">Twitter:</span></a>
                            <input type="text" class="form-control" id="Twitter" name="Twitter" value="'.$result["twitter"].'" placeholder="twitter.com/...">
                        </div>
                        <div class="input-group mb-3">
                          <span class="input-group-text"><a href="http://twitch.tv/'.$result["twitch"].'" target="_blank">Twitch:</span></a>
                            <input type="text" class="form-control" id="Twitch" name="Twitch" value="'.$result["twitch"].'" placeholder="twitch.tv/...">
                        </div>
                        <a href="stats/users.php?name='.$result["userName"].'&type=1" target="_blank">'.$dl->getLocalizedString("users").'</a>
                         / 
                        <a href="stats/levels.php?name='.$result["userName"].'&type=2" target="_blank">'.$dl->getLocalizedString("levels").'</a>
                         / 
                        <a href="account/changePassword.php" target="_blank">'.$dl->getLocalizedString("changePassword").'</a>
                         / 
                        <a href="account/changeUsername.php" target="_blank">'.$dl->getLocalizedString("changeUsername").'</a>
                         / 
                        <a href="account/unlisted.php" target="_blank">'.$dl->getLocalizedString("unlistedLevels").'</a>
                        <input type="hidden" name="pass" value="'.$pass.'">
                        <input type="hidden" name="user" value="'.$username.'">
					</div>
					<button type="submit" class="btn btn-primary btn-block">'.$dl->getLocalizedString("update").'</button>
				</form>',"account");
	}else{
		//Printing error
		$errorDesc = $dl->getLocalizedString("errorPasswordsNotMatch");
				exit($dl->printBox('<h1>'.$dl->getLocalizedString("account_settings")."</h1>
								<p>$errorDesc</p>
								<a class='btn btn-primary btn-block' href='".$_SERVER["REQUEST_URI"]."'>".$dl->getLocalizedString("tryAgainBTN")."</a>","account"));
	}
}else{
	//Printing page
	$dl->printBox('<h1>'.$dl->getLocalizedString("account_settings").'</h1>
				<form action="" method="post">
					<div class="form-group">
                        <div class="form-floating">
                          <input required type="text" class="form-control" id="u" name="user" value="'.$_POST["user"].'">
                          <label for="u">'.$dl->getLocalizedString("userName").'</label>
                        </div>
						<br>
    					<div class="form-floating">
                          <input required type="password" class="form-control" id="p" name="pass" value="'.$_POST["pass"].'">
                          <label for="p">'.$dl->getLocalizedString("password").'</label>
                        </div>
					</div>
					<button type="submit" class="btn btn-primary btn-block">'.$dl->getLocalizedString("confirm").'</button>
				</form>',"account");
}
?>