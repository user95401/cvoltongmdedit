<?php
//Requesting files
include "../../incl/lib/connection.php";
include "../../config/defaults.php";
require_once "../incl/dashboardLib.php";
require_once "../../incl/lib/XORCipher.php";
require "../../config/reuploadAcc.php";
require_once "../../incl/lib/mainLib.php";
$gs = new mainLib();
if($preventAddingNewData == 'true') {exit ('<div '.$styleForErrorAlert.'>Adding new data disabled by the administrator!</div>');}
$dl = new dashboardLib();
$xc = new XORCipher();
//redicret if not logined
$urlWas = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$urlWas = explode('?', $urlWas); $_SESSION["urlWas"] = $urlWas[0];
if(!isset($_SESSION["accountID"]) || !$_SESSION["accountID"]) exit(header("Location: ../login/login.php"));
//Checking if levelToGD not true
if($levelReupload !== 'true')
{exit ('<div '.$styleForErrorAlert.'>levelReupload tool is disabled by the administrator!</div>');}
function chkarray($source, $default = 0){
	if($source == ""){
		$target = $default;
	}else{
		$target = $source;
	}
	return $target;
}
if(!empty($_POST["levelid"])){
	$levelID = $_POST["levelid"];
	$levelID = preg_replace("/[^0-9]/", '', $levelID);
	$url = $_POST["server"];
	$post = ['gameVersion' => '21', 'binaryVersion' => '33', 'gdw' => '0', 'levelID' => $levelID, 'secret' => 'Wmfd2893gb7', 'inc' => '1', 'extras' => '0'];
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	curl_setopt($ch, CURLOPT_PROTOCOLS, CURLPROTO_HTTP | CURLPROTO_HTTPS);
	$result = curl_exec($ch);
	curl_close($ch);
	if($result == "" OR $result == "-1" OR $result == "No no no"){
		if($result==""){
			//Printing error
			exit($dl->printBox('<h1>'.$dl->getLocalizedString("levelReupload")."</h1>
				<p>".$dl->getLocalizedString("levelToGDError")."</p>
				<a class='btn btn-primary btn-block' href='".$_SERVER["REQUEST_URI"]."'>".$dl->getLocalizedString("tryAgainBTN")."</a>","reupload"));
			
		}else if($result=="-1"){
			//Printing error
			$errorDesc = $dl->getLocalizedString("levelReuploadError-3");
			exit($dl->printBox('<h1>'.$dl->getLocalizedString("levelReupload")."</h1>
							<p>$errorDesc</p>
							<a class='btn btn-primary btn-block' href='".$_SERVER["REQUEST_URI"]."'>".$dl->getLocalizedString("tryAgainBTN")."</a>","reupload"));

		}else{
			//Printing error
			$errorDesc = $dl->getLocalizedString("linkAccountError-3");
			exit($dl->printBox('<h1>'.$dl->getLocalizedString("levelReupload")."</h1>
							<p>$errorDesc</p>
							<a class='btn btn-primary btn-block' href='".$_SERVER["REQUEST_URI"]."'>".$dl->getLocalizedString("tryAgainBTN")."</a>","reupload"));

		}
		//Printing error
			exit($dl->printBox('<h1>'.$dl->getLocalizedString("levelReupload")."</h1>
							<p>".htmlspecialchars($result,ENT_QUOTES)."</p>
							<a class='btn btn-primary btn-block' href='".$_SERVER["REQUEST_URI"]."'>".$dl->getLocalizedString("tryAgainBTN")."</a>","reupload"));
				
	}else{
		$level = explode('#', $result)[0];
		$resultarray = explode(':', $level);
		$levelarray = array();
		$x = 1;
		foreach($resultarray as &$value){
			if ($x % 2 == 0) {
				$levelarray["a$arname"] = $value;
			}else{
				$arname = $value;
			}
			$x++;
		}
		//echo $result;
		if($levelarray["a4"] == ""){
			echo "An error has occured.<br>Error code: ".htmlspecialchars($result,ENT_QUOTES);
		}
		$uploadDate = time();
		//old levelString
		$levelString = chkarray($levelarray["a4"]);
		$gameVersion = chkarray($levelarray["a13"]);
		if(substr($levelString,0,2) == 'eJ'){
			$levelString = str_replace("_","/",$levelString);
			$levelString = str_replace("-","+",$levelString);
			$levelString = gzuncompress(base64_decode($levelString));
			if($gameVersion > 18){
				$gameVersion = 18;
			}
		}
		//check if exists
		$query = $db->prepare("SELECT count(*) FROM levels WHERE originalReup = :lvl OR original = :lvl");
		$query->execute([':lvl' => $levelarray["a1"]]);
		if($query->fetchColumn() == 0){
			$parsedurl = parse_url($url);
			if($parsedurl["host"] == $_SERVER['SERVER_NAME']){
				exit("You're attempting to reupload from the target server.");
			}
			$hostname = $gs->getIP();
			//values
			$twoPlayer = chkarray($levelarray["a31"]);
			$songID = chkarray($levelarray["a35"]);
			$coins = chkarray($levelarray["a37"]);
			$reqstar = chkarray($levelarray["a39"]);
			$extraString = chkarray($levelarray["a36"], "");
			$starStars = chkarray($levelarray["a18"]);
			$isLDM = chkarray($levelarray["a40"]);
			$password = chkarray($levelarray["a27"]);
			if($password != "0"){
				$password = XORCipher::cipher(base64_decode($password),26364);
			}
			$starCoins = 0;
			$starDiff = 0;
			$starDemon = 0;
			$starAuto = 0;
			if($parsedurl["host"] == "www.boomlings.com"){
				if($starStars != 0){
					$starCoins = chkarray($levelarray["a38"]);
					$starDiff = chkarray($levelarray["a9"]);
					$starDemon = chkarray($levelarray["a17"]);
					$starAuto = chkarray($levelarray["a25"]);
				}
			}else{
				$starStars = 0;
			}
			$targetUserID = chkarray($levelarray["a6"]);
			//linkacc
			$query = $db->prepare("SELECT accountID, userID FROM links WHERE targetUserID=:target AND server=:url");
			$query->execute([':target' => $targetUserID, ':url' => $parsedurl["host"]]);
			if($query->rowCount() == 0){
				$userID = $reupUID;
				$extID = $reupAID;
			}else{
				$userInfo = $query->fetchAll()[0];
				$userID = $userInfo["userID"];
				$extID = $userInfo["accountID"];
			}
			//query
			$query = $db->prepare("INSERT INTO levels (levelName, gameVersion, binaryVersion, userName, levelDesc, levelVersion, levelLength, audioTrack, auto, password, original, twoPlayer, songID, objects, coins, requestedStars, extraString, levelString, levelInfo, secret, uploadDate, updateDate, originalReup, userID, extID, unlisted, hostname, starStars, starCoins, starDifficulty, starDemon, starAuto, isLDM)
												VALUES (:name ,:gameVersion, '27', 'Reupload', :desc, :version, :length, :audiotrack, '0', :password, :originalReup, :twoPlayer, :songID, '0', :coins, :reqstar, :extraString, :levelString, '', '', '$uploadDate', '$uploadDate', :originalReup, :userID, :extID, '".$_POST["unlisted"]."', :hostname, :starStars, :starCoins, :starDifficulty, :starDemon, :starAuto, :isLDM)");
			$query->execute([':password' => $password, ':starDemon' => $starDemon, ':starAuto' => $starAuto, ':gameVersion' => $gameVersion, ':name' => $levelarray["a2"], ':desc' => $levelarray["a3"], ':version' => $levelarray["a5"], ':length' => $levelarray["a15"], ':audiotrack' => $levelarray["a12"], ':twoPlayer' => $twoPlayer, ':songID' => $songID, ':coins' => $coins, ':reqstar' => $reqstar, ':extraString' => $extraString, ':levelString' => "", ':originalReup' => $levelarray["a1"], ':hostname' => $hostname, ':starStars' => $starStars, ':starCoins' => $starCoins, ':starDifficulty' => $starDiff, ':userID' => $userID, ':extID' => $extID, ':isLDM' => $isLDM]);
			$levelID = $db->lastInsertId();
			file_put_contents("../../data/levels/$levelID",$levelString);
			//Printing box
			$dl->printBox("<h1>".$dl->getLocalizedString("levelReupload")."</h1>
							<p>".sprintf($dl->getLocalizedString("levelReuploaded"), "<a href='GET/level.php?levelID=".$levelID."'>$levelID</a>").'<br>
							<button style="padding: 0px;" type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#Debug">Debug</button>
							</p>'."
							<a class='btn btn-primary btn-block' href='".$_SERVER["REQUEST_URI"]."'>".$dl->getLocalizedString("levelReuploadAnotherBTN")."</a>","reupload");
		}else{
			//Printing error
			$errorDesc = sprintf($dl->getLocalizedString("levelReuploadError-1"),$levelarray["a2"]);
			exit($dl->printBox('<h1>'.$dl->getLocalizedString("levelReupload")."</h1>
							<p>$errorDesc</p>
							<a class='btn btn-primary btn-block' href='".$_SERVER["REQUEST_URI"]."'>".$dl->getLocalizedString("tryAgainBTN")."</a>","reupload"));

		}
	}
}else{
    //Printing page
	$dl->printBox('<h1>'.$dl->getLocalizedString("levelReupload").'</h1>
				<form action="" method="post">
					<div class="form-group">
						<input type="number" min="1" autofocus class="form-control" id="urlField" name="levelid" placeholder="'.$dl->getLocalizedString("levelReuploadIDFieldPlaceholder").'"><br>
						<input type="text" required pattern="https?://.+" class="form-control" id="urlField" name="server" value="http://www.boomlings.com/database/downloadGJLevel22.php" placeholder="'.$dl->getLocalizedString("levelReuploadServerFieldPlaceholder").'">
						As unlisted? (0=off, 1=on): <input type="number" min="0" max="1" name="unlisted" value="0" style="width: 33px;border-width: 0px;cursor: alias;background: transparent;"><br>
						
					</div>
					<h6><a class="text-danger" href="account/linkAcc.php">'.$dl->getLocalizedString("levelReuploadLinkingAccWarn").'</a></h6>
					<button type="submit" class="btn btn-primary btn-block">'.$dl->getLocalizedString("reuploadBTN").'</button>
				</form>',"reupload");
}
?>
<div class="modal fade" id="Debug" tabindex="-1" aria-labelledby="Debug" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen modal-dialog-scrollable" style="width: 100vw;max-width: none;height: 100%;margin: 0;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title h4" id="exampleModalFullscreenLabel">Debug</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php 
        echo $result;
		var_dump($levelarray); 
		?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>