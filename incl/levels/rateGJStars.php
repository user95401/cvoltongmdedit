<?php
chdir(dirname(__FILE__));
include "../lib/connection.php";
require_once "../lib/GJPCheck.php";
require_once "../lib/exploitPatch.php";
$ep = new exploitPatch();
require_once "../lib/mainLib.php";
$gs = new mainLib();
$gjp = $ep->remove($_POST["gjp"]);
$stars = $ep->remove($_POST["stars"]);
$levelID = $ep->remove($_POST["levelID"]);
$accountID = $ep->remove($_POST["accountID"]);
if($accountID != "" AND $gjp != ""){
	$GJPCheck = new GJPCheck();
	$gjpresult = $GJPCheck->check($gjp,$accountID);
	if($gjpresult == 1){
		$permState = $gs->checkPermission($accountID, "actionRateStars");
		if($permState){
			$difficulty = $gs->getDiffFromStars($stars);
			$gs->rateLevel($accountID, $levelID, 0, $difficulty["diff"], $difficulty["auto"], $difficulty["demon"]);
			echo 1;
$random = rand(1, 100);
$uploadDate = time();
if($random <= $autoWeeklyAndDailyChance AND $autoWeeklyAndDaily == 'true') {
if($stars >= 4 AND $stars < 9 AND $gs->checkPermission($accountID, "commandDaily")){
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
}
if($stars > 8 AND $gs->checkPermission($accountID, "commandWeekly")){
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
		}else{
			echo -1;
		}
	}else{echo -1;}
}else{echo -1;}