<?php
//Requesting files
include "../../incl/lib/connection.php";
include "../../config/security.php";
require_once "../../incl/lib/exploitPatch.php";
require "../../incl/lib/generatePass.php";
require_once "../../incl/lib/mainLib.php";
require_once "../incl/dashboardLib.php";
$generatePass = new generatePass();
$dl = new dashboardLib();
$gs = new mainLib();

if($_POST["userName"] != "" AND $_POST["password"] != "" AND $_POST["passwordRep"] != ""){
	//here im getting all the data
	$userName = ExploitPatch::remove($_POST["userName"]);
	$password = ExploitPatch::remove($_POST["password"]);
	$passwordRep = ExploitPatch::remove($_POST["passwordRep"]);
	$email = ExploitPatch::remove($_POST["email"]);
	$secret = "";
	$ip = $gs->getIP();
	//checking if ip exist in db
	$queryIP = $db->prepare("SELECT * FROM accounts WHERE ip = :ip LIMIT 1");
    $queryIP->execute([':ip' => $ip]);
    $resultIP = $queryIP->fetch();
    if($MultipleAccountsWithSameIP !== 'true' AND $resultIP["ip"] == $ip){exit (
$dl->printBox('
<h1>'.$dl->getLocalizedString("registerAcc").'</h1>
<form action="" method="post">
    <div class="form-group">
    '.$dl->getLocalizedString("errorNoMultipleAccountsWithSameIP").'<br></div>
    <button type="submit" class="btn btn-primary btn-block">'.$dl->getLocalizedString("register").'</button>
</form>
')
);}
	//checking if password does not match passwordRep
	if($password !== $passwordRep){
		exit(
$dl->printBox('
<h1>'.$dl->getLocalizedString("registerAcc").'</h1>
<form action="" method="post">
<div class="form-group">
        <input required type="text" pattern ="^[a-zA-Z0-9]+$" maxlength="20" class="form-control" name="userName" value="'.$userName.'" placeholder="'.$dl->getLocalizedString("regUsername").'"><br>
        <input required type="password" maxlength="20" class="form-control" name="password" value="'.$password.'" placeholder="'.$dl->getLocalizedString("regPass").'"><br>
        <input required type="password" maxlength="20" class="form-control is-invalid" name="passwordRep" value="'.$passwordRep.'" placeholder="'.$dl->getLocalizedString("regRepPass").'">
        <div class="invalid-feedback">'.$dl->getLocalizedString("errorPasswordsNotMatch").'</div><br>
        <input type="email" class="form-control" id="passhere" name="email" value="'.$email.'" placeholder="'.$dl->getLocalizedString("email").'">
</div>
    <button type="submit" class="btn btn-primary btn-block">'.$dl->getLocalizedString("register").'</button>
</form>
'));}
	//checking if $userName is within the length limit
	if(strlen($userName) > 20){
		exit(
$dl->printBox('
<h1>'.$dl->getLocalizedString("registerAcc").'</h1>
<form action="" method="post">
<div class="form-group">
        <input required type="text" pattern ="^[a-zA-Z0-9]+$" maxlength="20" class="form-control is-invalid" name="userName" value="'.$userName.'" placeholder="'.$dl->getLocalizedString("regUsername").'">
        <div class="invalid-feedback">'.$dl->getLocalizedString("errorUsernameLimit").'</div><br>
        <input required type="password" pattern ="^[a-zA-Z0-9]+$" maxlength="20" class="form-control" name="password" value="'.$password.'" placeholder="'.$dl->getLocalizedString("regPass").'"><br>
        <input required type="password" pattern ="^[a-zA-Z0-9]+$" maxlength="20" class="form-control" name="passwordRep" value="'.$passwordRep.'" placeholder="'.$dl->getLocalizedString("regRepPass").'"><br>
        <input type="email" class="form-control" id="email" name="email" value="'.$email.'" placeholder="'.$dl->getLocalizedString("email").'">
</div>
    <button type="submit" class="btn btn-primary btn-block">'.$dl->getLocalizedString("register").'</button>
</form>
'));}
	//checking if name is taken
	$query2 = $db->prepare("SELECT count(*) FROM accounts WHERE userName LIKE :userName");
	$query2->execute([':userName' => $userName]);
	$regusrs = $query2->fetchColumn();
	if ($regusrs > 0) {
		exit($dl->printBox('
<h1>'.$dl->getLocalizedString("registerAcc").'</h1>
<form action="" method="post">
<div class="form-group">
        <input required type="text" pattern ="^[a-zA-Z0-9]+$" maxlength="20" class="form-control is-invalid" name="userName" value="'.$userName.'" placeholder="'.$dl->getLocalizedString("regUsername").'">
        <div class="invalid-feedback">'.$dl->getLocalizedString("errorUsernameTaken").'</div><br>
        <input required type="password" pattern ="^[a-zA-Z0-9]+$" maxlength="20" class="form-control" name="password" value="'.$password.'" placeholder="'.$dl->getLocalizedString("regPass").'"><br>
        <input required type="password" pattern ="^[a-zA-Z0-9]+$" maxlength="20" class="form-control" name="passwordRep" value="'.$passwordRep.'" placeholder="'.$dl->getLocalizedString("regRepPass").'"><br>
        <input type="email" class="form-control" id="email" name="email" value="'.$email.'" placeholder="'.$dl->getLocalizedString("email").'">
</div>
    <button type="submit" class="btn btn-primary btn-block">'.$dl->getLocalizedString("register").'</button>
</form>
'));
	}else{
		$hashpass = password_hash($password, PASSWORD_DEFAULT);
		$gjp2 = GeneratePass::GJP2hash($password);
		$query = $db->prepare("INSERT INTO accounts (userName, password, email, registerDate, isActive, ip, gjp2)
		VALUES (:userName, :password, :email, :time, :isActive, :ip, :gjp)");
		$query->execute([':userName' => $userName, ':password' => $hashpass, ':email' => $email, ':time' => time(), ':isActive' => 1, ':ip' => $ip, ':gjp' => $gjp2]);
		exit($dl->printBox('
<h1>'.$dl->getLocalizedString("registerAcc").'</h1>
    '.$dl->getLocalizedString("registered").'<br>
    <p>'.$dl->getLocalizedString("registeredInfo").' '.$GDPSname.'!</p>
    <a href="login/login.php?userName='.$userName.'"<button type="submit" class="btn btn-primary btn-block">'.$dl->getLocalizedString("goToLogin").'</button></a>
</form>
'));
	}
}

$dl->printBox('
<h1>'.$dl->getLocalizedString("registerAcc").'</h1>
<form action="" method="post">
    <div class="form-group">
        <input required type="text" pattern ="^[a-zA-Z0-9]+$" maxlength="20" class="form-control" name="userName" placeholder="'.$dl->getLocalizedString("regUsername").'"><br>
        <input required type="password" pattern ="^[a-zA-Z0-9]+$" maxlength="20" class="form-control" name="password" placeholder="'.$dl->getLocalizedString("regPass").'"><br>
        <input required type="password" pattern ="^[a-zA-Z0-9]+$" maxlength="20" class="form-control" name="passwordRep" placeholder="'.$dl->getLocalizedString("regRepPass").'"><br>
        <input type="email" class="form-control" id="email" name="email" placeholder="'.$dl->getLocalizedString("email").'">
    </div>
    <button type="submit" class="btn btn-primary btn-block">'.$dl->getLocalizedString("register").'</button>
</form>
');
?>