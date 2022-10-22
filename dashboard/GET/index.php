<?php
//aeuaua
echo '
';
//req files
require_once "../incl/dashboardLib.php";
$dl = new dashboardLib();
//print page
$dl->printPage('
<div class="container-fluid">
	<div class="row">
		<div class="col-md-3">
		</div>
		<div class="card"><div class="card-body">
		
		<form role="form" action="GET/level.php" method="get">
				<div class="form-group">
					 
					<label for="exampleInputPassword1">
						levelID:
					</label>
					<input type="number" value="1" min="1" name="levelID" class="form-control" id="number"/>
				</div>
				<button type="submit" class="btn btn-primary">GET</button>
		</form>
			
		<form role="form" action="GET/song.php" method="get">
				<div class="form-group">
					 
					<label for="exampleInputPassword1">
						songID:
					</label>
					<input type="number" value="1" min="1" name="songID" class="form-control" id="number"/>
				</div>
				<button type="submit" class="btn btn-primary">GET</button>
		</form>
		
		<form role="form" action="GET/user.php" method="get">
				<div class="form-group">
					 
					<label for="exampleInputPassword1">
						userID:
					</label>
					<input type="number" value="1" min="1" name="userID" class="form-control" id="number"/>
				</div>
				<button type="submit" class="btn btn-primary">GET</button>
		</form>
		
		</div class="card-body"></div class="card">
		<div class="col-md-3">
		</div>
	</div>
</div>
');
?>