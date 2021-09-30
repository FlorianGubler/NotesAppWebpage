<?php
include "config.php";
$loginfailed = false;

if (isset($_GET["logout"])) {
  setcookie("sessionid", time() - 3600);
  setcookie("sessionkey", time() - 3600);
  header("Location: index.php");
}

if (isset($_COOKIE["sessionkey"]) and isset($_COOKIE["sessionid"])) {
  $userdata = getUserData($_COOKIE["sessionkey"]);
  if ($userdata != false) {
    header("Location: " . $rootpath . "assets/sites/home.php");
  }
}

if (isset($_POST["login"]) and !empty($_POST["uname"]) and !empty($_POST["psw"])) {
  $userCheck = checkLogin($_POST["uname"], $_POST["psw"]);
  if ($userCheck != false) {
    $user = getUserData($userCheck);
    setcookie("sessionid", hash("sha256", $user->username . $_SERVER["REMOTE_ADDR"]));
    setcookie("sessionkey", $user->id);
    header("Location: " . $rootpath . "assets/sites/home.php");
  } else {
    $loginfailed = true;
  }
}
?>
<!doctype html>
<html lang="de">

<head>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.5.0/css/ol.css" type="text/css">
  <link rel="stylesheet" href="<?php echo $rootpath ?>assets/css/login.css" type="text/css">
  <link rel="shortcut icon" type="image/x-icon" href="<?php echo $rootpath ?>assets/img/icon.ico">
  <script src="<?php echo $rootpath ?>assets/js/fontawesome/all.js" data-auto-replace-svg></script> <!-- Fontawesome -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Noten - Login</title>
</head>

<body>
  <div id="login" class="modal">
    <form class="modal-content animate" action="" method="POST">
      <div class="imgcontainer">
        <img src="<?php echo $rootpath ?>assets/img/logo.png" alt="Avatar" class="avatar">
        <h2>Helsana Noten Tool</h2>
      </div>
      <div class="container">
        <label for="uname"><b>E-Mail</b></label>
        <input type="email" placeholder="E-Mail eingeben" name="uname" required>

        <label for="psw"><b>Password</b></label>
        <input type="password" placeholder="Passwort eingeben" name="psw" required>

        <button type="submit" name="login">Login</button>

        <?php if ($loginfailed) { ?>
          <p style='background-color: red;' id='login-response-false'>Login failed</p>
        <?php } ?>
      </div>

      <div class="container footer-container">
        <span class="psw"><a style="color: rgb(103, 103, 216); cursor: pointer;" href="<?php echo $rootpath ?>assets/sites/register.php">Registrieren</a></span>
      </div>
    </form>
  </div>
</body>

</html>