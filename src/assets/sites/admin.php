<?php
include "navbar.php";
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
                foreach($users as $user){
                    echo "<tr><td>$user->id</td>";
                    echo "<td>$user->username</td>";
                    echo "<td>$user->email</td>";
                    echo "<td>$user->email_is_verified</td>";
                    if($user->admin == true){
                        $adminchangebutton = "change-privileges-button_down";
                        $newprivilege = 0;
                    } else{
                        $adminchangebutton = "change-privileges-button_up";
                        $newprivilege = 1;
                    }
                    echo "<td onmouseout='this.getElementsByClassName(\"change-privileges-btn\")[0].style.display = \"none\";' onmouseover='this.getElementsByClassName(\"change-privileges-btn\")[0].style.display = \"block\";'>$user->admin<div class='change-privileges-btn $adminchangebutton' onclick='updateUserPrivileges(\"$user->id\", \"$newprivilege\");'><i class='fas fa-long-arrow-alt-up'></i></div></td>";
                    echo "<td><img class='user-list-image' src='".$rootpath."assets/img/profilepictures/$user->profilepicture' alt='Profilbild'></td></tr>";
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
                    <input id="create-subject-name-input" placeholder="Neues Fach" required>
                </div>
                <select id="create-subject-school-input">
                    <option disabled selected>-- Schule wählen --</option>
                </select>
                <div class="admin-create-input-containers">
                    <label for="create-subject-addtionaltag-input">Additional Tag</label>
                    <input id="create-subject-addtionaltag-input" placeholder="LAP">
                </div>
                <button type="submit">Erstellen</button>
            </form>
        </fieldset>
        <fieldset id="create-semester-container">
            <form action="" method="POST">
                <legend>Neues Semester erstellen</legend>
                <div class="admin-create-input-containers">
                    <label for="create-semester-name-input">Semester Tag</label>
                    <input id="create-semester-name-input" placeholder="Neues Semester" required>
                </div>
                <button type="submit">Erstellen</button>
            </form>

        </fieldset>
    </div>
</div>
<script>
    function updateUserPrivileges(userid, newPrivilege) {
        //Update User Privilege with Ajax Request
    }
</script>