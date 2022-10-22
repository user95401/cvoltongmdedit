<?php
session_start();
//Requesting files
include "../../incl/lib/connection.php";
require_once "../incl/dashboardLib.php";
require_once "../../incl/lib/mainLib.php";
require_once "../../incl/lib/exploitPatch.php";
$gs = new mainLib();
$dl = new dashboardLib();
$ep = new exploitPatch();
//Generating levels table
if(isset($_GET["page"]) && is_numeric($_GET["page"]) && $_GET["page"] > 0){
	$page = ($ep->remove($_GET["page"]) - 1) * 10;
	$actualPage = $ep->remove($_GET["page"]);
}else{
	$page = 0;
	$actualPage = 1;
}
$leveltable = "";
//Getting data
if (isset($_GET['type']) == true) {
		$type = ExploitPatch::number($_GET['type']);
	} else {$type = 2;}
	switch ($type) {
		case 1:$searchType = "levelName";
			break;
		case 2:$searchType = "userName";
			break;
		default:$searchType = "levelName";
			break;
	}
	if (isset($_GET['name']) == true) {
	$name = ExploitPatch::remove($_GET['name']);}else {$name = '';}

$unlisted = 'AND unlisted = 0';
if($gs->checkPermission($_SESSION["accountID"], "dashboardModTools")){$unlisted = '';}

$query = $db->prepare("SELECT * FROM levels WHERE " . $searchType . " LIKE CONCAT('%', :name, '%') $unlisted ORDER BY uploadDate DESC LIMIT 10 OFFSET $page");
$query->execute([':name' => $name]);
$levels = $query->fetchAll();
$query = $db->prepare("SELECT count(*) FROM levels WHERE " . $searchType . " LIKE CONCAT('%', :name, '%')");
$query->execute([':name' => $name]);
$levelCount = $query->fetchColumn();
$x = $levelCount - $page;
//Printing data
foreach($levels as &$level){
	$leveltable .= '<tr>
					<th scope="row">'.$x.'</th>
					<td><a href="GET/level.php?levelID='.$level["levelID"].'">'.$level["levelID"].'</a></th>
					<td>'.$level["levelName"].'</td>
					<td>'.$gs->getUserName($level["userID"]).'</td>
					<td>'.$level["starStars"].'</td>
					<td>'.$level["coins"].'</td>
					<td>'.$dl->convertToDate($level["uploadDate"]).' ago</td>
				</tr>';
	$x--;
	echo "</td></tr>";
}
//Bottom row
$pageCount = ceil($levelCount / 10);
$bottomRow = $dl->generateBottomRow($pageCount, $actualPage);
//Printing page
$dl->printFullPage('
<form action="" method="get" class="input-group mb-3" style="
    background-color: #fff;
    border: 1px solid rgba(0,0,0,.125);
    border-radius: 0.25rem 0.25rem 0 0;
    margin-bottom: 0rem!important;
    padding: 0.7rem;">
<input type="text" name="name" class="form-control" placeholder="'.$name.'" value="'.$name.'">
<select name="type" style="border: 1px solid rgba(0,0,0,0.15);width: 120px;text-align: center;">
<option value="1">Name</option>
<option value="2">Author</option>
</select>
<input class="btn btn-primary" style="border-radius: 0 0.25rem 0.25rem 0;" type="submit" value="Search">
</form>
<table class="table table-inverse table-responsive">
	<thead>
		<tr>
			<th>#</th>
			<th>'.$dl->getLocalizedString("levelID").'</th>
			<th>'.$dl->getLocalizedString("name").'</th>
			<th>'.$dl->getLocalizedString("author").'</th>
			<th>'.$dl->getLocalizedString("stars").'</th>
			<th>'.$dl->getLocalizedString("userCoins").'</th>
			<th>'.$dl->getLocalizedString("uploaded").'</th>
		</tr>
	</thead>
	<tbody>
		'.$leveltable.'
	</tbody>
</table>'
.$bottomRow, true, "browse");
?>