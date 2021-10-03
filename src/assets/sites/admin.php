<?php
require_once("../../config.php");

$login = false;

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

if($user->admin != 1){
    header("Location: " . $rootpath . "assets/sites/home.php");
}

if (isset($_POST["create-subject"])) {
    if (!isset($_POST["over-subject"])) {
        $_POST["over-subject"] = null;
    }
    AdminTools_CreateSubject($_POST["subjectname"], $_POST["school"], $_POST["additionaltag"], $_POST["over-subject"]);
    header("Location: " . $_SERVER["PHP_SELF"]);
}

if (isset($_POST["create-semester"])) {
    AdminTools_CreateSemester($_POST["semestertag"]);
    header("Location: " . $_SERVER["PHP_SELF"]);
}

if (isset($_POST["update-user-privileges"])) {
    AdminTools_ChangeuserPrivileges($_POST["userid"], $_POST["newprivilege"]);
    header("Location: " . $_SERVER["PHP_SELF"]);
}

include "navbar.php";


$additionalTags = getAdditionalTags();
$subjects = getSubjects();
$subjectsstruct = array();
foreach ($subjects as $subject) {
    if (!isset($subjectsstruct[$subject->schoolName])) {
        $subjectsstruct[$subject->schoolName] = array();
    }
    array_push($subjectsstruct[$subject->schoolName], $subject);
}
?>
<link rel="stylesheet" href="<?php echo $rootpath; ?>assets/css/admin.css">
<div class="content-title-bar-container">
    <h1>Administrator Tools</h1>
</div>
<div class="content-container">
    <div class="user-list-container">
        <table id="user-list-table">
            <tr>
                <th>UserID</th>
                <th>Benutzername</th>
                <th>Email</th>
                <th>Email Ok</th>
                <th>Admin Rechte</th>
                <th>Profilbild</th>
            </tr>
            <?php
            $users = AdminTools_GetUserList();
            foreach ($users as $user) {
                echo "<tr><td>$user->id</td>";
                echo "<td>$user->username</td>";
                echo "<td>$user->email</td>";
                echo "<td>$user->email_is_verified</td>";
                if ($user->admin == true) {
                    $adminchangebutton = "change-privileges-button_down";
                    $newprivilege = 0;
                } else {
                    $adminchangebutton = "change-privileges-button_up";
                    $newprivilege = 1;
                }
                echo "<td onmouseout='this.getElementsByClassName(\"change-privileges-btn\")[0].style.display = \"none\";' onmouseover='this.getElementsByClassName(\"change-privileges-btn\")[0].style.display = \"block\";'>$user->admin<form action='' method='POST' onsubmit=' return updateUserPrivileges(this, \"$user->id\", \"$newprivilege\");'><button type='submit' class='change-privileges-btn $adminchangebutton'><i class='fas fa-long-arrow-alt-up'></i></button></td>";
                echo "<td><img class='user-list-image' src='" . $rootpath . "assets/img/profilepictures/$user->profilepicture' alt='Profilbild'></td></tr>";
            }
            ?>
        </table>
    </div>
    <div class="input-content-container">
        <fieldset id="create-subject-container">
            <legend>Neues Fach erstellen</legend>
            <form action="" method="POST">
                <div class="admin-create-input-containers">
                    <label for="create-subject-name-input">Fach Name</label>
                    <input id="create-subject-name-input" name="subjectname" placeholder="Neues Fach" required>
                </div>
                <select id="create-subject-school-input" name="school">
                    <option disabled selected>-- Schule wählen --</option>
                    <?php
                    foreach (getSchools() as $school) {
                        echo "<option value='$school->id'>$school->schoolName</option>";
                    }
                    ?>
                </select>
                <div class="input-container" onclick="checkCheckbox(event, this.getElementsByTagName('input')[0]);">
                    <input id="overSubjectCheckbox" type="checkbox" name="has-over-subject" onclick="checkOverNote(this);">
                    <label>Note wird in andere Note einberechnet</label>
                </div>
                <select name="over-subject" id="selectOverSubject" required>
                    <option disabled selected> - Fach wählen - </option>
                    <?php
                    foreach ($subjectsstruct as $school => $schoolsubjects) {
                        echo "<optgroup label='$school'>";
                        foreach ($schoolsubjects as $subject) {
                            if ($subject->schoolName == $school) {
                                echo "<option value='$subject->id'>$subject->subjectName</option>";
                            }
                        }
                    }
                    ?>
                </select>
                <div class="admin-create-input-containers">
                    <label for="create-subject-addtionaltag-input">Additional Tag</label>
                    <input id="create-subject-addtionaltag-input" placeholder="ÜK Module" name="additionaltag" list="additionaltags">
                    <datalist id="additionaltags">
                        <?php
                        foreach ($additionalTags as $additionalTag) {
                            echo "<option value='$additionalTag'>";
                        }
                        ?>
                    </datalist>
                </div>
                <button type="submit" name="create-subject">Erstellen</button>
            </form>
        </fieldset>
        <fieldset id="create-semester-container">
            <legend>Neues Semester erstellen</legend>
            <form action="" method="POST">
                <div class="admin-create-input-containers">
                    <label for="create-semester-name-input">Semester Tag</label>
                    <input id="create-semester-name-input" name="semestertag" placeholder="Neues Semester" required>
                </div>
                <button type="submit" name="create-semester">Erstellen</button>
            </form>
        </fieldset>
    </div>
</div>
<script>
    function updateUserPrivileges(form_el, userid, newPrivilege) {
        useridinput = form_el.appenChild(document.createElement("input"));
        useridinput.name = "userid";
        useridinput.type = "hidden";
        useridinput.value = userid;

        newprivilegeinput = form_el.appenChild(document.createElement("input"));
        newprivilegeinput.name = "newprivilege";
        newprivilegeinput.type = "hidden";
        newprivilegeinput.value = newPrivilege;
        return true;
    }

    function checkCheckbox(e, checkbox) {
        if (e.target.id != "overSubjectCheckbox") {
            if (checkbox.checked) {
                checkbox.checked = false;
            } else {
                checkbox.checked = true;
            }
            checkOverNote(checkbox);
        }
    }

    function checkOverNote(checkbox) {
        if (checkbox.checked) {
            document.getElementById("selectOverSubject").style.display = "block";
        } else {
            document.getElementById("selectOverSubject").style.display = "none";
        }
    }
</script>