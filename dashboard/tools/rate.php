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
$accountID = $_SESSION["accountID"];
$uploadDate = time();
$levelID = $_GET["levelID"];
if(!$gs->checkPermission($_SESSION["accountID"], "commandRate")){
	//Printing error
	$errorDesc = $dl->getLocalizedString("errorNoPerm");
	exit($dl->printBox('<h1>'.$dl->getLocalizedString("errorGeneric")."</h1>
					<p>$errorDesc</p>
					<a class='btn btn-primary btn-block' href='".$_SERVER["REQUEST_URI"]."'>".$dl->getLocalizedString("tryAgainBTN")."</a>","mod"));
}
if(!empty($_GET["levelID"]) and !empty($_POST["starStars"]) or !empty($_POST["diffArray"])){
$starStars=$_POST["starStars"];
if($starStars == ""){$starStars = 0;}
$starCoins = $_POST["starCoins"];
$starFeatured = $_POST["starFeatured"];
$starEpic = $_POST["starEpic"];
$diffArray = $gs->getDiffFromName($_POST["diffArray"]);
$starDemon = $diffArray[1]; $starAuto = $diffArray[2]; $starDifficulty = $diffArray[0];

$query = $db->prepare("UPDATE levels SET starCoins='0', starFeatured='0', starEpic='0', starStars=:starStars, starDifficulty=:starDifficulty, starDemon=:starDemon, starAuto=:starAuto, rateDate=:timestamp WHERE levelID=:levelID");
$query->execute([':starStars' => $starStars, ':starDifficulty' => $starDifficulty, ':starDemon' => $starDemon, ':starAuto' => $starAuto, ':timestamp' => $uploadDate, ':levelID' => $levelID]);
$query = $db->prepare("INSERT INTO modactions (type, value, value2, value3, timestamp, account) VALUES ('1', :value, :value2, :levelID, :timestamp, :id)");
$query->execute([':value' => $starStars, ':timestamp' => $uploadDate, ':id' => $accountID, ':value2' => $starStars, ':levelID' => $levelID]);
if($starFeatured != ""){
	$query = $db->prepare("INSERT INTO modactions (type, value, value3, timestamp, account) VALUES ('2', :value, :levelID, :timestamp, :id)");
	$query->execute([':value' => $starFeatured, ':timestamp' => $uploadDate, ':id' => $accountID, ':levelID' => $levelID]);	
	$query = $db->prepare("UPDATE levels SET starFeatured=:starFeatured WHERE levelID=:levelID");
	$query->execute([':starFeatured' => 1, ':levelID' => $levelID]);
}
if($starCoins != ""){
	$query = $db->prepare("INSERT INTO modactions (type, value, value3, timestamp, account) VALUES ('3', :value, :levelID, :timestamp, :id)");
	$query->execute([':value' => $starCoins, ':timestamp' => $uploadDate, ':id' => $accountID, ':levelID' => $levelID]);
	$query = $db->prepare("UPDATE levels SET starCoins=:starCoins WHERE levelID=:levelID");
	$query->execute([':starCoins' => 1, ':levelID' => $levelID]);
}
if($starEpic != ""){
	$query = $db->prepare("INSERT INTO modactions (type, value, value3, timestamp, account) VALUES ('3', :value, :levelID, :timestamp, :id)");
	$query->execute([':value' => $starCoins, ':timestamp' => $uploadDate, ':id' => $accountID, ':levelID' => $levelID]);
	$query = $db->prepare("UPDATE levels SET starEpic=:starEpic WHERE levelID=:levelID");
	$query->execute([':starEpic' => 1, ':levelID' => $levelID]);
}
//autoWeeklyAndDaily
$random = rand(1, 100);
$uploadDate = time();
if($random <= $autoWeeklyAndDailyChance and $autoWeeklyAndDaily == 'true' and $starFeatured != "") {
//but if exists???
$query = $db->prepare("SELECT count(*) FROM dailyfeatures WHERE levelID = :level AND type = 0");
$query->execute([':level' => $levelID]);
if($starStars >= 4 AND $starStars < 9 AND $query->fetchColumn() < 1 AND $gs->checkPermission($accountID, "commandDaily")){
	$query = $db->prepare("SELECT count(*) FROM dailyfeatures WHERE levelID = :level AND type = 0");
	$query->execute([':level' => $levelID]);
	$query = $db->prepare("SELECT timestamp FROM dailyfeatures WHERE timestamp >= :tomorrow AND type = 0 ORDER BY timestamp DESC LIMIT 1");
	$query->execute([':tomorrow' => strtotime("tomorrow 00:00:00")]);
	if($query->rowCount() == 0){
		$timestamp = strtotime("tomorrow 00:00:00");
	}else{
		$timestamp = $query->fetchColumn() + 86400;
	}
	$query = $db->prepare("INSERT INTO dailyfeatures (levelID, timestamp, type) VALUES (:levelID, :uploadDate, 0)");
		$query->execute([':levelID' => $levelID, ':uploadDate' => $timestamp]);
	$query = $db->prepare("INSERT INTO modactions (type, value, value3, timestamp, account, value2, value4) VALUES ('5', :value, :levelID, :timestamp, :id, :dailytime, 0)");
	$query->execute([':value' => "1", ':timestamp' => $uploadDate, ':id' => $accountID, ':levelID' => $levelID, ':dailytime' => $timestamp]);
	}
//but if exists???
$query = $db->prepare("SELECT count(*) FROM dailyfeatures WHERE levelID = :level AND type = 1");
$query->execute([':level' => $levelID]);
if($starStars > 8 AND $query->fetchColumn() < 1 AND $gs->checkPermission($accountID, "commandWeekly")){
    $query = $db->prepare("SELECT count(*) FROM dailyfeatures WHERE levelID = :level AND type = 1");
	$query->execute([':level' => $levelID]);
	$query = $db->prepare("SELECT timestamp FROM dailyfeatures WHERE timestamp >= :tomorrow AND type = 1 ORDER BY timestamp DESC LIMIT 1");
		$query->execute([':tomorrow' => strtotime("next monday")]);
	if($query->rowCount() == 0){
		$timestamp = strtotime("next monday");
	}else{
		$timestamp = $query->fetchColumn() + 604800;
	}
	$query = $db->prepare("INSERT INTO dailyfeatures (levelID, timestamp, type) VALUES (:levelID, :uploadDate, 1)");
	$query->execute([':levelID' => $levelID, ':uploadDate' => $timestamp]);
	$query = $db->prepare("INSERT INTO modactions (type, value, value3, timestamp, account, value2, value4) VALUES ('5', :value, :levelID, :timestamp, :id, :dailytime, 1)");
	$query->execute([':value' => "1", ':timestamp' => $uploadDate, ':id' => $accountID, ':levelID' => $levelID, ':dailytime' => $timestamp]);
    }
}
//Printing page
echo '';
if($gs->checkPermission($_SESSION["accountID"], "commandEpic")) $epicCheck = 'Epic: <input class="check" type="checkbox" name="starEpic" value="1" style="width: 1em;height: 1em;margin-top: 0.27em;"/>';
$dl->printBox('<h1>'.$dl->getLocalizedString("rate").'</h1>
<form action="" method="post">
    <div class="form-group">
        <div class="input-group mb-3"><span class="input-group-text">Difficulty: </span><select name="diffArray" class="form-select">
            <option value="">na</option>
            <option value="easy">easy</option>
            <option value="normal">normal</option>
            <option value="hard">hard</option>
            <option value="harder">harder</option>
            <option value="insane">insane</option>
            <option value="auto">auto</option>
            <option value="demon">demon</option>
          </select></div>
        <div class="input-group mb-3"><span class="input-group-text">Stars: </span><input autofocus type="number" class="form-control" name="starStars" placeholder=""></div>
        Coins: <input class="check" type="checkbox" name="starCoins" checked="checked" value="1" style="width: 1em;height: 1em;margin-top: 0.27em;"/>
        Featured: <input class="check" type="checkbox" name="starFeatured" value="1" style="width: 1em;height: 1em;margin-top: 0.27em;"/>
        '.$epicCheck.'
    </div>
    <div class="alert alert-success alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<h4>Successfully rated!</h4>
				Level id: '.$levelID.', stars: '.$starStars.', difficulty - '.$_POST["diffArray"].'.
			</div>
<button type="submit" class="btn btn-primary btn-block">'.$dl->getLocalizedString("rate").'</button>
</form>
',"mod");
}else{
//Printing page
if($gs->checkPermission($_SESSION["accountID"], "commandEpic")) $epicCheck = 'Epic: <input class="check" type="checkbox" name="starEpic" value="1" style="width: 1em;height: 1em;margin-top: 0.27em;"/>';
if(!empty($_GET["levelID"])){
$dl->printBox('<h1>'.$dl->getLocalizedString("rate").'</h1>
<form action="" method="post">
    <div class="form-group">
        <div class="input-group mb-3"><span class="input-group-text">Difficulty: </span><select name="diffArray" class="form-select">
            <option value="easy">easy</option>
            <option value="normal">normal</option>
            <option value="hard">hard</option>
            <option value="harder">harder</option>
            <option value="insane">insane</option>
            <option value="auto">auto</option>
            <option value="demon">demon</option>
          </select></div>
        <div class="input-group mb-3"><span class="input-group-text">Stars: </span><input autofocus type="number" class="form-control" name="starStars" placeholder=""></div>
        Coins: <input class="check" type="checkbox" name="starCoins" checked="checked" value="1" style="width: 1em;height: 1em;margin-top: 0.27em;"/>
        Featured: <input class="check" type="checkbox" name="starFeatured" value="1" style="width: 1em;height: 1em;margin-top: 0.27em;"/>
        '.$epicCheck.'
    </div>
<button type="submit" class="btn btn-primary btn-block">'.$dl->getLocalizedString("rate").'</button>
</form>',"mod");}else{
$dl->printBox('<h1>'.$dl->getLocalizedString("rate").'</h1>
<form action="" method="get">
    <div class="form-group">
        <div class="input-group mb-3"><span class="input-group-text">Level id: </span><input autofocus type="number" class="form-control" name="levelID" placeholder="ID pls"></div>
    </div>
<button type="submit" class="btn btn-primary btn-block">'.$dl->getLocalizedString("go").'</button>
</form>',"mod");
}
}
?>