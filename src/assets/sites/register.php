<?php
include "../../config.php";
$passwordfalse = false;
$formfalse = false;

if (isset($_POST['submit-register'])) {
    if (!empty($_POST['new-password']) || !empty($_POST['fname']) || !empty($_POST['lname']) || !empty($_POST['email'])) {
        if ($_POST['new-password'] == $_POST['new-password-check']) {
            $sql = "INSERT INTO users (username, email, passwordhash) VALUES ('" . $_POST['fname'] . " " . $_POST['lname'] . "', '" . $_POST['email'] . "', '" . hash('sha256', $_POST['email'] . $_POST['new-password']) . "')";
            if ($conn->query($sql)) {
                $receiver = $_POST["email"];
                $subject = 'Confirm Your Email';
                $message = str_replace("XXXXXXmailXXXXXX", $receiver, file_get_contents("dbapi/confirm_email_mailsite.html"));
                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                $headers .= 'From: verify@helsananotes.ch' . "\r\n";
                $headers .=  'X-Mailer: PHP/' . phpversion();

                if (mail($receiver, $subject, $message, $headers)) {
                    echo "<script>window.close();</script>";
                }
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
        ?>
        <button type="submit" name="submit-register">Registrieren</button>
    </form>
</body>

</html>