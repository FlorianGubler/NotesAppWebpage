<?php
require_once("../../config.php");

if (isset($_COOKIE["sessionkey"]) and isset($_COOKIE["sessionid"])) {
    $userdata = getUserData($_COOKIE["sessionkey"]);
    if (hash("sha256", $userdata->username . $_SERVER["REMOTE_ADDR"]) == $_COOKIE["sessionid"]) {
        $user = $userdata;
        $login = true;
    } else {
        header("Location: " . $rootpath . "index.php?logout=true");
    }
} else {
    header("Location: " . $rootpath . "index.php?logout=true");
}

$uploadstatus = null;
if (isset($_POST["submit-usr"])) {
    $uploadstatus = setUsername($user, $_POST["newusername"], $_POST["pswnewusername"]);
}
if (isset($_POST["submit-email"])) {
    $uploadstatus = setEmail($user, $_POST["newemail"], $_POST["pswnewemail"]);
}
if (isset($_POST["submit-paswd"])) {
    $uploadstatus = setPassword($user, $_POST["oldpassword"], $_POST["newpassword"]);
}
if (isset($_FILES["upload-new-pb"])) {
    uploadPB($user->id, $_FILES["upload-new-pb"], $_POST["upload-new-pb-data"]);
    header("Location: " . $_SERVER["PHP_SELF"]);
}
if ($uploadstatus) {
    header("Location: " . $rootpath . "index.php?logout=true");
}

include "navbar.php";
?>
<script src="<?php echo $rootpath; ?>assets/js/cropprjs/croppr.js"></script>
<link rel="stylesheet" href="<?php echo $rootpath; ?>assets/css/configuser.css">
<link rel="stylesheet" href="<?php echo $rootpath; ?>assets/css/croppr.css">
<div id="upload-pb-container">
    <div class="uploadpb-title-container">
        <h2>Profilbild bearbeiten</h2>
        <button onmouseover="handleuploadPB_informationTag(true);" onmouseout="handleuploadPB_informationTag(false);"><i class="fas fa-info-circle"></i></button>
        <div id="upload-pb-information">
            <p>Eventuell muss die Seite neu geladen werden, wenn das neue Profilbild gesetzt wurde</p>
        </div>
    </div>
    <div class="upload-pb-inner-container">
        <div class="exit-upload-container">
            <button id="uploadpb_quit"><i class="fas fa-times"></i></button>
        </div>
        <div class="edit-image-container">
            <div id="uploadpb-preview-container" class="uploadpb-cropper-image-container">
                <img id="upload-pb-preview-cropper" src="<?php echo $rootpath . "assets/img/profilepictures/" . $user->profilepicture; ?>">
            </div>
            <div class="resize-image-slider-container">
                <button id="slider-resize-reset"><i class="fas fa-eraser"></i></button>
                <div id="big-image-resize"><i class="fas fa-image"></i></div>
                <input type="range" id="slider-resize-image" class="resize-image-slider" step="0.01" max="1" min="0.75" value="1">
                <div id="little-image-resize"><i class="fas fa-image"></i></div>
            </div>
        </div>
        <button id="submit-changed-image"><i class="fas fa-check"></i> Neues Profilbild bestätigen</button>
    </div>
