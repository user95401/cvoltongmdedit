<?php
include "../../incl/lib/connection.php";
require_once "../incl/dashboardLib.php";
require_once "../../incl/lib/mainLib.php";
require_once "../../incl/lib/exploitPatch.php";
$gs = new mainLib();
$dl = new dashboardLib();
$ep = new exploitPatch();
//Checking if logged in
session_start();
if(!isset($_SESSION["accountID"]) || !$_SESSION["accountID"]) exit(header("Location: ../login/login.php"));
//Checking permissions
if(!$gs->checkPermission($_SESSION["accountID"], "toolSuggestlist")){
	//Printing errors
	$errorDesc = $dl->getLocalizedString("errorNoPerm");
	exit($dl->printBox('<h1>'.$dl->getLocalizedString("errorGeneric")."</h1>
					<p>$errorDesc</p>
					<a class='btn btn-primary btn-block' href='".$_SERVER["REQUEST_URI"]."'>".$dl->getLocalizedString("tryAgainBTN")."</a>","mod"));
}
//Generating reports table
if(isset($_GET["page"]) && is_numeric($_GET["page"]) && $_GET["page"] > 0){
	$page = ($ep->remove($_GET["page"]) - 1) * 10;
	$actualPage = $ep->remove($_GET["page"]);
}else{
	$page = 0;
	$actualPage = 1;
}
$array = array();
$rateSuggestionsTable = "";
			$query = $db->prepare("SELECT suggestBy,suggestLevelId,suggestDifficulty,suggestStars,suggestFeatured,suggestAuto,suggestDemon,timestamp FROM suggest ORDER BY timestamp DESC LIMIT 10 OFFSET $page");
			$query->execute();
			$result = $query->fetchAll();
//Bottom row
$query = $db->prepare("SELECT count(*) FROM suggest");
$query->execute();
$rateSuggestionsTable = $query->fetchColumn();
$pageCount = ceil($rateSuggestionsTable / 10);
$bottomRow = $dl->generateBottomRow($pageCount, $actualPage);
//Printing page
$dl->printPage('<table class="table table-inverse table-responsive">
<thead><tr><th>'.$dl->getLocalizedString('time').'</th><th>'.$dl->getLocalizedString('Suggested by').'</th><th>'.$dl->getLocalizedString('ID').'</th><th>'.$dl->getLocalizedString('Difficulty').'</th><th>'.$dl->getLocalizedString('stars').'</th><th>'.$dl->getLocalizedString('Featured').'</th></tr>
</thead>
	<tbody>');
		foreach($result as &$sugg){
			echo "<tr><td>".date("d/m/Y G:i", $sugg["timestamp"])."</td><td>".$gs->getAccountName($sugg["suggestBy"])."(".$sugg["suggestBy"].")</td><td><a href='GET/level.php?levelID=".$sugg["suggestLevelId"]."'>".htmlspecialchars($sugg["suggestLevelId"],ENT_QUOTES)."</a></td><td>".htmlspecialchars($gs->getDifficulty($sugg["suggestDifficulty"],$sugg["suggestAuto"],$sugg["suggestDemon"]), ENT_QUOTES)."</td><td>".htmlspecialchars($sugg["suggestStars"],ENT_QUOTES)."</td><td>".htmlspecialchars($sugg["suggestFeatured"],ENT_QUOTES)."</td></tr>";
		}
		echo '</tbody></table>'.$bottomRow;
?>
