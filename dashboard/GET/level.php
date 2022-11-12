<?php
//Requesting files
include      "../../incl/lib/connection.php";
require_once "../incl/dashboardLib.php";
require_once "../../incl/lib/exploitPatch.php";
require_once "../../incl/lib/mainLib.php";
$gs = new mainLib();
$dl = new dashboardLib();
$ep = new exploitPatch();

if(empty($_GET["levelID"])){
	exit("-1");
}
$levelID = ExploitPatch::remove($_GET["levelID"]);
$query = $db->prepare("SELECT * FROM levels WHERE levelID = :levelID LIMIT 1");
$query->execute([':levelID' => $levelID]);
$result = $query->fetch();

$songID = $result["songID"];
$query2=$db->prepare("SELECT * FROM songs WHERE ID = :songID LIMIT 1");
$query2->execute([':songID' => $songID]);
$song = $query2->fetch();

//Printing page

$levelDesc = '
<div class="row">
		<div class="col-md-1">
		</div>
		<div class="col-md-10 darkBox" style="padding: 1vh;margin: 1vh 0vh;">
			<h5 class="text-center text-muted">
				'.base64_decode($result["levelDesc"]).'
			</h5>
		</div>
		<div class="col-md-1">
		</div>
	</div>
';
if(empty($result["levelDesc"])){$levelDesc = '
<div class="row">
		<div class="col-md-1">
		</div>
		<div class="col-md-10 darkBox" style="padding: 1vh;margin: 1vh 0vh;">
			<h5 class="text-center text-muted">
				(No description provided)
			</h5>
		</div>
		<div class="col-md-1">
		</div>
	</div>
';}

$original = '<br>'.$dl->getLocalizedString("original").$result["original"];
if($result["original"] == 0){$original = "";}

$updateDate = '<br>'.$dl->getLocalizedString("updated").$dl->convertToDate($result["updateDate"]).' ago';
if($result["updateDate"] == 0){$updateDate = "";}

$rateDate = '<br>'.$dl->getLocalizedString("rated").$dl->convertToDate($result["rateDate"]).' ago';
if($result["rateDate"] == 0){$rateDate = "";}

$unlisted = '<br>'.$dl->getLocalizedString("unlisted");
if($result["unlisted"] == 0){$unlisted = "";}

$updateDate = '<br>'.$dl->getLocalizedString("updated").$dl->convertToDate($result["updateDate"]).' ago';
if($result["updateDate"] == 0){$updateDate = "";}

$likes = '<br>'.$dl->getLocalizedString("likes").': '.$result["likes"];
if($result["likes"] < 0){$likes = '<br>'.$dl->getLocalizedString("dislikes").': '.str_replace("-", "", $result["likes"]);}

$songName = $song["name"];
$songInfo = '<h4>
	By: <a href="https://'.$song["authorName"].'.newgrounds.com" dir="auto" rel="nofollow" target="_blank">'.$song["authorName"].'</a>
	</h4>
	<audio style="background-color: #f1f3f4;" class="form-control" controls ><source src="'.$song["download"].'" type="audio/mpeg"></audio>
	<h6>
	ID: <a href="GET/song.php?songID='.$song["ID"].'">'.$song["ID"].'</a> '.$dl->getLocalizedString("size").': '.$song["size"].'
	</h6>';
if($song["ID"] == 0){
    $songName = $gs->getAudioTrack($result["audioTrack"]);
    $songInfo = '';
}


	$dl->printPage('
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12 text-center darkBox" style="padding: 1vh;">
	<h1>'.$result["levelName"].' <span class="text-muted" style="font-size: x-large;">by '.$result["userName"].'</span></h1>
		</div>
	</div>
	'.$levelDesc.'
	<div class="row">
		<div blank class="col-md-1">
		</div blank>
		<div class="col-md-5" style="padding: 1%;">
			<div class="row">
				<div class="col-md-12" style="text-align: center;padding: 8vh 0vh 4vh;">
	<h4 style="font-size: 2.5rem">
	'.$gs->getDifficulty($result["starDifficulty"],$result["auto"],$result["demon"]).'<br>
	'.$result["starStars"].'⭐
	</h4>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12" style="padding: 0 12vh;">
					<a href="tools/commands.php?levelID='.$levelID.'&action=unlist" role="button" class="btn btn-block btn-primary">
						'.$dl->getLocalizedString("unlist").'
					</a> 
					<a href="tools/commands.php?levelID='.$levelID.'&action=public" role="button" class="btn btn-primary btn-block">
						'.$dl->getLocalizedString("public").'
					</a> 
					<a href="tools/rate.php?levelID='.$levelID.'" role="button" class="btn btn-primary btn-block">
						'.$dl->getLocalizedString("rate").'
					</a>
					<a href="tools/commands.php?levelID='.$levelID.'&action=delete" role="button" class="btn btn-block btn-primary">
						'.$dl->getLocalizedString("delete").'
					</a>
				</div>
			</div>
		</div>
		<div class="col-md-4 text-center darkBox" style="padding: 4vh;font-size: 3vh;">
	'.$dl->getLocalizedString("downloads").': '.$result["downloads"].'
	'.$likes.'
	<br>'.$dl->getLocalizedString("levelLength").': '.$gs->getLength($result["levelLength"]).'
	<br>
	'.$original.'
	'.$unlisted.'
	<br>'.$dl->getLocalizedString("uploaded").''.$dl->convertToDate($result["uploadDate"]).' ago
	'.$updateDate.'
	'.$rateDate.'
	</h4>
		</div>
		<div blank class="col-md-1">
		</div blank>
	</div>
	
	<div $song class="row">
		<div blank class="col-md-1">
		</div blank>
		<div class="col-md-10 darkBox" style="padding: 4vh;margin-top: 3vh;">
	<h2>
	'.$songName.'
	</h2>
	
	'.$songInfo.'
		</div>
		<div blank class="col-md-1">
		</div blank>
	</div $song>
</div>
	');

?>
