<?php
session_start();
require "incl/dashboardLib.php";
$dl = new dashboardLib();
include "../incl/lib/connection.php";
//First line chart
$levelsChart = array();
for($x = 7; $x >= 0;){
	$timeBefore = time() - (86400 * $x);
	$timeAfter = time() - (86400 * ($x + 1));
	$query = $db->prepare("SELECT count(*) FROM levels WHERE uploadDate < :timeBefore AND uploadDate > :timeAfter");
	$query->execute([':timeBefore' => $timeBefore, ':timeAfter' => $timeAfter]);
	switch($x){
		case 1:$identifier = sprintf($dl->getLocalizedString("dayAgo"), $x);break;
		case 0:$identifier = $dl->getLocalizedString("last24hours");break;
		default:$identifier = sprintf($dl->getLocalizedString("daysAgo"), $x);break;
	}
	$levelsChart[$identifier] = $query->fetchColumn(); $x--;
}
$commentsChart = array();
for($x = 7; $x >= 0;){
	$timeBefore = time() - (86400 * $x);
	$timeAfter = time() - (86400 * ($x + 1));
	$query = $db->prepare("SELECT count(*) FROM comments WHERE timestamp < :timeBefore AND timestamp > :timeAfter");
	$query->execute([':timeBefore' => $timeBefore, ':timeAfter' => $timeAfter]);
	switch($x){
		case 1:$identifier = sprintf($dl->getLocalizedString("dayAgo"), $x);break;
		case 0:$identifier = $dl->getLocalizedString("last24hours");break;
		default:$identifier = sprintf($dl->getLocalizedString("daysAgo"), $x);break;
	}
	$commentsChart[$identifier] = $query->fetchColumn(); $x--;
}
$ProfilePostsChart = array();
for($x = 7; $x >= 0;){
	$timeBefore = time() - (86400 * $x);
	$timeAfter = time() - (86400 * ($x + 1));
	$query = $db->prepare("SELECT count(*) FROM acccomments WHERE timestamp < :timeBefore AND timestamp > :timeAfter");
	$query->execute([':timeBefore' => $timeBefore, ':timeAfter' => $timeAfter]);
	switch($x){
		case 1:$identifier = sprintf($dl->getLocalizedString("dayAgo"), $x);break;
		case 0:$identifier = $dl->getLocalizedString("last24hours");break;
		default:$identifier = sprintf($dl->getLocalizedString("daysAgo"), $x);break;
	}
	$ProfilePostsChart[$identifier] = $query->fetchColumn(); $x--;
}
$messages = array();
for($x = 7; $x >= 0;){
	$timeBefore = time() - (86400 * $x);
	$timeAfter = time() - (86400 * ($x + 1));
	$query = $db->prepare("SELECT count(*) FROM messages WHERE timestamp < :timeBefore AND timestamp > :timeAfter");
	$query->execute([':timeBefore' => $timeBefore, ':timeAfter' => $timeAfter]);
	switch($x){
		case 1:$identifier = sprintf($dl->getLocalizedString("dayAgo"), $x);break;
		case 0:$identifier = $dl->getLocalizedString("last24hours");break;
		default:$identifier = sprintf($dl->getLocalizedString("daysAgo"), $x);break;
	}
	$messages[$identifier] = $query->fetchColumn(); $x--;
}
$levelsRated = array();
for($x = 7; $x >= 0;){
	$timeBefore = time() - (86400 * $x);
	$timeAfter = time() - (86400 * ($x + 1));
	$query = $db->prepare("SELECT count(*) FROM modactions WHERE `value2` != '0' AND timestamp < :timeBefore AND timestamp > :timeAfter");
	$query->execute([':timeBefore' => $timeBefore, ':timeAfter' => $timeAfter]);
	switch($x){
		case 1:$identifier = sprintf($dl->getLocalizedString("dayAgo"), $x);break;
		case 0:$identifier = $dl->getLocalizedString("last24hours");break;
		default:$identifier = sprintf($dl->getLocalizedString("daysAgo"), $x);break;
	}
	$levelsRated[$identifier] = $query->fetchColumn(); $x--;
}
$accsChart = array();
for($x = 7; $x >= 0;){
	$timeBefore = time() - (86400 * $x);
	$timeAfter = time() - (86400 * ($x + 1));
	$query = $db->prepare("SELECT count(*) FROM accounts WHERE registerDate < :timeBefore AND registerDate > :timeAfter");
	$query->execute([':timeBefore' => $timeBefore, ':timeAfter' => $timeAfter]);
	switch($x){
		case 1:$identifier = sprintf($dl->getLocalizedString("dayAgo"), $x);break;
		case 0:$identifier = $dl->getLocalizedString("last24hours");break;
		default:$identifier = sprintf($dl->getLocalizedString("daysAgo"), $x);break;
	}
	$accsChart[$identifier] = $query->fetchColumn(); $x--;
}
$actions = array();
for($x = 7; $x >= 0;){
	$timeBefore = time() - (86400 * $x);
	$timeAfter = time() - (86400 * ($x + 1));
	$query = $db->prepare("SELECT count(*) FROM actions WHERE timestamp < :timeBefore AND timestamp > :timeAfter");
	$query->execute([':timeBefore' => $timeBefore, ':timeAfter' => $timeAfter]);
	switch($x){
		case 1:$identifier = sprintf($dl->getLocalizedString("dayAgo"), $x);break;
		case 0:$identifier = $dl->getLocalizedString("last24hours");break;
		default:$identifier = sprintf($dl->getLocalizedString("daysAgo"), $x);break;
	}
	$actions[$identifier] = $query->fetchColumn(); $x--;
}

