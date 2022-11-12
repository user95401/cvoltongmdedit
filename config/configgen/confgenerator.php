<body style="color: #000; background-color: #f8f9fa;">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
<br>
<?php
include '../main.php';
session_start();
if ($_SESSION["admenusername"] !== $admenusername or $_SESSION["admenpassword"] !== $admenpassword) {header('Location: login.php');}
    if (!empty($_POST["generate"])) {
	// lets to test db connect data
    $mysqli = new mysqli($_POST["host"], $_POST["user"], $_POST["password"], $_POST["database"], $_POST["port"]);
    if (mysqli_connect_error()) {
		/* he must see this!11 */
        $dbconalert = '<div class="alert alert-dismissible alert-danger alert-dismissible fade show" role="alert" id="alert">
				<h4>Connection is failed!</h4> Fail message: <strong>'.mysqli_connect_error().'</strong>
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    } //db connect data is good! ok i do it
	else{
    $mainphp = '<?php
//database connection
$dbservername = "'.$_POST["host"].'"; //localhost?
$dbport = '.$_POST["port"].';
$dbusername = "'.$_POST["user"].'";
$dbpassword = "'.$_POST["password"].'";
$dbname = "'.$_POST["database"].'";

$GDPSname = "'.$_POST["GDPSname"].'"; //project title
$helpLink = "'.$_POST["helpLink"].'"; //help page, group, discord server and other
$projectIcon = "'.$_POST["projectIcon"].'"; //project icon

$levelToGD = "'.$_POST["levelToGD"].'"; //set false if you dont allow users upload levels to another servers
$levelReupload = "'.$_POST["levelReupload"].'"; //set false if you dont allow users upload levels FROM another servers
$songAdd = "'.$_POST["songAdd"].'"; //set false if you do not allow non-NG songs system
$songAdd_customName = "'.$_POST["songAdd_customName"].'"; //set false if you do not allow set a custom song name for non-NG songs system
$songAdd_customAuthor = "'.$_POST["songAdd_customAuthor"].'"; //set false if you do not allow set a custom author name for non-NG songs system
$dashboardImprovedChart = "'.$_POST["dashboardImprovedChart"].'"; //gooodLuck
$MultipleAccountsWithSameIP = "'.$_POST["MultipleAccountsWithSameIP"].'"; //set false if you do not allow register accounts with the same IP
$deleteRatedLevels = "'.$_POST["deleteRatedLevels"].'"; //set true to allow level owners to delete them anyway
$autoWeeklyAndDaily = "'.$_POST["autoWeeklyAndDaily"].'"; //randomly automatically adds Weekly and Daily levels when the moderator rate it
$autoWeeklyAndDailyChance = '.$_POST["autoWeeklyAndDailyChance"].'; // ^
$redirectTopArtist = "'.$_POST["redirectTopArtist"].'"; //Indicates wether the server should ask the main GD servers for the top artists list or not.

//cron
$ABstars = '.$_POST["ABstars"].'; //default value (for autoban counting)
$ABcoins = '.$_POST["ABcoins"].'; //default value (for autoban counting)
$ABpc = '.$_POST["ABpc"].'; //user coins default value (for autoban counting)
$ABdemons = '.$_POST["ABdemons"].'; //default values (for autoban counting)
$autoban = "'.$_POST["autoban"].'";
$fixCPs = "'.$_POST["fixCPs"].'";
$fixnames = "'.$_POST["fixnames"].'";
$friendsLeaderboardCron = "'.$_POST["friendsLeaderboardCron"].'";
$deleteUnnecessary = "'.$_POST["deleteUnnecessary"].'"; //delete worong songs and users / Fix levels with invalid passwords

//DANGER ZONE! I DONT RECOMMEND TOUCHING THIS
$preventAddingNewData = "'.$_POST["preventAddingNewData"].'"; //set true to prevent adding new data (this will block much of the features, such as: unloading levels, comments, posts, updating user stats, adding a song, reloading levels, etc.)
$maintenanceMode = "'.$_POST["maintenanceMode"].'"; //set false to to prevent contact with the database

//adminLogin
$admenusername = "'.$_POST["admenusername"].'";
$admenpassword = "'.$_POST["admenpassword"].'";';
    file_put_contents("../main.php", $mainphp);
    $newcfginf = '
    <h2>New config generated:</h2>
    <div id="codebox">
    <pre><code data-language="html">
    <p>'.str_replace("<?php", "", file_get_contents("../main.php")).'</p>
    </code></pre>
    </div id="codebox">
    ';
	} //end of generate
 } //end of if non empty POST["generate"]
        echo '
        <div class="container-fluid">
        <form action="" method="post" autocomplete="on">
        <div class="row">
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-4">
					<h3 class="text-center">Database connection:</h3>
					<div class="input-group mb-3">
                    <label class="input-group-text">Host: </label>
                    <input required class="form-control" type="text" name="host" value="'.$dbservername.'" autocomplete="host">
                    </div>
                    <div class="input-group mb-3"><label class="input-group-text">User: </label>
                    <input required class="form-control" type="text" name="user" value="'.$dbusername.'" autocomplete="user">
                    </div>
                    <div class="input-group mb-3"><label class="input-group-text">Password: </label>
                    <input required class="form-control" type="password" name="password" value="'.$dbpassword.'" autocomplete="password">
                    </div>
                    <div class="input-group mb-3"><label class="input-group-text">Database name: </label>
                    <input required class="form-control" type="text" name="database" value="'.$dbname.'" autocomplete="database">
                    </div>
                    <div class="input-group mb-3"><label class="input-group-text">Port: </label>
                    <input required class="form-control" type="number" name="port" value="'.$dbport.'" autocomplete="port">
                    </div>
					'.$dbconalert.'
					<h3 class="text-center text-danger">DANGER ZONE:</h3>
					<div class="input-group mb-3" data-bs-toggle="tooltip" data-bs-title="set true to prevent adding new data (this will block much of the features, such as: unloading levels, comments, posts, updating user stats, adding a song, reloading levels, etc.)" data-bs-placement="bottom">
					<label class="input-group-text is-invalid">preventAddingNewData: </label>
                    <input required class="form-control is-invalid" type="text" pattern="true|false" name="preventAddingNewData" value="'.$preventAddingNewData.'">
                    </div>
					<div class="input-group mb-3" data-bs-toggle="tooltip" data-bs-title="set false to to prevent contact with the database" data-bs-placement="bottom">
					<label class="input-group-text is-invalid">maintenanceMode: </label>
                    <input required class="form-control is-invalid" type="text" pattern="true|false" name="maintenanceMode" value="'.$maintenanceMode.'">
                    </div>
					<h3 class="text-center">ADMIN LOGIN:</h3>
					<div class="input-group mb-3">
					<label class="input-group-text is-invalid">username: </label>
                    <input required class="form-control" type="text" name="admenusername" value="'.$_SESSION['admenusername'].'">
                    </div>
					<div class="input-group mb-3">
					<label class="input-group-text is-invalid">password: </label>
                    <input required class="form-control" type="password" name="admenpassword" value="'.$_SESSION['admenpassword'].'">
                    </div>
				</div>
				<div class="col-md-4">
					<h3 class="text-center">Togglers:</h3>
					<h5 class="text-center text-muted">set false or true</h5>
					<div class="input-group mb-3" data-bs-toggle="tooltip" data-bs-title="set false if you dont allow users upload levels to another servers" data-bs-placement="bottom">
					<label class="input-group-text">levelToGD: </label>
                    <input required class="form-control" type="text" pattern="true|false" name="levelToGD" value="'.$levelToGD.'">
                    </div>
					<div class="input-group mb-3" data-bs-toggle="tooltip" data-bs-title="set false if you dont allow users upload levels FROM another servers" data-bs-placement="bottom">
					<label class="input-group-text">levelReupload: </label>
                    <input required class="form-control" type="text" pattern="true|false" name="levelReupload" value="'.$levelReupload.'">
                    </div>
					<div class="input-group mb-3" data-bs-toggle="tooltip" data-bs-title="set false if you do not allow non-NG songs system" data-bs-placement="bottom">
					<label class="input-group-text">songAdd: </label>
                    <input required class="form-control" type="text" pattern="true|false" name="songAdd" value="'.$songAdd.'">
                    </div>
					<div class="input-group mb-3" data-bs-toggle="tooltip" data-bs-title="false if you do not allow set a custom song name for non-NG songs system" data-bs-placement="bottom">
					<label class="input-group-text">songAdd_customName: </label>
                    <input required class="form-control" type="text" pattern="true|false" name="songAdd_customName" value="'.$songAdd_customName.'">
                    </div>
					<div class="input-group mb-3" data-bs-toggle="tooltip" data-bs-title="set false if you do not allow set a custom author name for non-NG songs system" data-bs-placement="bottom">
					<label class="input-group-text">songAdd_customAuthor: </label>
                    <input required class="form-control" type="text" pattern="true|false" name="songAdd_customAuthor" value="'.$songAdd_customAuthor.'">
                    </div>
					<div class="input-group mb-3" data-bs-toggle="tooltip" data-bs-title="yo" data-bs-placement="bottom">
					<label class="input-group-text">dashboardImprovedChart: </label>
                    <input required class="form-control" type="text" pattern="true|false" name="dashboardImprovedChart" value="'.$dashboardImprovedChart.'">
                    </div>
					<div class="input-group mb-3" data-bs-toggle="tooltip" data-bs-title="set false if you do not allow register accounts with the same IP" data-bs-placement="bottom">
					<label class="input-group-text">MultipleAccountsWithSameIP: </label>
                    <input required class="form-control" type="text" pattern="true|false" name="MultipleAccountsWithSameIP" value="'.$MultipleAccountsWithSameIP.'">
                    </div>
					<div class="input-group mb-3" data-bs-toggle="tooltip" data-bs-title="set true to allow level owners to delete them anyway" data-bs-placement="bottom">
					<label class="input-group-text">deleteRatedLevels: </label>
                    <input required class="form-control" type="text" pattern="true|false" name="deleteRatedLevels" value="'.$deleteRatedLevels.'">
                    </div>
					<div class="input-group mb-3" data-bs-toggle="tooltip" data-bs-title="randomly automatically adds Weekly and Daily levels when the moderator rate it" data-bs-placement="bottom">
					<label class="input-group-text">autoWeeklyAndDaily: </label>
                    <input required class="form-control" type="text" pattern="true|false" name="autoWeeklyAndDaily" value="'.$autoWeeklyAndDaily.'">
                    <input required class="form-control" type="number" data-bs-toggle="tooltip" data-bs-title="Chance 1-100" min=1 max=100 data-bs-placement="bottom" name="autoWeeklyAndDailyChance" value="'.$autoWeeklyAndDailyChance.'">
                    </div>
					<div class="input-group mb-3" data-bs-toggle="tooltip" data-bs-title="Indicates wether the server should ask the main GD servers for the top artists list or not." data-bs-placement="bottom">
					<label class="input-group-text" >redirectTopArtist: </label>
                    <input required class="form-control" type="text" pattern="true|false" name="redirectTopArtist" value="'.$redirectTopArtist.'">
                    </div>
				</div>
				<div class="col-md-4">
					<h3 class="text-center">CRON:</h3>
					<h6>Autoban defaults:</h6>
					<div class="input-group mb-3" data-bs-toggle="tooltip" data-bs-title="start stars counting from:" data-bs-placement="bottom">
					<label class="input-group-text">Stars: </label>
					<input min=0 name="ABstars" value="'.$ABstars.'" required class="form-control" type="number">
					</div>
					<div class="input-group mb-3" data-bs-toggle="tooltip" data-bs-title="start coins counting from:" data-bs-placement="bottom">
					<label class="input-group-text">Coins: </label>
					<input min=0 name="ABcoins" value="'.$ABcoins.'" required class="form-control" type="number">
					</div>
					<div class="input-group mb-3" data-bs-toggle="tooltip" data-bs-title="start usercoins counting from:" data-bs-placement="bottom"> 
					<label class="input-group-text">Usercoins: </label>
					<input min=0 name="ABpc" value="'.$ABpc.'" required class="form-control" type="number">
					</div>
					<div class="input-group mb-3" data-bs-toggle="tooltip" data-bs-title="start demons counting from:" data-bs-placement="bottom">
					<label class="input-group-text">Demons: </label>
					<input min=0 name="ABdemons" value="'.$ABdemons.'" required class="form-control" type="number">
					</div>
					<br>
					<div class="input-group mb-3" data-bs-toggle="tooltip" data-bs-title="ban users widh bad stats" data-bs-placement="bottom">
					<label class="input-group-text">autoban: </label>
                    <input required class="form-control" type="text" pattern="true|false" name="autoban" value="'.$autoban.'">
                    </div>
					<div class="input-group mb-3" data-bs-toggle="tooltip" data-bs-title="give creator points" data-bs-placement="bottom">
					<label class="input-group-text">fixCPs: </label>
                    <input required class="form-control" type="text" pattern="true|false" name="fixCPs" value="'.$fixCPs.'">
                    </div>
					<div class="input-group mb-3" data-bs-toggle="tooltip" data-bs-title="fix/deliverance bad names" data-bs-placement="bottom">
					<label class="input-group-text">fixnames: </label>
                    <input required class="form-control" type="text" pattern="true|false" name="fixnames" value="'.$fixnames.'">
                    </div>
					<div class="input-group mb-3" data-bs-toggle="tooltip" data-bs-title="yes" data-bs-placement="bottom">
					<label class="input-group-text">friendsLeaderboardCron: </label>
                    <input required class="form-control" type="text" pattern="true|false" name="friendsLeaderboardCron" value="'.$friendsLeaderboardCron.'">
                    </div>
					<div class="input-group mb-3" data-bs-toggle="tooltip" data-bs-title="delete worong songs and users / Fix levels with invalid passwords and other" data-bs-placement="bottom">
					<label class="input-group-text">deleteUnnecessary: </label>
                    <input required class="form-control" type="text" pattern="true|false" name="deleteUnnecessary" value="'.$deleteUnnecessary.'">
                    </div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<h3>Branding/names/other:</h3>
					<div class="input-group mb-3" data-bs-toggle="tooltip" data-bs-title="project title" data-bs-placement="bottom">
					<label class="input-group-text">GDPSname: </label>
                    <input required class="form-control" type="text" name="GDPSname" value="'.$GDPSname.'">
                    </div>
					<div class="input-group mb-3" data-bs-toggle="tooltip" data-bs-title="help page, group, discord server and other" data-bs-placement="bottom">
					<label class="input-group-text">helpLink: </label>
                    <input required class="form-control" type="url" name="helpLink" value="'.$helpLink.'">
                    </div>
					<div class="input-group mb-3">
					<label class="input-group-text">projectIcon: </label>
                    <input required class="form-control" type="url" name="projectIcon" value="'.$projectIcon.'">
                    <label class="input-group-text"><img width="64" height="64" src="'.$projectIcon.'"></label>
                    </div>
				</div>
			</div>
		</div>
	    <input type="hidden" name="generate" value="e">
		<div class="d-grid gap-2 col-6 mx-auto">
          <input class="btn btn-lg btn-primary" type="submit" value="Generate new config!">
        </div>
	</div>
	</form>
	'.$newcfginf.'
	</div class="container-fluid">';
?>
<br>
<style>
code {
	font-family: Courier, sans-serif;
    font-size: 1em;
	line-height: 1.3;
}
</style>
<script>
const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
</script>