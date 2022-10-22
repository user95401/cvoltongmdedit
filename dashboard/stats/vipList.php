<?php
session_start();
//Request files
include "../../incl/lib/connection.php";
require_once "../incl/dashboardLib.php";
require_once "../../incl/lib/mainLib.php";
require_once "../../incl/lib/exploitPatch.php";
$gs = new mainLib();
$dl = new dashboardLib();
$ep = new exploitPatch();

$query = $db->prepare("SELECT roleID, roleName FROM roles WHERE priority > 0 ORDER BY priority DESC");
$query->execute();
$result = $query->fetchAll();
foreach ($result as $role) {
	$roleName .= "<h2>" . $role['roleName'] . "</h2>";
	$query2 = $db->prepare("SELECT users.userName, users.lastPlayed FROM roleassign INNER JOIN users ON roleassign.accountID = users.extID WHERE roleassign.roleID = :roleID");
	$query2->execute([':roleID' => $role["roleID"]]);
	$account = $query2->fetchAll();
	foreach ($account as $user) {
		$time = date("d/m/Y G:i:s", $user["lastPlayed"]);
		$username = htmlspecialchars($user["userName"], ENT_QUOTES);
		$viptable .= "<tr><td>" . $username . "</td><td>$time</td></tr>";
	}
}

$dl->printPage('<h1>VIP</h1>
<table class="table table-inverse table-responsive">
<tr><th>User</th><th>Last Online</th></tr>
<tbody>
    '.$roleName.$viptable.'
</tbody>
</table>'
, true, "stats");
