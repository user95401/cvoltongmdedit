<body style="color: #000; background-color: #f8f9fa;">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
<?php
include 'main.php';
session_start();
if ($_SESSION["admenusername"] !== $admenusername or $_SESSION["admenpassword"] !== $admenpassword) {header('Location: configgen/login.php');}
if ($_SESSION["admenusername"] == $admenusername or $_SESSION["admenpassword"] == $admenpassword) {
    echo '<div class="container-fluid">
        <div class="card position-absolute top-50 start-50 translate-middle" style="width: 20rem;" >
        <div class="card-body">
        <h1 class="card-title text-center">Configs:</h1>
        <div class="d-grid gap-2 mx-auto">
        <a class="form-control btn btn-primary btn-lg" href="configgen/confgenerator.php">Main</a>
        <a class="form-control btn btn-primary btn-lg" href="configgen/dailyChestsgenerator.php">REWARDS</a>
        </div class="d-grid gap-2 mx-auto">
        </div class="card-body">
        </div class="card" style="width: 30rem;">
        </div class="container-fluid">';
    //alerts
    echo "<div class='position-absolute bottom-0 start-0' style='margin: 0 1% 0 1%;'>";
    if ($preventAddingNewData == "true") {
    echo '<div class="alert alert-dismissable alert-danger alert-dismissible fade show" role="alert">
				<h4>preventAddingNewData: '.$preventAddingNewData.'</h4><hr> <strong>Blocked much of the features, such as: unloading levels, comments, posts, updating user stats, adding a song, reloading levels, etc.</strong>
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    }
    if ($maintenanceMode !== "true") {
    echo '<div class="alert alert-dismissable alert-danger alert-dismissible fade show" role="alert">
				<h4>maintenanceMode: '.$maintenanceMode.'</h4><hr> <strong>Contact with the database is blocked!</strong>
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    }
    //last visit alert
    include "configgen/lastvisit.php";
    echo '<div class="alert alert-dismissable alert-info alert-dismissible fade show" role="alert">
				<h4>Last visit:</h4><hr> <strong>'.$lastvisit.'</strong>
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    $lastvisitphp = '<?php
    $lastvisit = "IP: '.getenv("REMOTE_ADDR").' / At '.date("d.m.Y",time()).' '.date("H:i:s",time()).' / '.getenv("HTTP_USER_AGENT").'";
    ?>';
    file_put_contents("configgen/lastvisit.php", $lastvisitphp);
    
    echo "</div class='position-absolute bottom-0 start-0'>";
}else{
    header('Location: configgen/login.php');
}
?>
<style>
code {
	font-family: Courier, sans-serif;
    font-size: 1em;
	line-height: 1.3;
}
.alert {
    width: fit-content;
}
</style>
<script>
const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
</script>
