<?php
//Requesting files
include "../../incl/lib/connection.php";
require_once "../incl/dashboardLib.php";
require_once "../../incl/lib/mainLib.php";
require_once "../../incl/lib/exploitPatch.php";
if($preventAddingNewData == 'true') {exit ('<div '.$styleForErrorAlert.'>Adding new data disabled by the administrator!</div>');}
$ep = new exploitPatch();
$dl = new dashboardLib();
$gs = new mainLib();
//Checking if logged in
session_start();
if(!isset($_SESSION["accountID"]) OR $_SESSION["accountID"] == 0){
	header("Location: ../login/login.php");
	exit();
}
//Checking if $songAdd not true
if($songAdd !== 'true')
{exit ('<div '.$styleForErrorAlert.'>songAdd tool is disabled by the administrator!</div>');}

if(!empty($_POST["songlink"])){
$song = str_replace("www.dropbox.com","dl.dropboxusercontent.com",$_POST["songlink"]);
$Headers = @get_headers($song);
if(preg_match("|200|", $Headers[0])) {
		$song = str_replace("?dl=0","",$song);
		$song = str_replace("?dl=1","",$song);
		$song = trim($song);
		$name = str_replace(".mp3", "", basename($song));
		$name = str_replace(".webm", "", $name);
		$name = str_replace(".mp4", "", $name);
		if(isset($_POST['NoSiteBrand']) && $_POST['NoSiteBrand'] == 'true') 
		{
		$name = str_replace("(musmore.com", "", $name);
		$name = str_replace("X2Download.com_-_", "", $name);
		$name = str_replace("yt5s.com_-_", "", $name);
		$name = str_replace("(Byfet.com", "", $name);
		$name = str_replace("Y2Mate.is_-_", "", $name);
		$name = str_replace("ytmp3free.cc_", "", $name);
		$name = str_replace("_[mp3pulse.ru]", "", $name);
		$name = str_replace("y2mate.com_-_", "", $name);
		$name = str_replace("_www.lightaudio.ru", "", $name);
		$name = str_replace("- wap.kengu.ru", "", $name);
		$name = str_replace("_(audiohunter.ru", "", $name);
		$name = str_replace("_[gidmp3.ru]", "", $name);
		$name = str_replace("_(EEMUSIC.ru", "", $name);
		$name = str_replace("[mp3can.ru]", "", $name);
		$name = str_replace("www.hotplayer.ru", "", $name);
		$name = str_replace("_(AxeMusic.ru)", "", $name);
		$name = str_replace("_(KillAudio.ru", "", $name);
		$name = str_replace("_(AndroSound.ru", "", $name);
		$name = str_replace("_(OOSOUND.RU", "", $name);
		$name = str_replace("_(Gybka.com", "", $name);
		$name = str_replace("_(mp3zvon.com", "", $name);
		$name = str_replace("_(mp3IQ.net", "", $name);
		$name = str_replace("-www_muzonov_net", "", $name);
		$name = str_replace("dfsfsdfsdf", "", $name);
		$name = str_replace("dfsfsdfsdf", "", $name);
		$name = str_replace("dfsfsdfsdf", "", $name);
		}
		$name = urldecode($name);
		if(isset($_POST['noUnderlines']) && $_POST['noUnderlines'] == 'true') 
		{
		$name = str_replace("_"," ",$name);
		$name = str_replace("+"," ",$name);
		}
		if(!empty($_POST["name"]) AND $songAdd_customName == 'true'){
		    $name = $_POST["name"];
		}
		$author = str_ireplace('www.', '', parse_url($_POST["songlink"], PHP_URL_HOST));
		if(!empty($_POST["author"]) AND $songAdd_customAuthor == 'true'){
		    $author = $_POST["author"];
		}
		$name = $gs->translit($name);
		$author = $gs->translit($author);
		$name = $ep->remove($name);
		$author = $ep->remove($author);
		$reuploadTime = time();
	$ch = curl_init($song);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, TRUE);
	curl_setopt($ch, CURLOPT_NOBODY, TRUE);
	$data = curl_exec($ch);
	$size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
	curl_close($ch);
	$size = round($size / 1024 / 1024, 2);
	$hash = "";
	//$hash = sha1_file($song);
	$count = 0;
	$query = $db->prepare("SELECT count(*) FROM songs WHERE download = :download");
	$query->execute([':download' => $song]);	
	$count = $query->fetchColumn();
	if($count != 0){
		exit($dl->printBox('<h1>'.$dl->getLocalizedString("songAdd")."</h1>
						<p>".$dl->getLocalizedString('songAddError-3')."</p>
						<a class='btn btn-primary btn-block' href='".$_SERVER["REQUEST_URI"]."'>".$dl->getLocalizedString("tryAgainBTN")."</a>","reupload"));
	}
	else{
	    $query = $db->prepare("INSERT INTO songs (name, authorID, authorName, size, download, hash, reuploadTime)
		VALUES (:name, '9', :author, :size, :download, :hash, :reuploadTime)");
		$query->execute([':name' => $name, ':download' => $song, ':author' => $author, ':size' => $size, ':hash' => $hash, ':reuploadTime' => $reuploadTime]);
		//Printing box
		$dl->printBox("<h1>".$dl->getLocalizedString("songAdd")."</h1>
						<p>".$dl->getLocalizedString("songReuploaded")."<br>
						ID: <b style='user-select: all'>".$db->lastInsertId().'</a></b><br>
						'.$dl->getLocalizedString("Song name").': '.$name.'<br>
						'.$dl->getLocalizedString("Song author").': '.$author."</p>
						<a class='btn btn-primary btn-block' href='".$_SERVER["REQUEST_URI"]."'>".$dl->getLocalizedString("songAddAnotherBTN")."</a>","reupload");
	}
}else{
	exit($dl->printBox('<h1>'.$dl->getLocalizedString("songAdd")."</h1>
						<p>".$dl->getLocalizedString('songAddError-2')."</p>
						<a class='btn btn-primary btn-block' href='".$_SERVER["REQUEST_URI"]."'>".$dl->getLocalizedString("tryAgainBTN")."</a>","reupload"));
}
}
if(empty($_POST["songlink"])){
	//Printing page
$Song_name = ''; $Song_author = '';
if($songAdd_customName == 'true'){$Song_name = '
<label for="urlField">'.$dl->getLocalizedString("Song name").'</label>
<input type="text" class="form-control" name="name" placeholder="'.$dl->getLocalizedString("blank for name = file name").'">';}
if($songAdd_customAuthor == 'true'){$Song_author = '
<label for="urlField">'.$dl->getLocalizedString("Song author").'</label>
<input type="text" class="form-control" name="author" placeholder="'.$dl->getLocalizedString("blank for author = site domain").'">
';}
	$dl->printBox('<h1>'.$dl->getLocalizedString("songAdd").'</h1>
				<form action="" method="post">
					<div class="form-group">
						<label for="urlField">'.$dl->getLocalizedString("songAddUrlFieldLabel").'</label>
						<input required type="text" pattern="https?://.+" class="form-control" name="songlink" placeholder="'.$dl->getLocalizedString("songAddUrlFieldPlaceholder").'">
						'.$Song_name.'
						<div style="display: flex;">
						<input style="margin-bottom: 0.4rem;margin-left: 0.4rem;" type="checkbox" id="noUnderlines" name="noUnderlines" value="true">
						<label style="margin-left: 0.1rem;" for="noUnderlines">'.$dl->getLocalizedString("noUnderlines").'</label>
						<input style="margin-bottom: 0.4rem;margin-left: 0.4rem;" type="checkbox" id="NoSiteBrand" name="NoSiteBrand" value="true">
						<label style="margin-left: 0.1rem;" for="NoSiteBrand">'.$dl->getLocalizedString("NoSiteBrand").'</label><br>
						</div>
						'.$Song_author.'
					</div>
					<button type="submit" class="btn btn-primary btn-block">'.$dl->getLocalizedString("addBTN").'</button>
				</form>',"reupload");
}
?>