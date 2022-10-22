<?php
include "../config/security.php";
include "../incl/lib/connection.php";
require_once "../incl/lib/exploitPatch.php";
require "../incl/lib/generatePass.php";
require_once "../incl/lib/mainLib.php";
$gs = new mainLib();

if($preventAddingNewData == 'true') {exit ('<div '.$styleForErrorAlert.'>Adding new data disabled by the administrator!</div>');}

if($_POST["userName"] != ""){
	//here im getting all the data
	$userName = ExploitPatch::remove($_POST["userName"]);
	$password = ExploitPatch::remove($_POST["password"]);
	$email = ExploitPatch::remove($_POST["email"]);
	$secret = "";
	$ip = $gs->getIP();
	//checking if ip exist in db
	$queryIP = $db->prepare("SELECT * FROM accounts WHERE ip = :ip LIMIT 1");
    $queryIP->execute([':ip' => $ip]);
    $resultIP = $queryIP->fetch();
    if($MultipleAccountsWithSameIP !== 'true' AND $resultIP["ip"] == $ip){exit ("You can register only one account from this IP! ❤️");}
	//checking if username is within the GD length limit
	if(strlen($userName) > 20)
		exit("-4");
	//checking if name is taken
	$query2 = $db->prepare("SELECT count(*) FROM accounts WHERE userName LIKE :userName");
	$query2->execute([':userName' => $userName]);
	$regusrs = $query2->fetchColumn();
	if ($regusrs > 0) {
		echo "-2";
	}else{
		$hashpass = password_hash($password, PASSWORD_DEFAULT);
		$gjp2 = GeneratePass::GJP2hash($password);
		$query = $db->prepare("INSERT INTO accounts (userName, password, email, registerDate, isActive, ip, gjp2)
		VALUES (:userName, :password, :email, :time, :isActive, :ip, :gjp)");
		$query->execute([':userName' => $userName, ':password' => $hashpass, ':email' => $email, ':time' => time(), ':isActive' => 1, ':ip' => $ip, ':gjp' => $gjp2]);
		echo "1";
	}
}
?>
