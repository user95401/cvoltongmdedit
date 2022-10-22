<?php
//Requesting files
include      "../../incl/lib/connection.php";
require_once "../incl/dashboardLib.php";
require_once "../../incl/lib/exploitPatch.php";
require_once "../../incl/lib/mainLib.php";
$gs = new mainLib();
$dl = new dashboardLib();
$ep = new exploitPatch();

if(empty($_GET["userID"])){
	exit("-1");
}
$userID = ExploitPatch::remove($_GET["userID"]);
$query = $db->prepare("SELECT * FROM users WHERE userID = :userID LIMIT 1");
$query->execute([':userID' => $userID]);
$result = $query->fetch();
$query = $db->prepare("SELECT * FROM acccomments WHERE userID = :userID ORDER BY timestamp DESC LIMIT 1");
$query->execute([':userID' => $userID]);
$acccomment = $query->fetch();

//Printing page
if($gs->checkPermission($_SESSION["accountID"], "dashboardModTools")){
$ban = '<form action="tools/leaderboardsBan.php" method="post">
<input required type="hidden" name="userID" value="'.$userID.'">
<button type="submit" class="btn btn-link">'.$dl->getLocalizedString('leaderboardBan').'</button>
</form action="tools/leaderboardsBan.php" method="post">';
$unBan = '<form action="tools/leaderboardsUnban.php" method="post">
<input required type="hidden" name="userID" value="'.$userID.'">
<button type="submit" class="btn btn-link">'.$dl->getLocalizedString('leaderboardUnban').'</button>
</form action="tools/leaderboardsUnban.php" method="post">';
}

if($result["isBanned"] == 0) {$isBanned = $dl->getLocalizedString('No').$ban;} else {$isBanned = $dl->getLocalizedString('Yes').$unBan;}
if(empty($acccomment["comment"])) {$post = "";} else {$post='<div style="width: 100%;background-color: #212529;padding: 1vh;margin: 4vh 0vh;align-self: center">
    <p class="blockquote">'.base64_decode($acccomment["comment"]).'</p>
    <figcaption class="blockquote-footer">'.sprintf($dl->getLocalizedString("lastPostAt"), date("d/m/Y G:i", $acccomment["timestamp"])).' ID: '.$acccomment["commentID"].'</figcaption>
    </div>';
}
	$dl->printPage('
<div class="row align-items-center">
<div class="col">

    <div>
     <div style="width: 100%; color: #fff;background-color: #212529;text-align: center;padding: 1vh;align-self: center">
	 <p class="h1">'.$result["userName"].'</p>
	 </div> 
    </div>
    
    <div style="width: 100%;background-color: #212529;text-align: center;padding: 1vh;color: #fff;margin: 1vh 0vh;align-self: center">
    Stars: '.$result["stars"].', Demons: '.$result["demons"].', Coins: '.$result["coins"].', User coins: '.$result["userCoins"].', Creator points: '.$result["creatorPoints"].', Diamonds: '.$result["diamonds"].', Moons: '.$result["moons"].', Orbs: '.$result["orbs"].', Completed Lvls: '.$result["completedLvls"].'
    </div>
    '.$post.'
    <div class="row h5" style="width: 100%; color: #fff;background-color: #212529;text-align: center;padding: 1vh;margin: 0vh 0vh 2%;align-self: center">
     <div class="col-sm" style="align-self: center">'.$dl->getLocalizedString("lastPlayed").': '.date("d/m/Y G:i", $result["lastPlayed"]).'</div>
     <div class="col-sm" style="align-self: center">'.$dl->getLocalizedString("banned?").': '.$isBanned.'</div>
    </div>
	

</div>
</div>
	');
?>