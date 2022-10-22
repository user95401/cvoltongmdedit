<?php
//Checking if logged in
session_start();
if(!isset($_SESSION["accountID"]) OR $_SESSION["accountID"] == 0){
	header("Location: ../login/login.php");
	exit();
}
//Requesting files
include "../../incl/lib/connection.php";
require_once "../incl/dashboardLib.php";
require_once "../../incl/lib/mainLib.php";
require_once "../../incl/lib/exploitPatch.php";
$gs = new mainLib();
$dl = new dashboardLib();
$ep = new exploitPatch();
//Generating mod table
$modtable = "";
$accounts = implode(",",$gs->getAccountsWithPermission("toolModactions"));
if(!$accounts) exit($dl->printBox(sprintf($dl->getLocalizedString("errorNoAccWithPerm"), "toolsModactions")));
//Getting data
$query = $db->prepare("SELECT accountID, username FROM accounts WHERE accountID IN ($accounts) ORDER BY username ASC");
$query->execute();
$result = $query->fetchAll();
$row = 0;
foreach($result as &$mod){
	$row++;
	$query = $db->prepare("SELECT lastPlayed FROM users WHERE extID = :id");
	$query->execute([':id' => $mod["accountID"]]);
	$time = "".$dl->convertToDate($query->fetchColumn())." ago";
	$query = $db->prepare("SELECT count(*) FROM modactions WHERE account = :id");
	$query->execute([':id' => $mod["accountID"]]);
	$actionscount = $query->fetchColumn();
	$query = $db->prepare("SELECT count(*) FROM modactions WHERE account = :id AND type = '1'");
	$query->execute([':id' => $mod["accountID"]]);
	$lvlcount = $query->fetchColumn();
	$modtable .= "<tr>
					<th scope='row'>".$row."</th>
					<td>".$mod["username"]."</td>
					<td>".$actionscount."</td>
					<td>".$lvlcount."</td>
					<td>".$time."</td>
				</tr>";
}
//Printing page
$dl->printPage('<table class="table table-inverse table-responsive">
  <thead>
    <tr>
      <th>#</th>
      <th>'.$dl->getLocalizedString("mod").'</th>
      <th>'.$dl->getLocalizedString("count").'</th>
      <th>'.$dl->getLocalizedString("ratedLevels").'</th>
	<th>'.$dl->getLocalizedString("lastSeen").'</th>
    </tr>
  </thead>
  <tbody>
    '.$modtable.'
  </tbody>
</table>', true, "stats");
//log button test 
if ($gs->checkPermission($_SESSION["accountID"], "toolModactions")) {
echo '<button style="border-radius: 0.5rem 0 0 0;" type="button" class="btn btn-primary btn-lg position-absolute bottom-0 end-0" data-bs-toggle="modal" data-bs-target="#Debug">Actions Log</button>
';}
?>
<div class="modal fade" id="Debug" tabindex="-1" aria-labelledby="Debug" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen modal-dialog-scrollable" style="width: 100vw;max-width: none;height: 100%;margin: 0;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title h4" id="exampleModalFullscreenLabel">Actions Log</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <table class="table table-hover">
              <thead class="table-light"><tr><th scope="col">Moderator</th><th scope="col">Action</th><th>Value</th><th scope="col">Value2</th><th scope="col">LevelID</th><th scope="col">Time</th></tr></thead>
        <tbody class="table-group-divider">
        <?php
require_once "../../incl/lib/exploitPatch.php";
$gs = new mainLib();
if ($gs->checkPermission($_SESSION["accountID"], "toolModactions")) {
$query = $db->prepare("SELECT modactions.*, accounts.userName FROM modactions INNER JOIN accounts ON modactions.account = accounts.accountID ORDER BY ID DESC");
$query->execute();
$result = $query->fetchAll();
foreach($result as &$action){
	//detecting mod
	/*$account = $action["account"];
	$query = $db->prepare("SELECT userName FROM accounts WHERE accountID = :id");
	$query->execute([':id'=>$account]);
	$account = $query->fetchColumn();*/
	//detecting action
	$value = $action["value"];
	$value2 = $action["value2"];
	$account = $action["userName"];
	switch($action["type"]){
		case 1:
			$actionname = "Rated a level";
			break;
		case 2:
			$actionname = "Featured change";
			break;
		case 3:
			$actionname = "Coins verification state";
			break;
		case 4:
			$actionname = "Epic change";
			break;
		case 5:
			$actionname = "Set as daily feature";
			if(is_numeric($value2)){
				$value2 = date("d/m/Y G:i:s", $value2);
			}
			break;
		case 6:
			$actionname = "Deleted a level";
			break;
		case 7:
			$actionname = "Creator change";
			break;
		case 8:
			$actionname = "Renamed a level";
			break;
		case 9:
			$actionname = "Changed level password";
			break;
		case 10:
			$actionname = "Changed demon difficulty";
			break;
		case 11:
			$actionname = "Shared CP";
			break;
		case 12:
			$actionname = "Changed level publicity";
			break;
		case 13:
			$actionname = "Changed level description";
			break;
		case 15:
			$actionname = "Un/banned a user";
			break;
		case 16:
			$actionname = "Song ID change";
			break;
		default:
			$actionname = $action["type"];
			break;
		}
	if($action["type"] == 2 OR $action["type"] == 3 OR $action["type"] == 4 OR $action["type"] == 15){
		if($action["value"] == 1){
			$value = "True";
		}else{
			$value = "False";
		}
	}
	if($action["type"] == 5 OR $action["type"] == 6){
		$value = "";
	}
	$time = date("d/m/Y G:i:s", $action["timestamp"]);
	if($action["type"] == 5 AND $action["value2"] > time()){
		echo "<tr><td>".$account."</td><td>".$actionname."</td><td>".$value."</td><td>".$value2."</td><td>future</td><td>".$time."</td></tr>";
	}else{
		echo "<tr><td>".$account."</td><td>".$actionname."</td><td>".$value."</td><td>".$value2."</td><td>".$action["value3"]."</td><td>".$time."</td></tr>";
	}
	
}
}else{echo "YOU DON'T HAVE PERMISSION! GET OUT OF HERE)";}
?>
        </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
