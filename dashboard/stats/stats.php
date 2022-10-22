<?php
include "../../incl/lib/connection.php";
require_once "../incl/dashboardLib.php";
require_once "../../incl/lib/mainLib.php";
$gs = new mainLib();
$dl = new dashboardLib();

$start_time = microtime(true);

$dl->printPage('
<div class="accordion" id="statsAccordion">
 <div class="accordion-item">
<h1 class="accordion-header">
<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#Levels" aria-expanded="true" aria-controls="Levels">
'.$dl->getLocalizedString("Levels").'</h1>
</button>
    </h1>
    <div id="Levels" class="accordion-collapse collapse" aria-labelledby="Levels" data-bs-parent="#statsAccordion">
      <div class="accordion-body">
<table class="table table-responsive">
  <thead>
<tr><th>'.$dl->getLocalizedString("Difficulty").'</th><th>'.$dl->getLocalizedString("Total").'</th><th>'.$dl->getLocalizedString("Unrated").'</th><th>'.$dl->getLocalizedString("Rated").'</th><th>'.$dl->getLocalizedString("Featured").'</th><th>'.$dl->getLocalizedString("Epic").'</th></tr>
', true, "stats");

function genLvlRow($params, $params2, $params3, $params4){
include "../../incl/lib/connection.php";
	$query = $db->prepare("SELECT count(*) FROM levels ".$params4." ".$params2);
	$query->execute();
	$row = "<tr><td>$params3</td><td>".$query->fetchColumn()."</td>";
	$query = $db->prepare("SELECT count(*) FROM levels WHERE starStars = 0 ".$params." ".$params2);
	$query->execute();
	$row .= "<td>".$query->fetchColumn()."</td>";
	$query = $db->prepare("SELECT count(*) FROM levels WHERE starStars <> 0 ".$params." ".$params2);
	$query->execute();
	$row .= "<td>".$query->fetchColumn()."</td>";
	$query = $db->prepare("SELECT count(*) FROM levels WHERE starFeatured <> 0 ".$params." ".$params2);
	$query->execute();
	$row .= "<td>".$query->fetchColumn()."</td>";
	$query = $db->prepare("SELECT count(*) FROM levels WHERE starEpic <> 0 ".$params." ".$params2);
	$query->execute();
	$row .= "<td>".$query->fetchColumn()."</td></tr>";
	return $row;
}
//error_reporting(0);
echo genLvlRow("","","Total", "");
echo genLvlRow("AND","starDifficulty = 0 AND starDemon = 0 AND starAuto = 0 AND unlisted = 0", "N/A", "WHERE");
echo genLvlRow("AND","starAuto = 1  AND unlisted = 0", "Auto", "WHERE");
echo genLvlRow("AND","starDifficulty = 10 AND starDemon = 0 AND starAuto = 0 AND unlisted = 0", "Easy", "WHERE");
echo genLvlRow("AND","starDifficulty = 20 AND starDemon = 0 AND starAuto = 0 AND unlisted = 0", "Normal", "WHERE");
echo genLvlRow("AND","starDifficulty = 30 AND starDemon = 0 AND starAuto = 0 AND unlisted = 0", "Hard", "WHERE");
echo genLvlRow("AND","starDifficulty = 40 AND starDemon = 0 AND starAuto = 0 AND unlisted = 0", "Harder", "WHERE");
echo genLvlRow("AND","starDifficulty = 50 AND starDemon = 0 AND starAuto = 0 AND unlisted = 0", "Insane", "WHERE");
echo genLvlRow("AND","starDemon = 1", "Demon", "WHERE");
?>
</thead>
</table>
</div>
</div>
    </div>
<div class="accordion-item">
<h1 class="accordion-header">
<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#Demons" aria-expanded="false" aria-controls="Demons">
<?php echo $dl->getLocalizedString("Demons")?>
</button>
    </h1>
    <div id="Demons" class="accordion-collapse collapse" aria-labelledby="Demons" data-bs-parent="#statsAccordion">
      <div class="accordion-body">
<table class="table table-responsive">
  <thead>
<tr><th><?php echo $dl->getLocalizedString("Difficulty")?></th><th><?php echo $dl->getLocalizedString("Total")?></th><th><?php echo $dl->getLocalizedString("Unrated")?></th><th><?php echo $dl->getLocalizedString("Rated")?></th><th><?php echo $dl->getLocalizedString("Featured")?></th><th><?php echo $dl->getLocalizedString("Epic")?></th></tr>
<?php
echo genLvlRow("AND","starDemon = 1", "Total", "WHERE");
echo genLvlRow("AND","starDemon = 1 AND starDemonDiff = 3", "Easy", "WHERE");
echo genLvlRow("AND","starDemon = 1 AND starDemonDiff = 4", "Medium", "WHERE");
echo genLvlRow("AND","starDemon = 1 AND starDemonDiff = 0", "Hard", "WHERE");
echo genLvlRow("AND","starDemon = 1 AND starDemonDiff = 5", "Insane", "WHERE");
echo genLvlRow("AND","starDemon = 1 AND starDemonDiff = 6", "Extreme", "WHERE");
?>
</thead>
</table>
</div>
</div>
    </div>
<div class="accordion-item">
<h1 class="accordion-header">
<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#Accounts" aria-expanded="true" aria-controls="Accounts">
<?php echo $dl->getLocalizedString("Accounts")?>
</button>
    </h1>
    <div id="Accounts" class="accordion-collapse collapse show" aria-labelledby="Accounts" data-bs-parent="#statsAccordion">
      <div class="accordion-body">
<table class="table table-responsive">
  <thead>
<tr><th><?php echo $dl->getLocalizedString("Type")?></th><th><?php echo $dl->getLocalizedString("Count")?></th>
<?php
$query = $db->prepare("SELECT count(*) FROM users");
$query->execute();
$thing = $query->fetchColumn();
echo "<tr><td>".$dl->getLocalizedString('Total')."</td><td>$thing</td></tr>";
$query = $db->prepare("SELECT count(*) FROM accounts");
$query->execute();
$thing = $query->fetchColumn();
echo "<tr><td>".$dl->getLocalizedString("Registered")."</td><td>$thing</td></tr>";
$sevendaysago = time() - 604800;
$query = $db->prepare("SELECT count(*) FROM users WHERE lastPlayed > :lastPlayed");
$query->execute([':lastPlayed' => $sevendaysago]);
$thing = $query->fetchColumn();
echo "<tr><td>".$dl->getLocalizedString("Active")."</td><td>$thing</td></tr>";
?>
<thead>
</table>
</div>
    </div>
  </div>
</div>