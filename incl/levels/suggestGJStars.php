<?php
//error_reporting(0);
chdir(dirname(__FILE__));
include "../lib/connection.php";
require_once "../lib/GJPCheck.php";
require_once "../lib/exploitPatch.php";
require_once "../lib/mainLib.php";
$gs = new mainLib();

$gjp = ExploitPatch::remove($_POST["gjp"]);
$stars = ExploitPatch::remove($_POST["stars"]);
$feature = ExploitPatch::remove($_POST["feature"]);
$levelID = ExploitPatch::remove($_POST["levelID"]);
$accountID = GJPCheck::getAccountIDOrDie();
$difficulty = $gs->getDiffFromStars($stars);

if($gs->checkPermission($accountID, "actionRateStars")){
	$gs->rateLevel($accountID, $levelID, $stars, $difficulty["diff"], $difficulty["auto"], $difficulty["demon"]);
	$gs->featureLevel($accountID, $levelID, $feature);
	$gs->verifyCoinsLevel($accountID, $levelID, 1);
	echo 1;
	
//autoWeeklyAndDaily
$random = rand(1, 100);
$uploadDate = time();
if($random <= $autoWeeklyAndDailyChance and $autoWeeklyAndDaily == 'true' and $feature) {
//but if exists???
$query = $db->prepare("SELECT count(*) FROM dailyfeatures WHERE levelID = :level AND type = 1");
$query->execute([':level' => $levelID]);

if($stars >= 4 AND $stars < 9 AND $query->fetchColumn() < 1 AND $gs->checkPermission($accountID, "commandDaily")){
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
if($stars > 7 AND $query->fetchColumn() < 1 AND $gs->checkPermission($accountID, "commandWeekly")){
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

}else if($gs->checkPermission($accountID, "actionSuggestRating")){
	$gs->suggestLevel($accountID, $levelID, $difficulty["diff"], $stars, $feature, $difficulty["auto"], $difficulty["demon"]);
	echo 1;
}else{
	echo -2;
}
?>