//Second line chart
$levelsChart2 = array();
$months = ["January","February","March","April","May","June","July","August","September","October","November","December"];$x = 0;
foreach($months as &$month){
	$x++;$nextMonthYear = date('Y');if($x == 12){$x = 0;$nextMonthYear++;}$nextMonth = $months[$x];$timeBefore = strtotime("first day of $month ".date('Y'));$timeAfter = strtotime("first day of $nextMonth ".$nextMonthYear);
	
	$query = $db->prepare("SELECT count(*) FROM levels WHERE uploadDate > :timeBefore AND uploadDate < :timeAfter");
	
	$query->execute([':timeBefore' => $timeBefore, ':timeAfter' => $timeAfter]);$amount = $query->fetchColumn();
	if($amount != 0){$month = $dl->getLocalizedString($month);
	$levelsChart2
	[$month] = $amount;}
}
$commentsChart2 = array();
$months = ["January","February","March","April","May","June","July","August","September","October","November","December"];$x = 0;
foreach($months as &$month){
	$x++;$nextMonthYear = date('Y');if($x == 12){$x = 0;$nextMonthYear++;}$nextMonth = $months[$x];$timeBefore = strtotime("first day of $month ".date('Y'));$timeAfter = strtotime("first day of $nextMonth ".$nextMonthYear);
	
	$query = $db->prepare("SELECT count(*) FROM comments WHERE timestamp > :timeBefore AND timestamp < :timeAfter");
	
	$query->execute([':timeBefore' => $timeBefore, ':timeAfter' => $timeAfter]);$amount = $query->fetchColumn();
	if($amount != 0){$month = $dl->getLocalizedString($month);
	$commentsChart2
	[$month] = $amount;}
}
$ProfilePostsChart2 = array();
$months = ["January","February","March","April","May","June","July","August","September","October","November","December"];$x = 0;
foreach($months as &$month){
	$x++;$nextMonthYear = date('Y');if($x == 12){$x = 0;$nextMonthYear++;}$nextMonth = $months[$x];$timeBefore = strtotime("first day of $month ".date('Y'));$timeAfter = strtotime("first day of $nextMonth ".$nextMonthYear);
	
	$query = $db->prepare("SELECT count(*) FROM acccomments WHERE timestamp > :timeBefore AND timestamp < :timeAfter");
	
	$query->execute([':timeBefore' => $timeBefore, ':timeAfter' => $timeAfter]);$amount = $query->fetchColumn();
	if($amount != 0){$month = $dl->getLocalizedString($month);
	$ProfilePostsChart2
	[$month] = $amount;}
}
$messages2 = array();
$months = ["January","February","March","April","May","June","July","August","September","October","November","December"];$x = 0;
foreach($months as &$month){
	$x++;$nextMonthYear = date('Y');if($x == 12){$x = 0;$nextMonthYear++;}$nextMonth = $months[$x];$timeBefore = strtotime("first day of $month ".date('Y'));$timeAfter = strtotime("first day of $nextMonth ".$nextMonthYear);
	
	$query = $db->prepare("SELECT count(*) FROM messages WHERE timestamp > :timeBefore AND timestamp < :timeAfter");
	
	$query->execute([':timeBefore' => $timeBefore, ':timeAfter' => $timeAfter]);$amount = $query->fetchColumn();
	if($amount != 0){$month = $dl->getLocalizedString($month);
	$messages2
	[$month] = $amount;}
}
$levelsRated2 = array();
$months = ["January","February","March","April","May","June","July","August","September","October","November","December"];$x = 0;
foreach($months as &$month){
	$x++;$nextMonthYear = date('Y');if($x == 12){$x = 0;$nextMonthYear++;}$nextMonth = $months[$x];$timeBefore = strtotime("first day of $month ".date('Y'));$timeAfter = strtotime("first day of $nextMonth ".$nextMonthYear);
	
	$query = $db->prepare("SELECT count(*) FROM modactions WHERE `value2` != '0' AND timestamp > :timeBefore AND timestamp < :timeAfter");
	
	$query->execute([':timeBefore' => $timeBefore, ':timeAfter' => $timeAfter]);$amount = $query->fetchColumn();
	if($amount != 0){$month = $dl->getLocalizedString($month);
	$levelsRated2
	[$month] = $amount;}
}
$accsChart2 = array();
$months = ["January","February","March","April","May","June","July","August","September","October","November","December"];$x = 0;
foreach($months as &$month){
	$x++;$nextMonthYear = date('Y');if($x == 12){$x = 0;$nextMonthYear++;}$nextMonth = $months[$x];$timeBefore = strtotime("first day of $month ".date('Y'));$timeAfter = strtotime("first day of $nextMonth ".$nextMonthYear);
	
	$query = $db->prepare("SELECT count(*) FROM accounts WHERE registerDate > :timeBefore AND registerDate < :timeAfter");
	
	$query->execute([':timeBefore' => $timeBefore, ':timeAfter' => $timeAfter]);$amount = $query->fetchColumn();
	if($amount != 0){$month = $dl->getLocalizedString($month);
	$accsChart2
	[$month] = $amount;}
}
$actions2 = array();
$months = ["January","February","March","April","May","June","July","August","September","October","November","December"];$x = 0;
foreach($months as &$month){
	$x++;$nextMonthYear = date('Y');if($x == 12){$x = 0;$nextMonthYear++;}$nextMonth = $months[$x];$timeBefore = strtotime("first day of $month ".date('Y'));$timeAfter = strtotime("first day of $nextMonth ".$nextMonthYear);
	
	$query = $db->prepare("SELECT count(*) FROM actions WHERE timestamp > :timeBefore AND timestamp < :timeAfter");
	
	$query->execute([':timeBefore' => $timeBefore, ':timeAfter' => $timeAfter]);$amount = $query->fetchColumn();
	if($amount != 0){$month = $dl->getLocalizedString($month);
	$actions2
	[$month] = $amount;}
}

