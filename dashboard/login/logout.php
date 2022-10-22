<?php
session_start();
unset($_SESSION["accountID"]);
require "../incl/dashboardLib.php";
$dl = new dashboardLib();
if(isset($_GET["deleted"])){
header("Location: ../");
}else{
    echo "<body onload='window.history.back()'>";
}
?>