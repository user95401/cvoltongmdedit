<body style="color: #000; background-color: #f8f9fa;">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
<?php
include '../main.php';
if (empty($admenusername) and empty($admenpassword)) {
if (!empty($_POST["admenusername"]) and !empty($_POST["admenpassword"])) {
$towriteadmencreds = '
//adminLogin
$admenusername = "'.$_POST["admenusername"].'";
$admenpassword = "'.$_POST["admenpassword"].'";';
file_put_contents("../main.php", file_get_contents("../main.php").$towriteadmencreds);
session_start();
$_SESSION['admenusername'] = $_POST["admenusername"];
$_SESSION['admenpassword'] = $_POST["admenpassword"];
header('Location: confgenerator.php');
}
else {
  echo '<div class="container-fluid">
    <div class="card position-absolute top-50 start-50 translate-middle" style="width: 30rem;" >
    <div class="card-body">
    <h5 class="card-title">Create admin user and password</h5>
    <form action="" method="post" autocomplete="on">
    <div class="input-group mb-3"><label class="input-group-text">User: </label>
    <input required autofocus class="form-control" type="text" name="admenusername" autocomplete="user">
    </div>
    <div class="input-group mb-3"><label class="input-group-text">Password: </label>
    <input required class="form-control" type="password" name="admenpassword" autocomplete="password">
    </div>
    <input value="Register" type="submit" class="form-control btn btn-primary">
    </form>
    </div class="card-body">
    </div class="card" style="width: 30rem;">
    </div class="container-fluid">';
}
}else{
    header('Location: confgenerator.php');
}
?>
<script>
const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
</script>