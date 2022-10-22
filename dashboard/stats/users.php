<?php
session_start();
//Requesting files
include "../../incl/lib/connection.php";
require_once "../incl/dashboardLib.php";
require_once "../../incl/lib/exploitPatch.php";
$dl = new dashboardLib();
$ep = new exploitPatch();
//Generating users table
if(isset($_GET["page"]) && is_numeric($_GET["page"]) && $_GET["page"] > 0){
	$page = ($ep->remove($_GET["page"]) - 1) * 10;
	$actualpage = $ep->remove($_GET["page"]);
}else{
	$page = 0;
	$actualpage = 1;
}
$usertable = "";
//Getting data
if (!empty($_GET["type"])) {
		$type = ExploitPatch::number($_GET['type']);
	} else {
		$type = 1;
	}
	switch ($type) {
		case 1:
			$order = "userID";
			$option1 = "selected";
			break;
		case 2:
			$order = "extID";
			$option2 = "selected";
			break;
		case 3:
			$order = "stars";
			$option3 = "selected";
			break;
		case 4:
			$order = "demons";
			$option4 = "selected";
			break;
		case 5:
			$order = "coins";
			$option5 = "selected";
			break;
		case 6:
			$order = "userCoins";
			$option6 = "selected";
			break;
		case 7:
			$order = "creatorPoints";
			$option7 = "selected";
			break;
		case 8:
			$order = "lastPlayed";
			$option8 = "selected";
			break;
		case 9:
			$order = "diamonds";
			$option9 = "selected";
			break;
		case 10:
			$order = "moons";
			$option10 = "selected";
			break;
		case 11:
			$order = "orbs";
			$option11 = "selected";
			break;
		case 12:
			$order = "completedLvls";
			$option12 = "selected";
			break;
		default:
			$order = "userID";
			$option1 = "selected";
			break;
	}
	if (isset($_GET['name'])) {
		$name = ExploitPatch::remove($_GET['name']);
	} else {
		$name = '';
	}

	
$query = $db->prepare("SELECT * FROM users WHERE isRegistered = '1' AND isBanned = '0' AND userName LIKE CONCAT('%', :name, '%') ORDER BY " . $order . " DESC LIMIT 10 OFFSET $page");
$query->execute([':name' => $name]);
$result = $query->fetchAll();
$query = $db->prepare("SELECT count(*) FROM users WHERE isRegistered = '1' AND isBanned = '0' AND userName LIKE CONCAT('%', :name, '%')");
$query->execute([':name' => $name]);
$usercount = $query->fetchColumn();
$x = $usercount - $page;
//Printing data
foreach($result as &$user){
	//Getting account data
	$usertable .= '<tr>
					<th scope="row">'.$x.'</th>
					<td><a href="GET/user.php?userID='.$user["userID"].'">'.$user["userID"].'</td>
					<td>'.$user["userName"].'</td>
					<td>'.$user["stars"].'</td>
					<td>'.$user["demons"].'</td>
					<td>'.$user["coins"].'</td>
					<td>'.$user["userCoins"].'</td>
					<td>'.$user["creatorPoints"].'</td>
					<td>'.$user["diamonds"].'</td>
				</tr>';
	$x--;
	echo "</td></tr>";
}
//Bottom row
$pagecount = ceil($usercount / 10);
$bottomrow = $dl->generateBottomRow($pagecount, $actualpage);
//Printing page
$dl->printFullPage('
<form action="" method="get" class="input-group mb-3" style="
    background-color: #fff;
    border: 1px solid rgba(0,0,0,.125);
    border-radius: 0.25rem 0.25rem 0 0;
    margin-bottom: 0rem!important;
    padding: 0.7rem;">
<input type="text" name="name" class="form-control" placeholder="'.$name.'" value="'.$name.'">
<select name="type" style="border: 1px solid rgba(0,0,0,0.15);width: 200px;text-align: center;">
<option '.$option1.' value="1">'.$dl->getLocalizedString("orderBy").' ID</option>
<option '.$option2.' value="2">'.$dl->getLocalizedString("orderBy").' account ID</option>
<option '.$option3.' value="3">'.$dl->getLocalizedString("orderBy").' stars</option>
<option '.$option4.' value="4">'.$dl->getLocalizedString("orderBy").' demons</option>
<option '.$option5.' value="5">'.$dl->getLocalizedString("orderBy").' coins</option>
<option '.$option6.' value="6">'.$dl->getLocalizedString("orderBy").' userCoins</option>
<option '.$option7.' value="7">'.$dl->getLocalizedString("orderBy").' creatorPoints</option>
<option '.$option8.' value="8">'.$dl->getLocalizedString("orderBy").' lastPlayed</option>
<option '.$option9.' value="9">'.$dl->getLocalizedString("orderBy").' diamonds</option>
<option '.$option10.' value="10">'.$dl->getLocalizedString("orderBy").' moons (wha)</option>
<option '.$option11.' value="11">'.$dl->getLocalizedString("orderBy").' orbs</option>
<option '.$option12.' value="12">'.$dl->getLocalizedString("orderBy").' completedLvls</option>
</select>
<input class="btn btn-primary" style="border-radius: 0 0.25rem 0.25rem 0;" type="submit" value="Search">
</form>
<table class="table table-inverse table-responsive">
	<thead>
		<tr>
			<th>#</th>
			<th>'.$dl->getLocalizedString("userID").'</th>
			<th>'.$dl->getLocalizedString("userName").'</th>
			<th>'.$dl->getLocalizedString("stars").'</th>
			<th>'.$dl->getLocalizedString("demons").'</th>
			<th>'.$dl->getLocalizedString("coins").'</th>
			<th>'.$dl->getLocalizedString("userCoins").'</th>
			<th>'.$dl->getLocalizedString("creatorPoints").'</th>
			<th>'.$dl->getLocalizedString("diamonds").'</th>
		</tr>
	</thead>
	<tbody>
		'.$usertable.'
	</tbody>
</table>'
.$bottomrow, true, "browse");
?>