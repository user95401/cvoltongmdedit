<?php
session_start();
//redicret if not logined
$urlWas = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$urlWas = explode('?', $urlWas); $_SESSION["urlWas"] = $urlWas[0];
if(!isset($_SESSION["accountID"]) || !$_SESSION["accountID"]) exit(header("Location: ../login/login.php"));
//Requesting files
include "../../incl/lib/connection.php";
require_once "../incl/dashboardLib.php";
$dl = new dashboardLib();
require_once "../../incl/lib/mainLib.php";
$gs = new mainLib();
//minecraft
$accountID = $_SESSION["accountID"];
$uploadDate = time();
$levelID = $_GET["levelID"];
//LEVELINFO
$query2 = $db->prepare("SELECT extID FROM levels WHERE levelID = :id");
$query2->execute([':id' => $levelID]);
$targetExtID = $query2->fetchColumn();

if($gs->ownCheck("delete", $accountID, $targetExtID) and $_GET["action"] == "delete"){
    $query = $db->prepare("DELETE from levels WHERE levelID=:levelID LIMIT 1");
    $query->execute([':levelID' => $levelID]);
    $query = $db->prepare("INSERT INTO modactions (type, value, value3, timestamp, account) VALUES ('6', :value, :levelID, :timestamp, :id)");
    $query->execute([':value' => "1", ':timestamp' => $uploadDate, ':id' => $accountID, ':levelID' => $levelID]);
    if(file_exists(dirname(__FILE__)."../../data/levels/$levelID")){
        rename(dirname(__FILE__)."../data/levels/$levelID",dirname(__FILE__)."../data/levels/deleted/$levelID");
    }
    //Printing card feedback
	exit($dl->printBox('<h1>'.$dl->getLocalizedString("delete")." <b class='text-success'>✓</b></h1>
					<p>command was successfully performed</p>
					<a class='btn btn-primary btn-block' href='javascript:history.back()'>OK</a>",""));
}
if($gs->ownCheck("public", $accountID, $targetExtID) and $_GET["action"] == "public"){
			$query = $db->prepare("UPDATE levels SET unlisted='0' WHERE levelID=:levelID");
			$query->execute([':levelID' => $levelID]);
			$query = $db->prepare("INSERT INTO modactions (type, value, value3, timestamp, account) VALUES ('12', :value, :levelID, :timestamp, :id)");
			$query->execute([':value' => "0", ':timestamp' => $uploadDate, ':id' => $accountID, ':levelID' => $levelID]);
    //Printing card feedback
	exit($dl->printBox('<h1>'.$dl->getLocalizedString("public")." <b class='text-success'>✓</b></h1>
					<p>command was successfully performed</p>
					<a class='btn btn-primary btn-block' href='javascript:history.back()'>OK</a>",""));
		}
if($gs->ownCheck("unlist", $accountID, $targetExtID) and $_GET["action"] == "unlist"){
			$query = $db->prepare("UPDATE levels SET unlisted='1' WHERE levelID=:levelID");
			$query->execute([':levelID' => $levelID]);
			$query = $db->prepare("INSERT INTO modactions (type, value, value3, timestamp, account) VALUES ('12', :value, :levelID, :timestamp, :id)");
			$query->execute([':value' => "0", ':timestamp' => $uploadDate, ':id' => $accountID, ':levelID' => $levelID]);
    //Printing card feedback
	exit($dl->printBox('<h1>'.$dl->getLocalizedString("unlist")." <b class='text-success'>✓</b></h1>
					<p>command was successfully performed</p>
					<a class='btn btn-primary btn-block' href='javascript:history.back()'>OK</a>",""));
		}
else {//Printing error
	$errorDesc = $dl->getLocalizedString("errorNoPerm");
	exit($dl->printBox('<h1>'.$dl->getLocalizedString("errorGeneric")."</h1>
					<p>$errorDesc</p>
					<a class='btn btn-primary btn-block' href='javascript:history.back()'>".$dl->getLocalizedString("tryAgainBTN")."</a>","mod"));}
?>
