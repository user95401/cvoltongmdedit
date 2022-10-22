<!-- LikeBtn.com -->
<script>(function(d,e,s){if(d.getElementById("likebtn_wjs"))return;a=d.createElement(e);m=d.getElementsByTagName(e)[0];a.async=1;a.id="likebtn_wjs";a.src=s;m.parentNode.insertBefore(a, m)})(document,"script","//w.likebtn.com/js/w/widget.js");</script>
<!-- LikeBtn.com -->
<?php
//Requesting files
include      "../../incl/lib/connection.php";
require_once "../incl/dashboardLib.php";
require_once "../../incl/lib/exploitPatch.php";
require_once "../../incl/lib/songReup.php";
$dl = new dashboardLib();
$ep = new exploitPatch();

if(empty($_GET["songID"])){
	exit($dl->printBox('<h1>Bad song ID</h1>'));
}
$songid = ExploitPatch::remove($_GET["songID"]);

$query = $db->prepare("SELECT * FROM songs WHERE ID = :songid LIMIT 1");
$query->execute([':songid' => $songid]);

if($query->rowCount() == 0) {
			$ch = curl_init(); 
			curl_setopt($ch, CURLOPT_URL, "https://www.newgrounds.com/audio/listen/".$songid); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			$songinfo = curl_exec($ch); 
			curl_close($ch);
			if(empty(explode('"url":"', $songinfo)[1])){
				exit($dl->printBox('<h1>Bad song ID</h1>'));
			}
			$songurl = explode('","', explode('"url":"', $songinfo)[1])[0];
			$songauthor = explode('","', explode('artist":"', $songinfo)[1])[0];
			$songurl = str_replace("\/", "/", $songurl);
			$songname = explode("<title>", explode("</title>", $songinfo)[0])[1];
			$result = "1~|~".$songid."~|~2~|~".$songname."~|~3~|~1234~|~4~|~".$songauthor."~|~5~|~6.69~|~6~|~~|~10~|~".$songurl."~|~7~|~~|~8~|~1";
	//Printing page
	$dl->printBox('<h1>Song info (<a href="https://www.newgrounds.com" dir="auto" rel="nofollow" target="_blank">NG</a>!)</h1>
	'.$dl->getLocalizedString("mapPackNameFieldPlaceholder").': '.$songname.'</br>
	'.$dl->getLocalizedString("Song author").': <a href="https://'.$songauthor.'.newgrounds.com" dir="auto" rel="nofollow" target="_blank">'.$songauthor.'</a></br>
	ID: '.$songid.'
    <br><br><span data-identifier="ngsong-'.$songid.'" class="likebtn-wrapper" data-theme="bootstrap" data-ef_voting="push" data-lazy_load="true" data-loader_show="true"></span>
    <br><br>
	<iframe src="'.$songurl.'" style="padding: 0;"></iframe>
	');
}else{
$result = $query->fetch();
//Printing page
	$dl->printBox('<h1>Song info</h1>
	'.$dl->getLocalizedString("mapPackNameFieldPlaceholder").': '.$result["name"].'</br>
	'.$dl->getLocalizedString("Song author").': '.$result["authorName"].'</br>
	ID: '.$result["ID"].'
	 | '.$dl->getLocalizedString("levelsCount").': '.$result["levelsCount"].'
	 | '.$dl->getLocalizedString("Added").' '.$dl->convertToDate($result["reuploadTime"]).' ago
    <br><br><span data-identifier="'.$dbname.'-song-'.$songid.'" class="likebtn-wrapper" data-theme="bootstrap" data-ef_voting="push" data-lazy_load="true" data-loader_show="true"></span>
    <br><br><a type="audio/mpeg3" href="'.$result["download"].'">'.$dl->getLocalizedString("download").'</a>
	'.$dl->getLocalizedString("size").': '.$result["size"].'
	<audio style="background-color: #f1f3f4;" class="form-control" controls ><source src="'.$result["download"].'" type="audio/mpeg"></audio>
	');
}
?>
