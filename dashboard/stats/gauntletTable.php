<?php
session_start();
//Requesting files
include "../../incl/lib/connection.php";
require_once "../incl/dashboardLib.php";
require_once "../../incl/lib/mainLib.php";
$gs = new mainLib();
$dl = new dashboardLib();
//Generating gauntlet table
if(isset($_GET["page"]) && is_numeric($_GET["page"]) && $_GET["page"] > 0){
	$page = ($ep->remove($_GET["page"]) - 1) * 10;
	$actualpage = $ep->remove($_GET["page"]);
}else{
	$page = 0;
	$actualpage = 1;
}
$x = $page + 1;
$gauntlettable = "";
//Getting data
$query = $db->prepare("SELECT * FROM gauntlets ORDER BY ID ASC LIMIT 10 OFFSET $page");
$query->execute();
$gauntlets = $query->fetchAll();
foreach($gauntlets as &$gauntlet){
	//Getting levels
	for ($y = 1; $y < 6; $y++) $lvlarray[] = $gauntlet["level".$y];
	$gauntlettable .= "<tr>
					<th scope='row'>$x</th>
					<td>".$gs->getGauntletName($gauntlet["ID"])."</td>
					<td>
					<a href='GET/level.php?levelID=".$gauntlet["level1"]."'>".$gauntlet["level1"]."</a>, 
					<a href='GET/level.php?levelID=".$gauntlet["level2"]."'>".$gauntlet["level2"]."</a>, 
					<a href='GET/level.php?levelID=".$gauntlet["level3"]."'>".$gauntlet["level3"]."</a>, 
					<a href='GET/level.php?levelID=".$gauntlet["level4"]."'>".$gauntlet["level4"]."</a>, 
					<a href='GET/level.php?levelID=".$gauntlet["level5"]."'>".$gauntlet["level5"]."</a>
					</td>
					</tr>";
	$x++;
	echo "</td></tr>";
}
//Getting count
$query = $db->prepare("SELECT count(*) FROM gauntlets");
$query->execute();
$gauntletCount = $query->fetchColumn();
$pageCount = ceil($gauntletCount / 10);
//Bottom row
$bottomRow = $dl->generateBottomRow($pageCount, $actualpage);
//Printing page
$dl->printPage('<table class="table table-inverse table-responsive">
  <thead>
    <tr>
      <th>#</th>
      <th>'.$dl->getLocalizedString("name").'</th>
      <th>'.$dl->getLocalizedString("levels").'</th>
    </tr>
  </thead>
  <tbody>
    '.$gauntlettable.'
  </tbody>
</table>'
.$bottomRow, true, "browse");
?>