//Print home page
if($dashboardImprovedChart == "true"){
    $genChart = 
				$dl->generateLineChart("Chart",
				$dl->getLocalizedString("levelsUploaded"),
				$dl->getLocalizedString("CommentsPosted"),
				$dl->getLocalizedString("profilePosts"),
				$dl->getLocalizedString("messagesSneded"),
				$dl->getLocalizedString("levelsRated"),
				$dl->getLocalizedString("actions"),
				$dl->getLocalizedString("accountsCreated"),
				$levelsChart,$commentsChart,$ProfilePostsChart,$messages,$levelsRated,$actions,$accsChart)
				.
				$dl->generateLineChart("Chart2",
				$dl->getLocalizedString("levelsUploaded"),
				$dl->getLocalizedString("CommentsPosted"),
				$dl->getLocalizedString("profilePosts"),
				$dl->getLocalizedString("messagesSneded"),
				$dl->getLocalizedString("levelsRated"),
				$dl->getLocalizedString("actions"),
				$dl->getLocalizedString("accountsCreated"),
				$levelsChart2,$commentsChart2,$ProfilePostsChart2,$messages2,$levelsRated2,$actions2,$accsChart2);
				}
if($dashboardImprovedChart !== "true"){
    $genChart = 
				$dl->OldGenerateLineChart("Chart",$dl->getLocalizedString("levelsUploaded"),$levelsChart)
				.
				$dl->OldGenerateLineChart("Chart2",$dl->getLocalizedString("levelsUploaded"),$levelsChart2);
				}
