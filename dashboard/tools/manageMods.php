<?php
//Requesting files
include "../../incl/lib/connection.php";
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
if(!$gs->checkPermission($_SESSION["accountID"], "adminTools")){
	//Printing errors
	$errorDesc = $dl->getLocalizedString("errorNoPerm");
	exit($dl->printBox('<h1>'.$dl->getLocalizedString("errorGeneric")."</h1>
					<p>$errorDesc</p>
					<a class='btn btn-primary btn-block' href='".$_SERVER["REQUEST_URI"]."'>".$dl->getLocalizedString("tryAgainBTN")."</a>","mod"));
}

//posts
$change_roleID = $ep->remove($_POST["change-roleID"]);
$change_accountID = $ep->remove($_POST["change-accountID"]);
$delete_assignID = $ep->remove($_POST["delete-assignID"]);
$delete_accountID = $ep->remove($_POST["delete-accountID"]);
$create_roleID = $ep->remove($_POST["create-roleID"]);
$create_accountID = $ep->remove($_POST["create-accountID"]);


//deleteAssign
if(!empty($delete_assignID)){
    $query = $db->prepare("DELETE FROM roleassign WHERE assignID = '".$delete_assignID."'");	
	$query->execute();
}
//deleteAssigns
if(!empty($delete_accountID)){
    $query = $db->prepare("DELETE FROM roleassign WHERE accountID = '".$delete_accountID."'");	
	$query->execute();
}
//createAssign
if(!empty($create_roleID) and !empty($create_accountID)){
    $query = $db->prepare("INSERT INTO roleassign (roleID, accountID) VALUES (".$create_roleID.", ".$create_accountID.")");	
	$query->execute();
}
//change
if(!empty($change_roleID) and !empty($change_accountID)){
    $query = $db->prepare("UPDATE `roleassign` SET `roleID`=".$change_roleID.",`accountID`=".$change_accountID." WHERE assignID = '".$_POST["change-assignID"]."'");	
	$query->execute();
}
//Generating roleassigns table
	if(isset($_GET["page"]) && is_numeric($_GET["page"]) && $_GET["page"] > 0){
		$page = ($ep->remove($_GET["page"]) - 1) * 10;
		$actualPage = $ep->remove($_GET["page"]);
	}else{
		$page = 0;
		$actualPage = 1;
	}
//Getting data
    if($_GET['order'] != "assignID" and $_GET['order'] != "roleID" and $_GET['order'] != "accountID") {$ORDER = 'roleID';} else {$ORDER = $_GET['order'];}
	$query = $db->prepare("SELECT * FROM roleassign ORDER BY " . $ORDER . " DESC LIMIT 10 OFFSET $page");
	$query->execute();
	$assigns = $query->fetchAll();
	$query = $db->prepare("SELECT count(*) FROM roleassign");
	$query->execute();
	$assignsCount = $query->fetchColumn();
	$x = $assignsCount - $page;
	$roleassignsTable = "";
	//Printing data
	foreach($assigns as &$assign){
		//get userID id
		$query = $db->prepare("SELECT userID FROM users WHERE extID = :extID LIMIT 1");
		$query->execute([':extID' => $assign["accountID"]]); $userID = $query->fetchColumn();
		if(empty($userID)) {$userID = "<b class='text-danger'>ACCOUNT HAS NO USER!";}
		$roleassignsTable .= '<tr>
						<th scope="row">'.$x.'</th>
						<form action="" method="post" id="change-'.$assign["assignID"].'">
						<td>'.$assign["assignID"].'</td>
						<input type="hidden" name="change-assignID" value="'.$assign["assignID"].'">
						<td>
						<input type="number" min="0" name="change-roleID" value="'.$assign["roleID"].'" style="border-width: 0px;cursor: alias;background: transparent;width: '.strlen($assign["roleID"]).'.8rem">
						</td>
						<td><input type="number" min="0" name="change-accountID" value="'.$assign["accountID"].'" style="border-width: 0px;cursor: alias;background: transparent;width: '.strlen($assign["accountID"]).'.8em"></td>
						<td><a href="GET/user.php?userID='.$userID.'" target="_blank">'.$userID.'</a></td>
						<td>
						<button type="submit" class="btn btn-primary btn-sm" style="background-color: #0069d9!important;border-color: #0062cc;!important">'.$dl->getLocalizedString("change").'</button>
						</form>
						<button onclick="deleteAssign()" class="btn btn-danger btn-sm"><i class="fa fa-trash" style="font-size: 19px"></i></button>
						<form action="" method="post" id="delete-'.$assign["assignID"].'">
						<input type="hidden" name="delete-assignID" value="'.$assign["assignID"].'">
						</form>
						<script>function deleteAssign(){document.getElementById("delete-'.$assign["assignID"].'").submit();}</script>
						</td>
					</tr>';
		$x--;
		echo "</td></tr>";
	}
	//Bottom row
	$pageCount = ceil($assignsCount / 10);
	$bottomRow = $dl->generateBottomRow($pageCount, $actualPage);
	//Printing page
	if(empty($_SERVER['QUERY_STRING'])){$urlprefixor = $_SERVER['REQUEST_URI'].'?';}else{$urlprefixor = $_SERVER['REQUEST_URI'].'&';}
	$dl->printPage('<table class="table table-inverse table-responsive">
		<thead>
			<tr>
				<th>#</th>
				<th><a href="'.$urlprefixor.'order=assignID">'.$dl->getLocalizedString("assignID").'</a></th>
				<th><a href="'.$urlprefixor.'order=roleID">'.$dl->getLocalizedString("roleID").'</a></th>
				<th><a href="'.$urlprefixor.'order=accountID">'.$dl->getLocalizedString("accountID").'</a></th>
				<th>'.$dl->getLocalizedString("userID").'</th>
				<th>'.$dl->getLocalizedString("action").'</th>
			</tr>
		</thead>
		<tbody>
			'.$roleassignsTable.'
		</tbody>
	</table>'
	.$bottomRow, true, "mod");
    $query = $db->prepare("SELECT * FROM roles");
	$query->execute();
	$roles = $query->fetchAll();
	//Printing data
    $roles_options = '';
	foreach($roles as &$role){
		$roles_options .= '<option value="'.$role["roleID"].'">'.$role["roleID"].' ('.$role["roleName"].')</option>';
	}
echo '<div class="container">
<div class="row">
		<div class="col-md-6 mt-1">
		    <div class="darkBox">
		    <h1>'.$dl->getLocalizedString("deleteAssign").'</h1>
		    <form action="" method="post" id="assignID">
					<div class="form-group">
						<label>'.$dl->getLocalizedString("assignID").'</label>
						<input type="number" class="form-control bg-dark text-light" name="delete-assignID">
						<label>'.$dl->getLocalizedString("accountIDdeleteAssigns").'</label>
						<input type="number" class="form-control bg-dark text-light" name="delete-accountID">
					</div>
					<button type="submit" class="btn btn-outline-danger btn-block">'.$dl->getLocalizedString("deleteAssign").'</button>
				</form>
		    </div>
		</div>
		<div class="col-md-6 mt-1">
		    <div class="darkBox">
		    <h1>'.$dl->getLocalizedString("createAssign").'</h1>
		    <form action="" method="post" id="createAssign">
					<div class="form-group">
						<label>'.$dl->getLocalizedString("roleID").'</label>
						<select name="create-roleID" class="form-control bg-dark text-light">'.$roles_options.'</select>
						<label>'.$dl->getLocalizedString("accountID").'</label>
						<input required type="number" class="form-control bg-dark text-light" name="create-accountID">
					</div>
					<input type="submit" class="btn btn-outline-info btn-block">
				</form>
		    </div>
		</div>
</div>
</div class="container">';
?>