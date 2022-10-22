<body style="color: #000; background-color: #f8f9fa;">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
<br>
<?php
require_once "../../incl/lib/mainLib.php";
$gs = new mainLib();
include '../main.php';
include '../dailyChests.php';
session_start();
if ($_SESSION["admenusername"] !== $admenusername or $_SESSION["admenpassword"] !== $admenpassword) {header('Location: login.php');}
        echo '<div class="container-fluid">
        <form action="" method="post" autocomplete="off">
        <div class="row">
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-4">
					<h3 class="text-center">SMALL CHEST:</h3>
                    <div class="input-group mb-3"><label class="input-group-text">Min Orbs: </label>
                    <input required class="form-control" min=0 type="number" name="chest1minOrbs" value="'.$chest1minOrbs.'">
                    </div>
                    <div class="input-group mb-3"><label class="input-group-text">Max Orbs: </label>
                    <input required class="form-control" min=0 type="number" name="chest1maxOrbs" value="'.$chest1maxOrbs.'">
                    </div>
                    <div class="input-group mb-3"><label class="input-group-text">Min Diamonds: </label>
                    <input required class="form-control" min=0 type="number" name="chest1minDiamonds" value="'.$chest1minDiamonds.'">
                    </div>
                    <div class="input-group mb-3"><label class="input-group-text">Max Diamonds: </label>
                    <input required class="form-control" min=0 type="number" name="chest1maxDiamonds" value="'.$chest1maxDiamonds.'">
                    </div>
                    <div class="input-group mb-3"><label class="input-group-text">Items: </label>
                    <input required class="form-control" min=1 max=6 type="number" name="chest1items1" value="1">
                    <input required class="form-control" min=1 max=6 type="number" name="chest1items2" value="2">
                    <input required class="form-control" min=1 max=6 type="number" name="chest1items3" value="3">
                    <input required class="form-control" min=1 max=6 type="number" name="chest1items4" value="4">
                    <input required class="form-control" min=1 max=6 type="number" name="chest1items5" value="5">
                    <input required class="form-control" min=1 max=6 type="number" name="chest1items6" value="6">
                    </div>
                    <div class="input-group mb-3"><label class="input-group-text">Min Keys: </label>
                    <input required class="form-control" min=0 type="number" name="chest1minKeys" value="'.$chest1minKeys.'">
                    </div>
                    <div class="input-group mb-3"><label class="input-group-text">Max Keys: </label>
                    <input required class="form-control" min=0 type="number" name="chest1maxKeys" value="'.$chest1maxKeys.'">
                    </div>
				</div>
				<div class="col-md-4">
					<h3 class="text-center">BIG CHEST:</h3>
                    <div class="input-group mb-3"><label class="input-group-text">Min Orbs: </label>
                    <input required class="form-control" min=0 type="number" name="chest2minOrbs" value="'.$chest2minOrbs.'">
                    </div>
                    <div class="input-group mb-3"><label class="input-group-text">Max Orbs: </label>
                    <input required class="form-control" min=0 type="number" name="chest2maxOrbs" value="'.$chest2maxOrbs.'">
                    </div>
                    <div class="input-group mb-3"><label class="input-group-text">Min Diamonds: </label>
                    <input required class="form-control" min=0 type="number" name="chest2minDiamonds" value="'.$chest2minDiamonds.'">
                    </div>
                    <div class="input-group mb-3"><label class="input-group-text">Max Diamonds: </label>
                    <input required class="form-control" min=0 type="number" name="chest2maxDiamonds" value="'.$chest2maxDiamonds.'">
                    </div>
                    <div class="input-group mb-3"><label class="input-group-text">Items: </label>
                    <input required class="form-control" min=1 max=6 type="number" name="chest2items1" value="1">
                    <input required class="form-control" min=1 max=6 type="number" name="chest2items2" value="2">
                    <input required class="form-control" min=1 max=6 type="number" name="chest2items3" value="3">
                    <input required class="form-control" min=1 max=6 type="number" name="chest2items4" value="4">
                    <input required class="form-control" min=1 max=6 type="number" name="chest2items5" value="5">
                    <input required class="form-control" min=1 max=6 type="number" name="chest2items6" value="6">
                    </div>
                    <div class="input-group mb-3"><label class="input-group-text">Min Keys: </label>
                    <input required class="form-control" min=0 type="number" name="chest2minKeys" value="'.$chest2minKeys.'">
                    </div>
                    <div class="input-group mb-3"><label class="input-group-text">Max Keys: </label>
                    <input required class="form-control" min=0 type="number" name="chest2maxKeys" value="'.$chest2maxKeys.'">
                    </div>
				</div>
				<div class="col-md-4">
					<h3 class="text-center">REWARD TIMES (in seconds):</h3>
                    <div class="input-group mb-3"><label class="input-group-text">SMALL CHEST: </label>
                    <input required class="form-control" min=1 type="number" name="chest1wait" value="'.$chest1wait.'">
                    </div>
                    <div class="input-group mb-3"><label class="input-group-text">BIG CHEST: </label>
                    <input required class="form-control" min=1 type="number" name="chest2wait" value="'.$chest2wait.'">
                    </div>
				</div>
			</div class="row">
		</div>
	    <input type="hidden" name="generate" value="d">
		<div class="d-grid gap-2 col-6 mx-auto">
          <input class="btn btn-lg btn-primary" type="submit" value="Generate new config!">
        </div>
	</div>
	</form>
	</div class="container-fluid">';
    if (!empty($_POST["generate"])) {
    $dailyChestsphp = '<?php
/*
	REWARDS
*/
//SMALL CHEST
$chest1minOrbs = '.$_POST["chest1minOrbs"].';
$chest1maxOrbs = '.$_POST["chest1maxOrbs"].';
$chest1minDiamonds = '.$_POST["chest1minDiamonds"].';
$chest1maxDiamonds = '.$_POST["chest1maxDiamonds"].';
$chest1items = ['.$_POST["chest1items1"].', '.$_POST["chest1items2"].', '.$_POST["chest1items3"].', '.$_POST["chest1items4"].', '.$_POST["chest1items5"].', '.$_POST["chest1items6"].'];
$chest1minKeys = '.$_POST["chest1minKeys"].';
$chest1maxKeys = '.$_POST["chest1maxKeys"].';
//BIG CHEST
$chest2minOrbs = '.$_POST["chest2minOrbs"].';
$chest2maxOrbs = '.$_POST["chest2maxOrbs"].';
$chest2minDiamonds = '.$_POST["chest2minDiamonds"].';
$chest2maxDiamonds = '.$_POST["chest2maxDiamonds"].';
$chest1items = ['.$_POST["chest2items1"].', '.$_POST["chest2items2"].', '.$_POST["chest2items3"].', '.$_POST["chest2items4"].', '.$_POST["chest2items5"].', '.$_POST["chest2items6"].'];
$chest2minKeys = '.$gs->TimeToSec($_POST["chest2minKeys"]).';
$chest2maxKeys = '.$gs->TimeToSec($_POST["chest2maxKeys"]).';
//REWARD TIMES (in seconds)
$chest1wait = '.$_POST["chest1wait"].';
$chest2wait = '.$_POST["chest2wait"].';';
    file_put_contents("../dailyChests.php", $dailyChestsphp);
    echo '
    <h2>New config generated:</h2>
    <span class="text-muted">Reload page to regenerate form values</span>
    <div id="codebox">
    <pre><code data-language="html">
    <p>'.str_replace("<?php", "", file_get_contents("../dailyChests.php")).'</p>
    </code></pre>
    </div id="codebox">
    ';}
    
?>
<br>
<style>
code {
	font-family: Courier, sans-serif;
    font-size: 1em;
	line-height: 1.3;
}
</style>