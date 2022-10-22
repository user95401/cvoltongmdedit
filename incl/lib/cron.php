<?php

if($autoban == "true"){
$query = $db->prepare("SELECT COUNT(starDemon) FROM levels WHERE starDemon = 1");
$query->execute();
$ABdemons = $ABdemons + $query->fetchColumn();

$query = $db->prepare("SELECT * FROM dailyfeatures");
$query->execute();
$result = $query->fetchAll();
foreach($result as $a){
	$querys = $db->prepare("SELECT starStars FROM levels WHERE levelID = ".$a["levelID"]);
	$querys->execute();
	$ABstars = $ABstars + $querys->fetchColumn();
}
$query = $db->prepare("SELECT SUM(stars) FROM mappacks");
$query->execute();
$ABstars = $ABstars + $query->fetchColumn();

$query = $db->prepare("SELECT SUM(starStars) FROM levels");
$query->execute();
$ABstars = $ABstars + $query->fetchColumn();
$query = $db->prepare("UPDATE users SET isBanned = 1 WHERE stars > $ABstars");
$query->execute();

$query = $db->prepare("SELECT SUM(coins) FROM mappacks");
$query->execute();
$ABcoins = $ABcoins + $query->fetchColumn();

$query = $db->prepare("SELECT SUM(coins) FROM levels");
$query->execute();
$ABpc = $query->fetchColumn();

$query = $db->prepare("SELECT * FROM gauntlets");
$query->execute();
$result = $query->fetchAll();
foreach($result as $a){
	$querys = $db->prepare("SELECT starStars FROM levels WHERE levelID = ".$a["level1"]);
	$querys->execute();
	$ABstars = $ABstars + $querys->fetchColumn();
}

$query = $db->prepare("SELECT * FROM gauntlets");
$query->execute();
$result = $query->fetchAll();
foreach($result as $a){
	$querys = $db->prepare("SELECT starStars FROM levels WHERE levelID = ".$a["level5"]);
	$querys->execute();
	$ABstars = $ABstars + $querys->fetchColumn();
}

$query = $db->prepare("SELECT * FROM gauntlets");
$query->execute();
$result = $query->fetchAll();
foreach($result as $a){
	$querys = $db->prepare("SELECT starStars FROM levels WHERE levelID = ".$a["level4"]);
	$querys->execute();
	$ABstars = $ABstars + $querys->fetchColumn();
}

$query = $db->prepare("SELECT * FROM gauntlets");
$query->execute();
$result = $query->fetchAll();
foreach($result as $a){
	$querys = $db->prepare("SELECT starStars FROM levels WHERE levelID = ".$a["level3"]);
	$querys->execute();
	$ABstars = $ABstars + $querys->fetchColumn();
}

$query = $db->prepare("SELECT * FROM gauntlets");
$query->execute();
$result = $query->fetchAll();
foreach($result as $a){
	$querys = $db->prepare("SELECT starStars FROM levels WHERE levelID = ".$a["level2"]);
	$querys->execute();
	$ABstars = $ABstars + $querys->fetchColumn();
}

$query = $db->prepare("UPDATE users SET isBanned = 1 WHERE stars > $ABstars OR coins > $ABcoins OR userCoins > $ABpc");
$query->execute();
}

if($fixCPs == "true"){
$query = $db->prepare("update users 
set creatorPoints = (
	 select COUNT(*)
	 from levels 
	 where levels.userID = users.userID AND starStars != 0
) + (
	 select COUNT(*)
	 from levels 
	 where levels.userID = users.userID AND levels.starFeatured != 0 AND levels.starEpic = 0 
) + (
	select COUNT(*)
	from levels 
	where levels.userID = users.userID AND levels.starEpic = 1 AND levels.starFeatured = 0
) + (
	select COUNT(*)
	from levels 
	where levels.userID = users.userID AND levels.starEpic = 1 AND levels.starFeatured = 0
) + (
	select COUNT(*)
	from levels 
	where levels.userID = users.userID AND levels.starEpic = 1 AND levels.starFeatured = 1
) + (
	 select COUNT(*)
	 from levels 
	 where levels.userID = users.userID AND levels.starEpic = 1 AND levels.starFeatured = 1
)");
$query->execute();
}

if($fixnames == "true"){
$query = $db->prepare("UPDATE users
	INNER JOIN accounts ON accounts.accountID = users.extID
	SET users.userName = accounts.userName
	WHERE users.extID REGEXP '^-?[0-9]+$'
	AND LENGTH(accounts.userName) <= 69");
$query->execute();
$query = $db->prepare("UPDATE users
	INNER JOIN accounts ON accounts.accountID = users.extID
	SET users.userName = 'Invalid Username'
	WHERE users.extID REGEXP '^-?[0-9]+$'
	AND LENGTH(accounts.userName) > 69");
$query->execute();
}

if($deleteUnnecessary == "true"){
$query = $db->prepare("DELETE FROM users WHERE extID = ''");
$query->execute();
$query = $db->prepare("DELETE FROM songs WHERE download = '' OR download LIKE 'file:%'");
$query->execute();
$query = $db->prepare("UPDATE levels SET password = 0 WHERE password = 2");
$query->execute();
$query = $db->prepare("UPDATE `levels` SET `starStars`='0',`starCoins`='0' WHERE starCoins > 4 AND starStars > 20"); //remove invalid stars and coins
$query->execute();
}

//Updating song levelsCount
$query_songsCount = $db->prepare("SELECT ID FROM songs");
$query_songsCount->execute();
$result_songsCount = $query_songsCount->fetchAll();
$songs_songsCount = $db->prepare("SELECT count(*) FROM songs");
$songs_songsCount->execute();
$songs_songsCount = $songs_songsCount->fetchColumn();
foreach($result_songsCount as &$songData_songsCount){
	//Getting song count
	$song_songsCount = $songData_songsCount["ID"];
	$query2_songsCount = $db->prepare("SELECT count(*) FROM levels WHERE songID = :song");
	$query2_songsCount->execute([':song' => $song_songsCount]);
	$count_songsCount = $query2_songsCount->fetchColumn();
	//Updating song count
	if($count_songsCount != 0){
		$query4_songsCount = $db->prepare("UPDATE songs SET levelsCount=:count WHERE ID=:songID");
		$query4_songsCount->execute([':count' => $count_songsCount, ':songID' => $song_songsCount]);
	}
}



if($friendsLeaderboardCron == "true"){
    
$query_friendsLeaderboardCron = $db->prepare("SELECT accountID, userName FROM accounts");
$query_friendsLeaderboardCron->execute();
$result_friendsLeaderboardCron = $query_friendsLeaderboardCron->fetchAll();
foreach($result_friendsLeaderboardCron as $account_friendsLeaderboardCron){
	//Getting friends count
	$me_friendsLeaderboardCron = $account["accountID"];
	$query2_friendsLeaderboardCron = $db->prepare("SELECT count(*) FROM friendships WHERE person1 = :me OR person2 = :me");
	$query2_friendsLeaderboardCron->execute([':me' => $me_friendsLeaderboardCron]);
	$friendscount_friendsLeaderboardCron = $query2_friendsLeaderboardCron->fetchColumn();
	//Updating friends count
	if($friendscount_friendsLeaderboardCron != 0){
		$query4_friendsLeaderboardCron = $db->prepare("UPDATE accounts SET friendsCount=:friendscount WHERE accountID=:me");
		$query4_friendsLeaderboardCron->execute([':friendscount' => $friendscount_friendsLeaderboardCron, ':me' => $me_friendsLeaderboardCron]);
	}
}
}
?>