<?php
session_start();
include "../../incl/lib/connection.php";
require_once "../incl/dashboardLib.php";
require_once "../../incl/lib/mainLib.php";
require_once "../../incl/lib/exploitPatch.php";
$gs = new mainLib();
$dl = new dashboardLib();
$ep = new exploitPatch();
//Generating pack table
if(isset($_GET["page"]) && is_numeric($_GET["page"]) && $_GET["page"] > 0){
	$page = ($ep->remove($_GET["page"]) - 1) * 10;
	$actualPage = $ep->remove($_GET["page"]);
}else{
	$page = 0;
	$actualPage = 1;
}
$packtable = "";
$x = $page + 1;
//Getting map packs
$query = $db->prepare("SELECT * FROM mappacks ORDER BY ID ASC LIMIT 10 OFFSET $page");
$query->execute();
$result = $query->fetchAll();
foreach($result as &$pack){
	//Getting data
	$lvltable;
	$lvlarray = explode(",", $pack["levels"]);
	$packtable .= "<tr>
					<th scope='row'>$x</th>
					<td><b style='color: rgb(".$pack["rgbcolors"].")'>".htmlspecialchars($pack["name"],ENT_QUOTES)."</b></td>
					<td>".$pack["stars"]."</td>
					<td>".$pack["coins"]."</td>
					<td>".$pack["levels"]."</td>
					</tr>";
	$x++;
}
//Getting count
$query = $db->prepare("SELECT count(*) FROM mappacks");
$query->execute();
$packCount = $query->fetchColumn();
$pageCount = ceil($packCount / 10);
//Bottom row
$bottomRow = $dl->generateBottomRow($pageCount, $actualPage);
//Printing page
$dl->printPage('<table class="table table-inverse table-responsive">
  <thead>
    <tr>
      <th>#</th>
      <th>'.$dl->getLocalizedString("name").'</th>
      <th>'.$dl->getLocalizedString("stars").'</th>
      <th>'.$dl->getLocalizedString("coins").'</th>
	  <th>'.$dl->getLocalizedString("levels").'</th>
    </tr>
  </thead>
  <tbody>
    '.$packtable.'
  </tbody>
</table>'
.$bottomRow, true, "browse");
?>