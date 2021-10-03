<?php
include "../../config.php";
$passwordfalse = false;
$formfalse = false;
$serverfalse = false;

if (isset($_POST['submit-register'])) {
    if (!empty($_POST['new-password']) || !empty($_POST['fname']) || !empty($_POST['lname']) || !empty($_POST['email'])) {
        if ($_POST['new-password'] == $_POST['new-password-check']) {
            if (createUser($_POST['fname'] . " " . $_POST['lname'], $_POST['email'], $_POST['new-password'])) {
                header("Location: ../../index.php");
            } else {
                $serverfalse = true;
            }
        } else {
            $passwordfalse = true;
        }
    } else {
        $formfalse = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Helsana Noten Registrierung</title>
    <link rel="stylesheet" href="<?php echo $rootpath; ?>assets/css/register.css">
</head>

<body>
    <form autocomplete="off" id="register-form" action="" method="POST">
        <h1>Helsana Noten Registrierung</h1>

        <input name="fname" placeholder="Max" required>
        <input name="lname" placeholder="Muster" required>

        <input name="email" type="email" placeholder="E-Mail" autocomplete="off" required>

        <input name="new-password" type="password" placeholder="Passwort" autocomplete="off" required>
        <input name="new-password-check" type="password" placeholder="Passwort wiederholen" autocomplete="off" required>

        <?php
        if ($formfalse) {
            echo "<h6 style='color: red;'>* Ungültige Eingaben</h6>";
        }
        if ($passwordfalse) {
            echo "<h6 style='color: red;'>* Paasswörter stimmen nicht überein</h6>";
        }
        if ($serverfalse) {
            echo "<h6 style='color: red;'>* Etwas hat nicht geklappt, falls dies wiederholt passiert, kontaktiere bitten einen Admin</h6>";
        }
        ?>
        <button type="submit" name="submit-register">Registrieren</button>
    </form>
</body>

</html>