</div>
<div id="configuser" class="modal">
    <div class="modal-content animate">
        <div class="imgcontainer">
            <img id="profile-pic" src="<?php echo $rootpath . "assets/img/profilepictures/" . $user->profilepicture; ?>" alt="Avatar" class="avatar" draggable="false" onerror="this.src = '<?php echo $rootpath ?>assets/img/profilepictures/defaultpb.jpg">
            <button class="upload-pb-select-picture" type="button" onclick="document.getElementById('pb-upload-hidden').click();"><i class="fas fa-pencil-alt"></i> Bearbeiten</button>
            <form action="" method="POST" id="upload-new-pb-form" enctype="multipart/form-data">
                <input type="file" name="upload-new-pb" id="pb-upload-hidden" onchange="loadPreviewPB();" accept="image/png, image/jpeg, image/gif">
                <input type="hidden" name="upload-new-pb-data" id="pb-upload-data">
            </form>
            <p style="display: inline;" class="use-user-name"><?php echo $user->username; ?></p>
            <p style="display: inline; opacity: 0.5; font-size: x-small; padding-top: 0px;" class="use-user-email"><?php echo $user->email; ?></p>
        </div>
        <div class="container">
            <?php
            if (isset($uploadstatus)) {
                if (!$uploadstatus) {
            ?>
                    <p class='upload-response-message upload-false'>Sorry, etwas hat nicht geklappt, bei widerholtem Auftreten kontaktiere bitte einen Admin</p>
                <?php
                } else {
                ?>
                    <p class='upload-response-message upload-true'>Neue Daten erfolgreich gespeichert</p>
            <?php
                }
            }
            ?>
            <form id="newusername" class="newuserinput" action="" method="POST">
                <label>Neuer Benutzername</label>
                <input id="getnewusername" name="newusername" placeholder="Benutzername" required>
                <input type="password" id="getpswforuser" name="pswnewusername" placeholder="Passwort" required>
                <button name="submit-usr" type="submit">Neuer Benutzername Speichern</button>
            </form>
            <form id="newemail" class="newuserinput" action="" method="POST">
                <label>Neue E-Mail</label>
                <input id="getnewemail" name="newemail" placeholder="E-Mail" required>
                <input type="password" id="getpswforemail" name="pswnewemail" placeholder="Passwort" required>
                <button name="submit-email" type="submit">Neue E-Mail Speichern</button>
            </form>
            <form id="newpassword" class="newuserinput" action="" method="POST">
                <label>Neues Passwort</label>
                <input type="password" id="getnewpassword" name="newpassword" placeholder="Neues Passwort" required>
                <input type="password" id="getnewpasswordrepeat" name="newpasswordrepeat" placeholder="Neues Passwort wiederholen" required>
                <input type="password" id="getoldpassword" name="oldpassword" placeholder="Altes Passwort" required>
                <button type="submit" name="submit-paswd">Neues Passwort Speichern</button>
            </form>
        </div>

        <div class="container footer-container" style="background-color:#404142">
            <span class="psw">* Bei Änderungen ist ein erneutes Login vonnöten</span>
        </div>
    </div>
</div>
<script>
    function loadPreviewPB() {
        imgpreview = document.getElementById("upload-pb-preview-cropper");
        imgpreview.src = URL.createObjectURL(document.getElementById("pb-upload-hidden").files[0]);
        document.getElementById("upload-pb-container").style.display = "block";

        var croppr = new Croppr('#upload-pb-preview-cropper', {
            startSize: [300, 300, 'px'],
            aspectRatio: 1,
        });

        document.getElementById("submit-changed-image").addEventListener("click", e => {
            uploadPB(croppr.getValue());
        });

        document.getElementById("slider-resize-image").addEventListener("input", e => {
            zoomImage(croppr);
        });

        document.getElementById("slider-resize-reset").addEventListener("click", e => {
            croppr.reset();
        });

        document.getElementById("uploadpb_quit").addEventListener("click", e => {
            document.getElementById('upload-pb-container').style.display = 'none';
        });
    }

    function zoomImage(croppr) {
        slider = document.getElementById("slider-resize-image");

        if (slider.value == slider.min) {
            document.getElementById("little-image-resize").style.opacity = "0.5";
        } else {
            document.getElementById("little-image-resize").style.opacity = "1";
        }

        if (slider.value == slider.max) {
            document.getElementById("big-image-resize").style.opacity = "0.5";
        } else {
            document.getElementById("big-image-resize").style.opacity = "1";
        }
        croppr.scaleBy(slider.value);
    }

    function uploadPB(data) {
        document.getElementById("pb-upload-data").value = JSON.stringify(data);
        document.getElementById("upload-new-pb-form").submit();
    }

    function resetInputs() {
        document.getElementById("getnewpassword").value = "";
        document.getElementById("getnewpasswordrepeat").value = "";
        document.getElementById("getoldpassword").value = "";
        document.getElementById("getnewemail").value = "";
    }

    function handleuploadPB_informationTag(over) {
        if (over) {
            document.getElementById('upload-pb-information').style.display = 'block';
        } else {
            document.getElementById('upload-pb-information').style.display = 'none';
        }
    }
</script>