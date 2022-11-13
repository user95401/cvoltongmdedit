<?php
//Requesting files
include "../../incl/lib/connection.php";
require_once "../incl/dashboardLib.php";
require_once "../../incl/lib/mainLib.php";
require_once "../../incl/lib/exploitPatch.php";
$gs = new mainLib();
$dl = new dashboardLib();
$ep = new exploitPatch();
//redicret if not logined
$urlWas = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$urlWas = explode('?', $urlWas); $_SESSION["urlWas"] = $urlWas[0];
if(!isset($_SESSION["accountID"]) || !$_SESSION["accountID"]) exit(header("Location: ../login/login.php"));
//Getting form data
if(isset($_GET["page"]) && is_numeric($_GET["page"]) && $_GET["page"] > 0){
	$page = ($ep->remove($_GET["page"]) - 1) * 10;
	$actualPage = $ep->remove($_GET["page"]);
}else{
	$page = 0;
	$actualPage = 1;
}

if (!empty($_GET["type"])) {
		$type = ExploitPatch::number($_GET['type']);
	} else {
		$type = 1;
	}
	switch ($type) {
		case 1:
			$order = "levelID";
			$option1 = "selected";
			break;
		case 2:
			$order = "starStars";
			$option2 = "selected";
			break;
		case 3:
			$order = "downloads";
			$option3 = "selected";
			break;
		case 4:
			$order = "objects";
			$option4 = "selected";
			break;
		case 5:
			$order = "uploadDate";
			$option5 = "selected";
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

//Generating unlisted table
$table = '
<form action="" method="get" class="input-group mb-3" style="
    background-color: #fff;
    border: 1px solid rgba(0,0,0,.125);
    border-radius: 0.25rem 0.25rem 0 0;
    margin-bottom: 0rem!important;
    padding: 0.7rem;">
<input type="text" name="name" class="form-control" placeholder="'.$name.'" value="'.$name.'">
<select name="type" style="border: 1px solid rgba(0,0,0,0.15);width: 162px;text-align: center;">
<option '.$option1.' value="1">'.$dl->getLocalizedString("orderBy").' '.$dl->getLocalizedString("ID").'</option>
<option '.$option2.' value="2">'.$dl->getLocalizedString("orderBy").' '.$dl->getLocalizedString("stars").'</option>
<option '.$option3.' value="3">'.$dl->getLocalizedString("orderBy").' '.$dl->getLocalizedString("downloads").'</option>
<option '.$option4.' value="4">'.$dl->getLocalizedString("orderBy").' '.$dl->getLocalizedString("objects").'</option>
<option '.$option5.' value="5">'.$dl->getLocalizedString("orderBy").' '.$dl->getLocalizedString("uploaded").'</option>
</select>
<input class="btn btn-primary" style="border-radius: 0 0.25rem 0.25rem 0;" type="submit" value="Search">
</form>
<table class="table table-inverse table-responsive test">
			<thead>
				<tr>
					<th scope="col">'.$dl->getLocalizedString("ID").'</th>
					<th scope="col">'.$dl->getLocalizedString("name").'</th>
					<th scope="col">'.$dl->getLocalizedString("stars").'</th>
					<th scope="col">'.$dl->getLocalizedString("userCoins").'</th>
					<th scope="col">'.$dl->getLocalizedString("downloads").'</th>
					<th scope="col">'.$dl->getLocalizedString("objects").'</th>
			        <th scope="col">'.$dl->getLocalizedString("uploaded").'</th>
					<th scope="col">'.$dl->getLocalizedString("password").'</th>
				</tr>
			</thead>
			<tbody>';
//Getting unlisted level
$userName = $gs->getAccountName($_SESSION["accountID"]);
$query = $db->prepare("SELECT * FROM levels WHERE extID = :extID AND unlisted = 1 AND levelName LIKE CONCAT('%', :name, '%') ORDER BY " . $order . " DESC LIMIT 10 OFFSET $page");
$query->execute([":extID" => $_SESSION["accountID"], ":name" => $name]);
$levels = $query->fetchAll();
foreach($levels as &$level){
	//Getting level data
    if($level["password"] == 0) {$password = '-';} else {$password = $level["password"];}
	$table .= "<tr>
				<td scope='row'>".$level["levelID"]."</td>
				<td scope='row'>".$level["levelName"]."</td>
				<td scope='row'>".$level["starStars"]."</td>
				<td scope='row'>".$level["coins"]."</td>
				<td scope='row'>".$level["downloads"]."</td>
				<td scope='row'>".$level["objects"]."</td>
				<td scope='row'>".$dl->convertToDate($level["uploadDate"])." ago</td>
				<td scope='row' class='blur-hover'>".$password."</td>
			</tr>";
}
$table .= "</tbody></table>";
//Getting count
$query = $db->prepare("SELECT count(*) FROM levels WHERE extID = :extID AND unlisted = 1 AND levelName LIKE CONCAT('%', :name, '%')");
$query->execute([":extID" => $_SESSION["accountID"], ":name" => $name]);
$unlistedCount = $query->fetchColumn();
$pageCount = ceil($unlistedCount / 10);

if ($pageCount == 0) {
//Printing error
	$errorDesc = $dl->getLocalizedString("errorNoUnlistedLvls");
	exit($dl->printBox('<h1>'.$dl->getLocalizedString("unlistedLevels")."</h1>
					<p>$errorDesc</p>
					<a class='btn btn-primary btn-block' href='stats/levels.php?name=$userName&type=2'>".$dl->getLocalizedString("showYourLevels")."</a>","account"));
}

//Bottom row
$bottomRow = $dl->generateBottomRow($pageCount, $actualPage);
$dl->printPage($table . $bottomRow, true, "account");
?>