<?php
//Checking if logged in
session_start();
if(!isset($_SESSION["accountID"]) OR $_SESSION["accountID"] == 0){
	header("Location: ../login/login.php");
	exit();
}
//Requesting files
include "../../incl/lib/connection.php";
require_once "../incl/dashboardLib.php";
require_once "../../incl/lib/mainLib.php";
require_once "../../incl/lib/exploitPatch.php";
$gs = new mainLib();
$dl = new dashboardLib();
$ep = new exploitPatch();
//Checking seeLogs permission
if(!$gs->checkPermission($_SESSION["accountID"], "seeLogs"))
$log = date('Y-m-d H:i:s') . 'sex' . $gs->getUserName($_SESSION["accountID"]);
file_put_contents(__DIR__ . '../log.txt', $log . PHP_EOL, FILE_APPEND);
?>