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
//Generating songs table
if(isset($_GET["page"]) && is_numeric($_GET["page"]) & $_GET["page"] > 0){
	$page = ($ep->remove($_GET["page"]) - 1) * 10;
	$actualpage = $ep->remove($_GET["page"]);
}else{
	$page = 0;
	$actualpage = 1;
}
$songtable = "";
//Getting data
if (!empty($_GET["type"])) {
		$type = ExploitPatch::number($_GET['type']);
	} else {
		$type = 1;
	}
	switch ($type) {
		case 1:
			$searchType = "name";
			break;
		case 2:
			$searchType = "authorName";
			break;
		default:
			$searchType = "name";
			break;
	}
	if (isset($_GET['name'])) {
		$name = ExploitPatch::remove($_GET['name']);
	} else {
		$name = '';
	}
$SORDER = $_GET['order'];
if(empty($SORDER)){$SORDER = "ID";}
$query = $db->prepare("SELECT * FROM songs WHERE isDisabled = '0' AND " . $searchType . " LIKE CONCAT('%', :name, '%') ORDER BY " . $SORDER . " DESC LIMIT 10 OFFSET $page");
$query->execute([':name' => $name]);
$result = $query->fetchAll();
$query = $db->prepare("SELECT count(*) FROM songs WHERE isDisabled = '0' AND " . $searchType . " LIKE CONCAT('%', :name, '%')");
$query->execute([':name' => $name]);
$songcount = $query->fetchColumn();
$x = $songcount - $page;
//Printing data
foreach($result as &$song){
$maxStrlen_name = 30;
$maxStrlen_authorName = 24;
if(strlen($song["name"])>$maxStrlen_name) {$songName = mb_substr($song["name"],0,$maxStrlen_name, 'UTF-8').'...';$tooltip_name='data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="'.$song["name"].'"';}else{$songName = $song["name"];$tooltip_name='';}
if(strlen($song["authorName"])>$maxStrlen_authorName) {$authorName = mb_substr($song["authorName"],0,$maxStrlen_authorName, 'UTF-8').'...';$tooltip_authorName='data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="'.$song["authorName"].'"';}else{$authorName = $song["authorName"];$tooltip_authorName='';}

	$songtable .= '<tr>
					<th scope="row">'.$x.'</th>
					<td><a href="GET/song.php?songID='.$song["ID"].'">'.$song["ID"].'</a></th>
					<td><span '.$tooltip_name.'>'.$songName.'</span></td>
					<td><span '.$tooltip_authorName.'>'.$authorName.'</span></td>
					<td>'.$song["size"].' MB</td>
					<td>'.$song["levelsCount"].'</td>
					<td><audio controls style="height: 30px;"><source src="'.$song["download"].'" type="audio/mpeg"></audio></td>
				</tr>';
	$x--;
	echo "</td></tr>";
}
//Bottom row
$pagecount = ceil($songcount / 10);
$bottomrow = $dl->generateBottomRow($pagecount, $actualpage);
$urlprefixor = $_SERVER['REQUEST_URI'].'&';
if(empty($_SERVER['QUERY_STRING'])){$urlprefixor = $_SERVER['REQUEST_URI'].'?';}
//Printing page
$dl->printPage('
<form action="" method="get" class="input-group mb-3" style="
    background-color: #fff;
    border: 1px solid rgba(0,0,0,.125);
    border-radius: 0.25rem 0.25rem 0 0;
    margin-bottom: 0rem!important;
    padding: 0.7rem;">
<input type="text" name="name" class="form-control" placeholder="'.$name.'" value="'.$name.'">
<select name="type" style="border: 1px solid rgba(0,0,0,0.15);width: 120px;text-align: center;">
<option value="1">Song Name</option>
<option value="2">Song Author</option>
</select>
<input class="btn btn-primary" style="border-radius: 0 0.25rem 0.25rem 0;" type="submit" value="Search">
</form>
<table class="table table-responsive table-inverse">
	<thead>
		<tr>
			<th scope="col">#</th>
			<th scope="col"><a href="'.$urlprefixor.'order=ID">'.$dl->getLocalizedString("ID").'</th></a>
			<th scope="col">'.$dl->getLocalizedString("name").'</th>
			<th scope="col">'.$dl->getLocalizedString("songAuthor").'</th>
			<th scope="col"><a href="'.$urlprefixor.'order=size">'.$dl->getLocalizedString("size").'</th></a>
			<th scope="col"><a href="'.$urlprefixor.'order=levelsCount">'.$dl->getLocalizedString("levelsCount").'</th></a>
			<th scope="col">'.$dl->getLocalizedString("Player").'</th>
		</tr>
	</thead>
	<tbody>
		'.$songtable.'
	</tbody>
</table>'
.$bottomrow, true, "browse");
?>
<script>
const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
</script>