$dl->printFullPage('<p>'.sprintf($dl->getLocalizedString("welcome"), $GDPSname).'
				</p><br>
	<div class="row">
		<div class="col">
			<div class="chart-container">
						<canvas id="Chart" height="350"></canvas>
					</div>
		</div>
</div class="row">
<div class="row">
		<div class="col">
			<div class="chart-container">
						<canvas id="Chart2" height="350"></canvas>
					</div>
		</div>
	</div row>
				'.$genChart, false);

//alerts
$accountID = $_SESSION["accountID"];
echo "<div class='bottom-0 start-0' style='margin: 0 15px 0 15px;position: fixed;'>";
//msg get count
$query = $db->prepare("SELECT count(*) FROM messages WHERE toAccountID = :toAccountID AND isNew = 1");
$query->execute([':toAccountID' => $accountID]);
$msgcount = $query->fetchColumn();
//get friendreqs cont
$query = $db->prepare("SELECT count(*) FROM friendreqs WHERE toAccountID = :toAccountID AND isNew = 1");
$query->execute([':toAccountID' => $accountID]);
$friendreqscount = $query->fetchColumn();
//print alerts
 //msg
if($msgcount == 1) {
echo '<div class="alert alert-dismissable alert-info alert-dismissible animitslideInLeft fade show" role="alert" data-bs-dismiss="alert" aria-label="Close">
	    <h4>'.$dl->getLocalizedString("newmsg").'</h4>
	    '.$dl->getLocalizedString("newmsgfooter").'</div>';
}
if($msgcount > 1) {
echo '<div class="alert alert-dismissable alert-info alert-dismissible animitslideInLeft fade show" role="alert" data-bs-dismiss="alert" aria-label="Close">
	    <h4>'.$dl->getLocalizedString("newmsgs").'</h4>
	    '.sprintf($dl->getLocalizedString("newmsgsfooter"), $msgcount).'</div>';
}
 //friendreqs
if($friendreqscount == 1) {
echo '<div class="alert alert-dismissable alert-info alert-dismissible animitslideInLeft fade show" role="alert" data-bs-dismiss="alert" aria-label="Close">
	    <h4>'.$dl->getLocalizedString("newfriendreq").'</h4>
	    <span>'.$dl->getLocalizedString("newfriendreqfooter").'</span></div>';
}
if($friendreqscount > 1) {
echo '<div class="alert alert-dismissable alert-info alert-dismissible animitslideInLeft fade show" role="alert" data-bs-dismiss="alert" aria-label="Close">
	    <h4>'.$dl->getLocalizedString("newfriendreqs").'</h4>
	    <span>'.sprintf($dl->getLocalizedString("newfriendreqsfooter"), $friendreqscount).'</span></div>';
}
echo "</div class='bottom-0 start-0' style='margin: 0 15px 0 15px;position: fixed;'>";
?>
<style>
.alert {
    width: fit-content;
}
</style>