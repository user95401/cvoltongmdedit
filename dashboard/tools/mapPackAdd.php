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
if(!$gs->checkPermission($_SESSION["accountID"], "dashboardModTools")){
	//Printing error
	$errorDesc = $dl->getLocalizedString("errorNoPerm");
	exit($dl->printBox('<h1>'.$dl->getLocalizedString("errorGeneric")."</h1>
					<p>$errorDesc</p>
					<a class='btn btn-primary btn-block' href='".$_SERVER["REQUEST_URI"]."'>".$dl->getLocalizedString("tryAgainBTN")."</a>","mod"));
}
//Checking nothing's empty
if(!empty($_POST["levels"]) && !empty($_POST["stars"]) && !empty($_POST["name"])){
	//Getting data
	$packName = $ep->remove($_POST["name"]);
	$levels = $ep->remove($_POST["levels"]);
	$stars = $ep->remove($_POST["stars"]);
	$coins = $ep->remove($_POST["coins"]);
	$color = $ep->remove($_POST["color"]);
	//Checking values are valid
	if(!is_numeric($stars) OR !is_numeric($coins) OR $stars > 10 OR $coins > 2){
		//Printing error
		$errorDesc = sprintf($dl->getLocalizedString("packAddError-4"), $color);
		exit($dl->printBox('<h1>'.$dl->getLocalizedString("packAdd")."</h1>
						<p>$errorDesc</p>
						<a class='btn btn-primary btn-block' href='".$_SERVER["REQUEST_URI"]."'>".$dl->getLocalizedString("tryAgainBTN")."</a>","mod"));
	}
	//Printing data
	$rgb = str_replace("rgb(", "", $color); $rgb = str_replace(")", "", $rgb);
	$lvlsarray = explode(",", $levels);
	foreach($lvlsarray AS &$level){
		//Checking if is numeric
		if(!is_numeric($level)){
			//Printing error
			$errorDesc = sprintf($dl->getLocalizedString("packAddError-2"), $level);
			exit($dl->printBox('<h1>'.$dl->getLocalizedString("packAdd")."</h1>
							<p>$errorDesc</p>
							<a class='btn btn-primary btn-block' href='".$_SERVER["REQUEST_URI"]."'>".$dl->getLocalizedString("tryAgainBTN")."</a>","mod"));
		}
		//Getting level data
		$query = $db->prepare("SELECT levelName FROM levels WHERE levelID=:levelID");	
		$query->execute([':levelID' => $level]);
		if($query->rowCount() == 0){
			//Printing error
			$errorDesc = sprintf($dl->getLocalizedString("packAddError-1"), $level);
			exit($dl->printBox('<h1>'.$dl->getLocalizedString("packAdd")."</h1>
							<p>$errorDesc</p>
							<a class='btn btn-primary btn-block' href='".$_SERVER["REQUEST_URI"]."'>".$dl->getLocalizedString("tryAgainBTN")."</a>","mod"));
		}
		$levelName = $query->fetchColumn();
		$levelstring .= $levelName . ", ";
	}
	$levelstring = substr($levelstring,0,-2);
	$diff = 0;
	$diffname = "Auto";
	//Checking stars
	switch($stars){
		case 1:
			$diffname = "Auto";
			$diff = 0;
			break;
		case 2:
			$diffname = "Easy";
			$diff = 1;
			break;
		case 3:
			$diffname = "Normal";
			$diff = 2;
			break;
		case 4:
		case 5:
			$diffname = "Hard";
			$diff = 3;
			break;
		case 6:
		case 7:
			$diffname = "Harder";
			$diff = 4;
			break;
		case 8:
		case 9:
			$diffname = "Insane";
			$diff = 5;
			break;
		case 10:
			$diffname = "Demon";
			$diff = 6;
			break;
	}
	//Adding map pack
	$query = $db->prepare("INSERT INTO mappacks (name, levels, stars, coins, difficulty, rgbcolors)
											VALUES (:name,:levels,:stars,:coins,:difficulty,:rgbcolors)");
	$query->execute([':name' => $packName, ':levels' => $levels, ':stars' => $stars, ':coins' => $coins, ':difficulty' => $diff, ':rgbcolors' => $rgb]);
	$dl->printBox("<h1>".$dl->getLocalizedString("packAdd")."</h1>
					<p>".sprintf($dl->getLocalizedString("packAdded"), $packName)."</p>
					<a class='btn btn-primary btn-block' href='".$_SERVER["REQUEST_URI"]."'>".$dl->getLocalizedString("songAddAnotherBTN")."</a>","mod");
}else{
	//Printing page
	$dl->printBox('<h1>'.$dl->getLocalizedString("packAdd").'</h1>
	            <script src="https://cdnjs.cloudflare.com/ajax/libs/jscolor/2.4.9/jscolor.min.js"></script>
<script>
// These options apply to all color pickers on the page
jscolor.presets.default = {
	format:"rgb", previewPosition:"right", previewSize:50, 
	position:"left", borderColor:"rgba(0,0,0,0.13)", borderRadius:7, 
	width:110, controlBorderColor:"rgba(187,187,187,0.52)", 
	sliderSize:10
};
</script>
				<form action="" method="post">
					<div class="form-group">
						<input autofocus required type="text" class="form-control" id="mapPackName" name="name" placeholder="'.$dl->getLocalizedString("mapPackNameFieldPlaceholder").'"><br>
						<input required type="text" class="form-control" id="mapPackLevels" name="levels" placeholder="'.$dl->getLocalizedString("mapPackLevelsFieldPlaceholder").'"><br>
						<input required max="10" min="0" type="number" class="form-control" id="mapPackStars" name="stars" placeholder="'.$dl->getLocalizedString("mapPackStarsFieldPlaceholder").'"><br>
						<input required max="2" min="0" type="number" class="form-control" id="mapPackCoins" name="coins" placeholder="'.$dl->getLocalizedString("mapPackCoinsFieldPlaceholder").'"><br>
						<input data-jscolor="" class="form-control" id="mapPackColor" name="color" placeholder="'.$dl->getLocalizedString("mapPackColorFieldPlaceholder").'">
					</div>
					<button type="submit" class="btn btn-primary btn-block">'.$dl->getLocalizedString("createBTN").'</button>
				</form>',"mod");
}
?>