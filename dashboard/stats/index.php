<?php
//aeuaua
echo '
';
//req files
require_once "../incl/dashboardLib.php";
$dl = new dashboardLib();
//get files list
function listdir($dir){
	$dirstring = "";
	$files = scandir($dir);
	foreach($files as $file) {
		if(pathinfo($file, PATHINFO_EXTENSION) == "php" AND $file != "index.php"){
			$dirstring .= '<a class="dropdown-item" aria-expanded="false" href="stats/'.$file.'">'.str_replace(".php", "", $file).'</a>';
		}
	}
	return $dirstring;
}
//print page
$dl->printPage('
<div style="margin-right: auto;margin-left: auto;padding-top: 18vh;">
<div style="background-color: #fff;background-clip: border-box;border: 1px solid rgba(0,0,0,.125);border-radius: 0.25rem;font-size: xx-large;padding: 3vh 0vh 3vh;">
    '.listdir('.').'
</div>
</div>
');
?>