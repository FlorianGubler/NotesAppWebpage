<?php
include "navbar.php";
?>
<script src="<?php echo $rootpath; ?>assets/js/cropprjs/croppr.js"></script>
<script src="<?php echo $rootpath; ?>assets/js/sleep.js"></script>
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
            <img id="profile-pic" src="<?php echo $rootpath . "assets/img/profilepictures/" . $user->profilepicture; ?>" alt="Avatar" class="avatar" draggable="false" onerror="this.src = 'https://dekinotu.myhostpoint.ch/notes/assets/profilepictures/defaultpb.jpg';">
            <button class="upload-pb-select-picture" type="button" onclick="document.getElementById('pb-upload-hidden').click();" onchange="loadPreviewPB();"><i class="fas fa-pencil-alt"></i> Bearbeiten</button>
            <input type="file" id="pb-upload-hidden">
            <p style="display: inline;" class="use-user-name"><?php echo $user->username; ?></p>
            <p style="display: inline; opacity: 0.5; font-size: x-small; padding-top: 0px;" class="use-user-email"><?php echo $user->email; ?></p>
        </div>
        <div class="container">
            <form id="newusername" class="newuserinput">
                <label>Neuer Benutzername</label>
                <input id="getnewusername" name="newusername" placeholder="Benutzername" required>
                <input type="password" id="getpswforuser" name="pswnewusername" placeholder="Passwort" required>
                <button name="submit-usr" type="submit">Neuer Benutzername Speichern</button>
            </form>
            <form id="newemail" class="newuserinput">
                <label>Neue E-Mail</label>
                <input id="getnewemail" name="newemail" placeholder="E-Mail" required>
                <input type="password" id="getpswforemail" name="pswnewemail" placeholder="Passwort" required>
                <button name="submit-email" type="submit">Neue E-Mail Speichern</button>
            </form>
            <form id="newpassword" class="newuserinput">
                <label>Neues Passwort</label>
                <input type="password" id="getnewpassword" name="newpassword" placeholder="Neues Passwort" required>
                <input type="password" id="getnewpasswordrepeat" name="newpasswordrepeat" placeholder="Neues Passwort wiederholen" required>
                <input type="password" id="getoldpassword" name="oldpassword" placeholder="Altes Passwort" required>
                <button type="submit" name="submit-paswd">Neues Passwort Speichern</button>
            </form>

            <p style='background-color: red;display: none;' id='login-response-false'>Etwas hat nicht geklappt</p>
            <p style='background-color: green;display: none;' id='login-response-true'>Neue Daten erfolgreich
                gespeichert</p>
        </div>

        <div class="container" style="background-color:#404142">
            <span class="psw">* Bei Änderungen ist ein erneutes Login vonnöten</span>
        </div>
    </div>
</div>
<script>
    function loadPreviewPB() {
        document.getElementById("upload-pb-container").style.display = "block";

        var croppr = new Croppr('#upload-pb-preview-cropper', {
            aspectRatio: 1
        });

        document.getElementById("submit-changed-image").addEventListener("click", e => {
            uploadPB(croppr.getValue(), attribute);
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

    function uploadPB(data, filelink) {
        //Upload PB
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