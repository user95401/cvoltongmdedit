<?php
include dirname(__FILE__)."/../../config/main.php";

$styleForErrorAlert = '
style="
    position: absolute;
    bottom: 1;
    left: 10;
    border: 1px solid maroon !important;
    color: #000;
    background: pink;
    margin: 0.5em 0 0.5em;
    padding: 10px 10px 10px 10px;
    cursor: help;
    border-radius: 5px;
    font-family: sans-serif;
"
';


//error_reporting(0);
@header('Content-Type: text/html; charset=utf-8');
$notConfigurated = '';
if ($dbusername == 'root' AND $dbpassword == '123456g' AND $dbname == 'gdps'){
$notConfigurated = 'It seems that the server was not configured, <b>Go to config/index.php page for configurate it!</b><br>
<meta name="viewport" content="width=device-width, initial-scale=1">';
}

if($maintenanceMode !== 'true')
{exit ('<div '.$styleForErrorAlert.'>Maintenance mode is disabled by the administrator!</div>');}
if($preventAddingNewData == 'true')
{exit ('<div '.$styleForErrorAlert.'>Contact with the database is disabled by the administrator!</div>');}

if(!isset($port))
	$port = 3306;
try {
    $db = new PDO("mysql:host=$dbservername;port=$dbport;dbname=$dbname", $dbusername, $dbpassword, array(
    PDO::ATTR_PERSISTENT => true
));
    // set the PDO error mode to exception
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
catch(PDOException $e)
    {
    echo '<div '.$styleForErrorAlert.'>'.$notConfigurated.' Connection failed: '.$e->getMessage().'</div>
    <meta name="viewport" content="width=device-width, initial-scale=1">';
    }

include dirname(__FILE__)."/cron.php";
?>