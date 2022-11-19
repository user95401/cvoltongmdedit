<script>
</script>
<?php
session_start();
function listLang($dir){
	$dirstring = "";
	$files = scandir($dir);
	foreach($files as $file) {
		if(pathinfo($file, PATHINFO_EXTENSION) == "php" AND $file != "index.php"){
		    $Lang = $file; $Lang = str_replace('.php', '', $file); $Lang = str_replace('locale', '', $Lang);
			$dirstring .= '<a class="dropdown-item" href="lang/switchLang.php?lang='.$Lang.'">'.$Lang.'</a>';
		}
	}
	return $dirstring;
}

class dashboardLib{
	//Print header
	public function printHeader($isSubdirectory = true){
		$this->handleLangStart();
		echo '<!DOCTYPE html>
				<html lang="en">
					<head>
						<meta charset="utf-8">';
		if($isSubdirectory){
			echo '<base href="../">';
			include "../../config/main.php";
		    echo '<style>';
		    include "../incl/cvolton.css";
		    echo '</style>';
		}else{
		    echo '<style>';
		    include "incl/cvolton.css";
		    echo '</style>';
		}
		include "../config/main.php";
		if(!empty($_GET["songID"]) or !empty($_GET["userID"]) or !empty($_GET["levelID"])) $idlblfortitle = 'by ID: ';
		echo '<!-- DASHBOARD CREATORS: Cvolton, user666, donalex1 -->
		<title>'.$this->getLocalizedString("Dashboard").' - '.basename($_SERVER["SCRIPT_FILENAME"], '.php').' '.$idlblfortitle.$_GET["songID"].$_GET["userID"].$_GET["levelID"].'</title>
		<meta property="og:title" content="'.$this->getLocalizedString("Dashboard").' - '.basename($_SERVER["SCRIPT_FILENAME"], '.php').' '.$_GET["songID"].$_GET["userID"].$_GET["levelID"].'">
		<meta property="og:image" content="'.$projectIcon.'">
		<meta property="og:site_name" content="'.$GDPSname.'">
		<meta property="og:description" content="'.$this->getLocalizedString("Dashboardof").' '.$GDPSname.', browse: users, levels, gauntletTable, packTable, songs.  Account, mod, reupload, stats, admin tools and more!">
		<link rel="shortcut icon" type="image/ico" href="'.$projectIcon.'"/>
		<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
        	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
		<script async src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
		<script async src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.min.js"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
		<link async rel="stylesheet" href="incl/font-awesome-4.7.0/css/font-awesome.min.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<style>';
		include "../incl/cvolton.css";
		echo '
		</style>
		</head>
		<body>';
	}
	//Print body of the page
	public function printBoxBody(){
		echo '<div class="container-fluid container-box">
					<div class="card">
						<div class="card-block buffer">';
	}
	//Print a box
	public function printBox($content, $active = "", $isSubdirectory = true){
		$this->printHeader($isSubdirectory);
		$this->printNavbar($active);
		$this->printBoxBody();
		echo "$content";
		$this->printBoxFooter();
		$this->printFooter();
	}
	//Print the footer box
	public function printBoxFooter(){
		echo '</div></div></div>';
	}
	//Print footer
	public function printFooter(){
		echo '</body>
		</html>';
	}
	//Print navbar
	public function printNavbar($active){
		require_once __DIR__."/../../incl/lib/mainLib.php";
		include __DIR__."/../../config/main.php";
		$gs = new mainLib();
		$modActive = "";
		$homeActive = "";
		$cronActive = "";
		$statsActive = "";
		$loginActive = "";
		$browseActive = "";
		$accountActive = "";
		$reuploadActive = "";
		$adminActive = "";
		
		$smallscreenAlert = '<script>
if(screen.availHeight > screen.availWidth){alert("'.$this->getLocalizedString("smallscreenAlert").'")}
</script>';

		switch($active){
			case "home":
				$homeActive = "active";
				break;
			case "account":
				$accountActive = "active";
				break;
			case "mod":
				$modActive = "active";
				break;
			case "reupload":
				$reuploadActive = "active";
				break;
			case "stats":
				$statsActive = "active";
				echo $smallscreenAlert;
				break;
			case "browse":
				$browseActive = "active";
				echo $smallscreenAlert;
				break;
			case "cron":
				$cronActive = "active";
				break;
			case "admin":
				$adminActive = "active";
				break;
			case "login":
				$loginActive = "active";
				break;
		}
        if(!empty($_GET["songID"]) or !empty($_GET["userID"]) or !empty($_GET["levelID"])) $idlblfortitle = 'by ID: ';
		echo '<nav class="navbar navbar-dark menubar">
		         <a class="navbar-brand" href="" onclick="history.back()" style="width: 1">
                  <img src="'.$projectIcon.'" alt="Logo" width="30" height="30" class="d-inline-block align-text-top">
                '.$this->getLocalizedString("Dashboard").' - '.basename($_SERVER["SCRIPT_FILENAME"], '.php').' '.$idlblfortitle.$_GET["songID"].$_GET["userID"].$_GET["levelID"].'
                </a>
			<button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar">
              <span class="navbar-toggler-icon"></span>
            </button>
			<div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="offcanvasDarkNavbar" data-bs-scroll="true" aria-labelledby="offcanvasDarkNavbarLabel">
              <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasDarkNavbarLabel">'.$this->getLocalizedString("Dashboardof").' '.$GDPSname.'</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
              </div>
              <div class="offcanvas-body" style="overflow-x: hidden;">
				<ul class="navbar-nav">
					<li class="nav-item '.$homeActive.' ">
						<a class="nav-link" href="home.php">
							<i class="fa fa-home" aria-hidden="true"></i> '.$this->getLocalizedString("homeNavbar").'
						</a>
					</li>';
		$browse = '<li class="nav-item dropdown '.$browseActive.' ">
						<a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="fa fa-folder-open" aria-hidden="true"></i> '.$this->getLocalizedString("browse").'
						</a>
						<div class="dropdown-menu dropdown-menu-offcanvas" aria-labelledby="navbarDropdownMenuLink">
							<a class="dropdown-item" aria-expanded="false" href="stats/users.php">'.$this->getLocalizedString("users").'</a>
							<a class="dropdown-item" aria-expanded="false" href="stats/levels.php">'.$this->getLocalizedString("levels").'</a>
							<a class="dropdown-item" aria-expanded="false" href="stats/gauntletTable.php">'.$this->getLocalizedString("gauntletTable").'</a>
							<a class="dropdown-item" aria-expanded="false" href="stats/packTable.php">'.$this->getLocalizedString("packTable").'</a>
							<a class="dropdown-item" aria-expanded="false" href="stats/songs.php">'.$this->getLocalizedString("songs").'</a>';
		if(isset($_GET["accountManagement"])){$AMset = ' show';}
		if(isset($_SESSION["accountID"]) && $_SESSION["accountID"]){
			echo '
					<li class="nav-item dropdown '.$accountActive.' '.$AMset.'">
						<a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="fa fa-user" aria-hidden="true"></i> '.$this->getLocalizedString("accountManagement").'
						</a>
						<div class="dropdown-menu dropdown-menu-offcanvas '.$AMset.'" aria-labelledby="navbarDropdownMenuLink">
							<a class="dropdown-item" aria-expanded="false" href="account/linkAcc.php">'.$this->getLocalizedString("linkAccount").'</a>
							<a class="dropdown-item" aria-expanded="false" href="account/unlisted.php">'.$this->getLocalizedString("unlistedLevels").'</a>
							<a class="dropdown-item" aria-expanded="false" href="account/changeUsername.php">'.$this->getLocalizedString("changeUsername").'</a>
							<a class="dropdown-item" aria-expanded="false" href="account/changePassword.php">'.$this->getLocalizedString("changePassword").'</a>
							<a class="dropdown-item" aria-expanded="false" href="account/settings.php">'.$this->getLocalizedString("account_settings").'</a>
							<a class="dropdown-item text-danger" aria-expanded="false" href="account/deleteOwnAccount.php">'.$this->getLocalizedString("deleteOwnAccount").'</a>
						</div>
					</li>' . $browse . '</div></li>';
			if($gs->checkPermission($_SESSION["accountID"], "dashboardModTools")){
				echo '<li class="nav-item dropdown '.$modActive.'">
						<a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="fa fa-wrench" aria-hidden="true"></i> '.$this->getLocalizedString("modTools").'
						</a>
						<div class="dropdown-menu dropdown-menu-offcanvas" aria-labelledby="navbarDropdownMenuLink">
							<a class="dropdown-item" aria-expanded="false" href="stats/reports.php">'.$this->getLocalizedString("reports").'</a>
							<a class="dropdown-item" aria-expanded="false" href="stats/accounts.php">'.$this->getLocalizedString("accounts").'</a>
							<a class="dropdown-item" aria-expanded="false" href="tools/leaderboardsBan.php">'.$this->getLocalizedString("leaderboardBan").'</a>
							<a class="dropdown-item" aria-expanded="false" href="tools/leaderboardsUnban.php">'.$this->getLocalizedString("leaderboardUnban").'</a>
							<a class="dropdown-item" aria-expanded="false" href="tools/mapPackAdd.php">'.$this->getLocalizedString("packManage").'</a>
							<a class="dropdown-item" aria-expanded="false" href="tools/addQuests.php">'.$this->getLocalizedString("Add quest").'</a>
							<a class="dropdown-item" aria-expanded="false" href="tools/rate.php">'.$this->getLocalizedString("rate").'</a>
						</div>
					</li>';
				echo '<li class="nav-item dropdown '.$cronActive.'">
						<a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="fa fa-wrench" aria-hidden="true"></i> '.$this->getLocalizedString("cleanup").'
						</a>
						<div class="dropdown-menu dropdown-menu-offcanvas" aria-labelledby="navbarDropdownMenuLink">
							<a class="dropdown-item" aria-expanded="false" href="cleanup/deleteUnused.php">'.$this->getLocalizedString("deleteUnused").'</a>
							<a class="dropdown-item" aria-expanded="false" href="cleanup/optimizeTables.php">'.$this->getLocalizedString("optimize").'</a>
							<a class="dropdown-item" aria-expanded="false" href="cleanup/deleteAcs.php">'.$this->getLocalizedString("deleteAcs").'</a>
							<a class="dropdown-item" aria-expanded="false" href="cleanup/deleteUsers.php">'.$this->getLocalizedString("deleteUsers").'</a>
						</div>
					</li>';
			}
		}else{
			echo $browse . "</div></li>";
		}
		$switchLanglistdir = '../incl/lang';
		if (strpos($_SERVER[REQUEST_URI], 'home.php') !== false){
			$switchLanglistdir = 'incl/lang';
		}
		
include "../../config/main.php";
include "../config/main.php";

//Checking if levelToGD true
$levelToGDDropdownItem = '<a class="dropdown-item" aria-expanded="false" href="reupload/levelToGD.php">'.$this->getLocalizedString("levelToGD").'</a>';
if($levelToGD !== "true")
{$levelToGDDropdownItem = '';}

//Checking if songAdd true
$songAddDropdownItem = '<a class="dropdown-item" aria-expanded="false" href="reupload/songAdd.php">'.$this->getLocalizedString("songAdd").'</a>';
if($songAdd !== "true")
{$songAddDropdownItem = '';}

//Checking if levelReupload true
$levelReuploadDropdownItem = '<a class="dropdown-item" aria-expanded="false" href="reupload/levelReupload.php">'.$this->getLocalizedString("levelReupload").'</a>';
if($levelReupload !== "true")
{$levelReuploadDropdownItem = '';}

//Checking if levelReupload, songAdd, levelToGD is not true
$reuploadDropdown = '<li class="nav-item dropdown '.$reuploadActive.'">
						<a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="fa fa-upload" aria-hidden="true"></i> '.$this->getLocalizedString("reuploadSection").'
						</a>
						<div class="dropdown-menu dropdown-menu-offcanvas" aria-labelledby="navbarDropdownMenuLink">
							'.$levelToGDDropdownItem.'
							'.$songAddDropdownItem.'
							'.$levelReuploadDropdownItem.'
						</div>
					</li>';

if($levelToGD !== "true" AND $songAdd !== "true" AND $levelReupload !== "true")
{$reuploadDropdown = '';}

		echo '		'.$reuploadDropdown.'
					<li class="nav-item dropdown '.$statsActive.'">
						<a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="fa fa-bar-chart" aria-hidden="true"></i> '.$this->getLocalizedString("statsSection").'
						</a>
						<div class="dropdown-menu dropdown-menu-offcanvas" aria-labelledby="navbarDropdownMenuLink">
							<a class="dropdown-item" aria-expanded="false" href="stats/dailyTable.php">'.$this->getLocalizedString("dailyTable").'</a>
							<a class="dropdown-item" aria-expanded="false" href="stats/modActions.php">'.$this->getLocalizedString("modActions").'</a>
							<a class="dropdown-item" aria-expanded="false" href="stats/rateSuggestions.php">'.$this->getLocalizedString("rateSuggestions").'</a>
							<a class="dropdown-item" aria-expanded="false" href="stats/stats.php">'.$this->getLocalizedString("Main stats").'</a>
						</div>
					</li>
				</ul>
				<ul class="nav navbar-nav ml-auto">
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="fa fa-language" aria-hidden="true"></i> '.$this->getLocalizedString("language").'
						</a>
						<div class="dropdown-menu dropdown-menu-offcanvas" aria-labelledby="navbarDropdownMenuLink">
							'.listLang($switchLanglistdir).'
						</div>';
		if(isset($_SESSION["accountID"]) && $_SESSION["accountID"]){
			$userName = $gs->getAccountName($_SESSION["accountID"]);
			echo'<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="fa fa-user-circle" aria-hidden="true"></i> '.sprintf($this->getLocalizedString("loginHeader"), $userName).'
						</a>
						<div class="dropdown-menu dropdown-menu-offcanvas" aria-labelledby="navbarDropdownMenuLink">
							<a class="dropdown-item" aria-expanded="false" href="login/logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i> '.$this->getLocalizedString("logout").'</a>
						</div>
					</li>';
		}else{
			echo '<li class="nav-item dropdown '.$loginActive.'">
						<a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="fa fa-sign-in" aria-hidden="true"></i> '.$this->getLocalizedString("login").'
						</a>
						<div class="dropdown-menu dropdown-menu-offcanvas dropdown-menu-right dropdown-menu-navbar" aria-labelledby="navbarDropdownMenuLink" style="padding:17px;">
									<form action="login/login.php" method="post">
										<div class="form-group">
											<input type="text" class="form-control" id="usernameField" name="username" placeholder="Username">
											<br>
											<input type="password" class="form-control" id="passwordField" name="password" placeholder="Password">
										</div>
										<button type="submit" class="btn btn-primary btn-block">'.$this->getLocalizedString("login").'</button>
									</form>
										<a href="account/register.php" class="btn btn-primary btn-block">'.$this->getLocalizedString("register").'</a>
						</div>';
		}		
		echo'	</ul>
		<div class="position-absolute bottom-0 end-0" style="padding: 3%;font-size: unset">
		    <a target="_blank" href="'.$helpLink.'">Help</a>
		</div class="position-absolute bottom-0 end-0">
			</div>
		</div>
		</nav>';
	}
	//Print page
	public function printPage($content, $isSubdirectory = true, $navbar = "home"){
		$dl = new dashboardLib();
		$dl->printHeader($isSubdirectory);
		$dl->printNavbar($navbar);
		echo '<div class="container d-flex flex-column">
				<div class="row fill d-flex justify-content-start content buffer">
					'.$content.'
				</div>
			</div>';
		$dl->printFooter();
	}
	//Print full-content page
	public function printFullPage($content, $isSubdirectory = true, $navbar = "home"){
		$dl = new dashboardLib();
		$dl->printHeader($isSubdirectory);
		$dl->printNavbar($navbar);
		echo '<div class="container-fluid d-flex flex-column">
				<div class="row fill d-flex justify-content-start content buffer">
					'.$content.'
				</div>
			</div>';
		$dl->printFooter();
	}
	//Handle language
	public function handleLangStart(){
		if(!isset($_COOKIE["lang"]) || !ctype_alpha($_COOKIE["lang"])){
			setcookie("lang", "EN", 2147483647, "/");
		}
	}
	//Get strings
	public function getLocalizedString($stringName){
		if(!isset($_COOKIE["lang"]) || !ctype_alpha($_COOKIE["lang"])){
			$lang = "EN";
		}else{
			$lang = $_COOKIE["lang"];
		}
		$locale = __DIR__ . "/lang/locale".$lang.".php";
		if(file_exists($locale)){
			include $locale;
		}else{
			include __DIR__ . "/lang/localeEN.php";
		}
		if(isset($string[$stringName])){
			return $string[$stringName];
		}else{
			return "lnf:$stringName";
		}
	}
	//Convert date
	public function time_elapsed_string($datetime, $full = false) {
		$now = new DateTime;
		$ago = new DateTime($datetime);
		$diff = $now->diff($ago);
	
		$diff->w = floor($diff->d / 7);
		$diff->d -= $diff->w * 7;
	
		$string = array(
			'y' => $this->getLocalizedString("year"),
			'm' => $this->getLocalizedString("month"),
			'w' => $this->getLocalizedString("week"),
			'd' => $this->getLocalizedString("day"),
			'h' => $this->getLocalizedString("hour"),
			'i' => $this->getLocalizedString("minute"),
			's' => $this->getLocalizedString("second"),
		);
		foreach ($string as $k => &$v) {
			if ($diff->$k) {
				$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
			} else {
				unset($string[$k]);
			}
		}
	
		if (!$full) $string = array_slice($string, 0, 1);
		return $string ? implode(', ', $string) : $this->getLocalizedString("justnow");
	}
	public function convertToDate($timestamp){
		return $this->time_elapsed_string(date("Y-m-d H:i:s", $timestamp));
	}
	//Generate row
	public function generateBottomRow($pagecount, $actualpage){
		$pageminus = $actualpage - 1;
		$pageplus = $actualpage + 1;
		$bottomrow = '<div>'.sprintf($this->getLocalizedString("pageInfo"),$actualpage,$pagecount).'</div><div class="btn-group btn-group-sm" style="margin-left:auto; margin-right:0; overflow: auto;">';
		$bottomrow .= '<a id="first" href="'.strtok($_SERVER["REQUEST_URI"],'?').'?page=1&name='.$_GET["name"].'&type='.$_GET["type"].'&order='.$_GET["order"].'" class="btn btn-outline-secondary"><i class="fa fa-backward" aria-hidden="true"></i> '.$this->getLocalizedString("first").'</a><a id="prev" href="'.strtok($_SERVER["REQUEST_URI"],'?').'?page='. $pageminus .'&name='.$_GET["name"].'&type='.$_GET["type"].'&order='.$_GET["order"].'" class="btn btn-outline-secondary"><i class="fa fa-chevron-left" aria-hidden="true"></i> '.$this->getLocalizedString("previous").'</a>';
		//updated to ".."
		$bottomrow .= '<a class="btn btn-outline-secondary" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">..</a>
			<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink" style="padding:17px;">
				<form action="" method="get">
					<div class="form-group">
						<input type="number" class="form-control" value="'.$actualpage.'" min="1" max="'.$pagecount.'" name="page" placeholder="#">
						<input type="hidden" name="name" value="'.$_GET["name"].'">
						<input type="hidden" name="type" value="'.$_GET["type"].'">
						<input type="hidden" name="order" value="'.$_GET["order"].'">';
		foreach($_GET as $key => $param){
			if($key != "page"){
				$bottomrow .= '<input type="hidden" name="'.$key.'" value="'.$param.'">';
			}
		}
		$bottomrow .= '</div>
					<button type="submit" class="btn btn-primary btn-block">'.$this->getLocalizedString("go").'</button>
				</form>
			</div>';
		$bottomrow .= '<a href="'.strtok($_SERVER["REQUEST_URI"],'?').'?page='.$pageplus.'&name='.$_GET["name"].'&type='.$_GET["type"].'&order='.$_GET["order"].'" id="next" class="btn btn-outline-secondary">'.$this->getLocalizedString("next").' <i class="fa fa-chevron-right" aria-hidden="true"></i></a><a id="last" href="'.strtok($_SERVER["REQUEST_URI"],'?').'?page='. $pagecount .'&name='.$_GET["name"].'&type='.$_GET["type"].'&order='.$_GET["order"].'" class="btn btn-outline-secondary">'.$this->getLocalizedString("last").' <i class="fa fa-forward" aria-hidden="true"></i></a>';
		$bottomrow .= "</div><script>
			function disableElement(element){
				if(element){
					element.className += first.className ? ' disabled' : 'disabled';
				}
			}
			var pagecount = $pagecount;
			var actualpage = $actualpage;
			if(actualpage == 1){
				disableElement(document.getElementById('first'));
				disableElement(document.getElementById('prev'));
			}
			if(pagecount == actualpage){
				disableElement(document.getElementById('last'));
				disableElement(document.getElementById('next'));
			}
			</script>";
		return $bottomrow;
	}
	//Generate line chart
	public function generateLineChart($elementID, 
	$name1, $name2, $name3, $name4, $name5, $name6, $name7,
	$data1, $data2, $data3, $data4, $data5, $data6, $data7
	){
		$labels = implode('","', array_keys($data1));
		$data1 = implode(',', $data1);
		$data2 = implode(',', $data2);
		$data3 = implode(',', $data3);
		$data4 = implode(',', $data4);
		$data5 = implode(',', $data5);
		$data6 = implode(',', $data6);
		$data7 = implode(',', $data7);
		$chart = "<script>
					var ctx = document.getElementById(\"$elementID\");
					var myChart = new Chart(ctx, {
						type: 'line',
						data: {
							labels: [\"$labels\"],
							datasets: [{
								label: '$name1',
								data: [$data1],
								backgroundColor: [
									'rgba(255, 99, 132, 0.2)'
								],
								borderColor: [
									'rgba(255,99,132,1)'
								],
							},
							{
								label: '$name2',
								data: [$data2],
								backgroundColor: [
									'rgba(221, 153, 255, 0.2)'
								],
								borderColor: [
									'rgba(221, 153, 255, 1)'
								],
							},
							{
								label: '$name3',
								data: [$data3],
								backgroundColor: [
									'rgba(153, 168, 255, 0.2)'
								],
								borderColor: [
									'rgba(153, 168, 255, 1)'
								],
							},
							{
								label: '$name4',
								data: [$data4],
								backgroundColor: [
									'rgba(153, 255, 234, 0.2)'
								],
								borderColor: [
									'rgba(153, 255, 234, 1)'
								],
							},
							{
								label: '$name5',
								data: [$data5],
								backgroundColor: [
									'rgba(158, 255, 153, 0.2)'
								],
								borderColor: [
									'rgba(158, 255, 153, 1)'
								],
							},
							{
								label: '$name6',
								data: [$data6],
								backgroundColor: [
									'rgba(255, 249, 153, 0.2)'
								],
								borderColor: [
									'rgba(255, 249, 153, 1)'
								],
							},
							{
								label: '$name7',
								data: [$data7],
								backgroundColor: [
									'rgba(255, 192, 153, 0.2)'
								],
								borderColor: [
									'rgba(255, 192, 153, 1)'
								],
							}]
						},
						options: {
							responsive: true,
							maintainAspectRatio: false,
							scales: {
								yAxes: [{
									ticks: {
										beginAtZero:true
									}
								}]
							}
						}
					});
					</script>";
		return $chart;
	}
	public function OldGenerateLineChart($elementID, $name, $data){
		$labels = implode('","', array_keys($data));
		$data = implode(',', $data);
		$chart = "<script>
					var ctx = document.getElementById(\"$elementID\");
					var myChart = new Chart(ctx, {
						type: 'line',
						data: {
							labels: [\"$labels\"],
							datasets: [{
								label: '$name',
								data: [$data],
								backgroundColor: [
									'rgba(255, 99, 132, 0.2)'
								],
								borderColor: [
									'rgba(255,99,132,1)'
								],
							}]
						},
						options: {
							responsive: true,
							maintainAspectRatio: false,
							scales: {
								yAxes: [{
									ticks: {
										beginAtZero:true
									}
								}]
							}
						}
					});
					</script>";
		return $chart;
	}
}
?>
