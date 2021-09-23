<?php
include "navbar.php";

if (isset($_POST["create-subject"])) {
    AdminTools_CreateSubject($_POST["subjectname"], $_POST["school"], $_POST["additionaltag"]);
}

if (isset($_POST["create-semester"])) {
    AdminTools_CreateSemester($_POST["semestertag"]);
}

if (isset($_POST["update-user-privileges"])) {
    AdminTools_ChangeuserPrivileges($_POST["userid"], $_POST["newprivilege"]);
}
?>
<link rel="stylesheet" href="<?php echo $rootpath; ?>assets/css/notes.css">
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
                <div class="admin-create-input-containers">
                    <label for="create-subject-addtionaltag-input">Additional Tag</label>
                    <input id="create-subject-addtionaltag-input" placeholder="LAP" name="additionaltag">
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
</script>