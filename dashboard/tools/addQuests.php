<?php
//Requesting files
include "../../incl/lib/connection.php";
require "../../incl/lib/generatePass.php";
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
if(!$gs->checkPermission($_SESSION["accountID"], "toolQuestsCreate")){
	//Printing errors
	$errorDesc = $dl->getLocalizedString("errorNoPerm");
	exit($dl->printBox('<h1>'.$dl->getLocalizedString("errorGeneric")."</h1>
					<p>$errorDesc</p>
					<a class='btn btn-primary btn-block' href='".$_SERVER["REQUEST_URI"]."'>".$dl->getLocalizedString("tryAgainBTN")."</a>","mod"));
}

if(!empty($_POST["type"]) AND !empty($_POST["amount"]) AND !empty($_POST["reward"]) AND !empty($_POST["names"])){
	$type = ExploitPatch::number($_POST["type"]);
	$amount = ExploitPatch::number($_POST["amount"]);
    $reward = ExploitPatch::number($_POST["reward"]);
    $name = ExploitPatch::remove($_POST["names"]);
	if(!is_numeric($type) OR !is_numeric($amount) OR !is_numeric($reward) OR $type > 3){
	exit($dl->printBox('<h1>'.$dl->getLocalizedString("errorGeneric").'</h1>
	<p>'.$dl->getLocalizedString("Type/Amount/Reward invalid")."</p>
	<br><a class='btn btn-primary btn-block' href='".$_SERVER["REQUEST_URI"]."'>".$dl->getLocalizedString("tryAgainBTN")."</a>","mod"));
}
	$query = $db->prepare("INSERT INTO quests (type, amount, reward, name) VALUES (:type,:amount,:reward,:name)");
			$query->execute([':type' => $type, ':amount' => $amount, ':reward' => $reward, ':name' => $name]);
			if($db->lastInsertId() < 3) {
				exit($dl->printBox('<h1>'.$dl->getLocalizedString("Successfully added Quest!").'</h1>
	<p>'.$dl->getLocalizedString("It is recommended that you should add a few more.")."</p>
					<br><a class='btn btn-primary btn-block' href='".$_SERVER["REQUEST_URI"]."'>".$dl->getLocalizedString("addMoreBTN")."</a>","mod"));
			} else {
			exit($dl->printBox('<h1>'.$dl->getLocalizedString("Successfully added Quest!")."</h1>
					<br><a class='btn btn-primary btn-block' href='".$_SERVER["REQUEST_URI"]."'>".$dl->getLocalizedString("addMoreBTN")."</a>","mod"));
			}
		}
$dl->printBox('<div class="form-group"><form action="" method="post">
<h1>'.$dl->getLocalizedString("Add quest").'</h1>
'.$dl->getLocalizedString("Quest Type:").' <select name="type" class="form-select">
	<option value="1">Orbs</option>
	<option value="2">Coins</option>
	<option value="3">Star</option>
</select>
<br>'.$dl->getLocalizedString("Amount:").' <input autofocus required class="form-control" type="number" name="amount" placeholder="'.$dl->getLocalizedString("How many orbs/coins/stars you need to collect").'" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="'.$dl->getLocalizedString("How many orbs/coins/stars you need to collect").'">
<br>'.$dl->getLocalizedString("Reward:").' <input required class="form-control" type="number" name="reward" placeholder="'.$dl->getLocalizedString("How many Diamonds you get as a reward").'" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="'.$dl->getLocalizedString("How many Diamonds you get as a reward").'">
<br>'.$dl->getLocalizedString("Quest Name:").' <input required class="form-control" type="text" name="names">
<br><input class="btn btn-primary btn-block" type="submit" value="'.$dl->getLocalizedString("addBTN").'"></form></div class="form-group">',"mod");

?>
<script>
const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
</script>
<style>
    .card {
    width: 50%;
    min-width: 320px;
    max-width: 480px;
    }
</style>