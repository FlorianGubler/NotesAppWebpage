<?php
function checkLogin($username, $password)
{
    global $conn;
    require_once("obj/user.class.php");
    $checkUserSQL = "SELECT * FROM root.users WHERE email=:text_username";
    $stid = oci_parse($conn, $checkUserSQL);
    oci_bind_by_name($stid, ":text_username", $username);
    oci_execute($stid);
    $user = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS);
    if (hash("sha256", $username . $password) == $user["PASSWORDHASH"]) {
        return $user["ID"];
    } else {
        return false;
    }
}

function getUserData($userid)
{
    global $conn;

    require_once("obj/user.class.php");
    $checkUserSQL = "SELECT * FROM root.users WHERE id=:id_user";
    $stid = oci_parse($conn, $checkUserSQL);

    oci_bind_by_name($stid, ":id_user", $userid);
    oci_execute($stid);
    $user = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS);
    if (oci_num_rows($stid) == 0) {
        return false;
    }
    return new User($user['ID'], $user['USERNAME'], $user["EMAIL"], $user["EMAIL_CONFIRMED"], $user['PROFILEPICTURE'], $user['ADMIN'], $user['PASSWORDHASH']);
}

function getNotes($userid)
{
    require_once("obj/note.class.php");
    global $conn;

    $query = "SELECT n.id, n.value, n.examName, n.FK_subject, n.FK_user, su.FK_school, n.FK_semester, su.additionalTag, so.schoolName, se.semesterTag, su.subjectName FROM root.notes n JOIN root.subjects su ON n.FK_subject = su.id JOIN root.schools so ON su.FK_school = so.id LEFT JOIN root.semesters se ON n.FK_semester = se.id WHERE FK_user=:id_user";
    $stid = oci_parse($conn, $query);
    oci_bind_by_name($stid, ":id_user", $userid);
    oci_execute($stid);
    $notes = array();
    while ($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
        array_push($notes, new Note($row['ID'], floatval($row['VALUE']), $row['EXAMNAME'], $row['FK_SUBJECT'], $row['FK_USER'], $row['FK_SCHOOL'], $row['FK_SEMESTER'], $row['ADDITIONALTAG'], $row['SCHOOLNAME'], $row['SEMESTERTAG'], $row['SUBJECTNAME']));
    }
    return $notes;
}
function getSemesters()
{
    require_once("obj/semester.class.php");
    global $conn;

    $query = "SELECT * FROM root.semesters";
    $stid = oci_parse($conn, $query);
    oci_execute($stid);
    $semesters = array();
    while ($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
        array_push($semesters, new Semester($row["ID"], $row["SEMESTERTAG"]));
    }
    return $semesters;
}
function getSchools()
{
    require_once("obj/school.class.php");
    global $conn;

    $query = "SELECT * FROM root.schools";
    $stid = oci_parse($conn, $query);

    oci_execute($stid);
    $schools = array();
    while ($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
        array_push($schools, new School($row["ID"], $row["SCHOOLNAME"]));
    }

    return $schools;
}
function getSubjects()
{
    require_once("obj/subject.class.php");
    global $conn;

    $query = "SELECT s1.id, s1.FK_school, s1.additionalTag, schools.schoolName, s1.subjectName as SUBJECTNAME, s2.subjectName as OVERSUBJECTNAME FROM root.subjects s1 JOIN root.schools ON s1.FK_school = schools.id LEFT JOIN root.subjects s2 ON s1.FK_overSubject = s2.id";
    $stid = oci_parse($conn, $query);
    oci_execute($stid);
    $subjects = array();
    while ($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
        array_push($subjects, new Subject($row["ID"], $row["FK_SCHOOL"], $row["ADDITIONALTAG"], $row["SCHOOLNAME"], $row["SUBJECTNAME"], $row["OVERSUBJECTNAME"]));
    }
    return $subjects;
}
function getAdditionalTags()
{
    global $conn;

    $query = "SELECT DISTINCT additionalTag FROM root.subjects";
    $stid = oci_parse($conn, $query);

    oci_execute($stid);
    $additionalTags = array();
    while ($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
        array_push($additionalTags, $row["ADDITIONALTAG"]);
    }
    return $additionalTags;
}
function getSubjectsFromID($searchid)
{
    $subjects = getSubjects();
    foreach ($subjects as $subject) {
        if ($subject->id == $searchid) {
            return $subject;
        }
    }
    return false;
}
function getSubjectsFromName($searchname)
{
    $subjects = getSubjects();
    foreach ($subjects as $subject) {
        if ($subject->subjectName == $searchname) {
            return $subject;
        }
    }
    return false;
}
function GetNextIDFromSessionLinks()
{
    global $conn;

    $query = "SELECT MAX(ID) + 1 as maxid FROM root.session_links";
    $stid = oci_parse($conn, $query);
    oci_execute($stid);

    $result = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)["MAXID"];
    if ($result == 0 or $result == null) {
        $result = 1;
    }
    return $result;
}
function GetShareLink($userid)
{
    global $conn;

    $token = random_int(100000, 999999);
    $link = hash("sha256", $userid . uniqid("", true));

    $query = "INSERT INTO root.session_links (ID, FK_user, link, token) VALUES (:new_id, :id_user, :newlink, :newtoken)";
    $stid = oci_parse($conn, $query);
    oci_bind_by_name($stid, ":id_user", $userid);
    oci_bind_by_name($stid, ":newlink", $link);
    $newid = GetNextIDFromSessionLinks();
    oci_bind_by_name($stid, ":new_id", $newid);
    oci_bind_by_name($stid, ":newtoken", $token);
    oci_execute($stid);

    $returnvalue = new stdClass();
    $returnvalue->link = $link;
    $returnvalue->token = $token;

    return $returnvalue;
}

function getStickyNotes($userid)
{
    require_once("obj/stickynotes.class.php");
    global $conn;


    $query = "SELECT stickynotes.PK_stickynote, TO_CHAR(stickynotes.createtime, 'dd.mm.yyyy - hh24:mi') AS createtime, stickynotes.title FROM root.stickynotes WHERE FK_user=:id_user";
    $stid = oci_parse($conn, $query);

    oci_bind_by_name($stid, ":id_user", $userid);
    oci_execute($stid);

    $stickynotes = array();
    while ($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
        array_push($stickynotes, new StickyNotes($row["PK_STICKYNOTE"], $row["CREATETIME"], $row["TITLE"]));
    }
    return $stickynotes;
}

function getStickyNotesVal($PK_stickyNote)
{
    global $conn;


    $query = "SELECT stickynotes.PK_stickynote, stickynotes.value FROM root.stickynotes WHERE PK_stickynote = :id_stickynote";
    $stid = oci_parse($conn, $query);

    oci_bind_by_name($stid, ":id_stickynote", $PK_stickyNote);
    oci_execute($stid);

    $result = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS);
    $stickynoteval = new stdClass();
    $stickynoteval->PK_stickynote = $result["PK_STICKYNOTE"];
    $stickynoteval->value = $result["VALUE"];
    return $stickynoteval;
}

function saveStickyNote($stickynoteid, $newvalue)
{
    global $conn;


    $query = "UPDATE root.stickynotes SET value=:newvalue WHERE PK_stickynote=:id_stickynote";
    $stid = oci_parse($conn, $query);
    oci_bind_by_name($stid, ":id_stickynote", $stickynoteid);
    oci_bind_by_name($stid, ":newvalue", $newvalue);


    return oci_execute($stid);
}
function GetNextIDFromStickyNote()
{
    global $conn;

    $query = "SELECT MAX(PK_STICKYNOTE) + 1 as maxid FROM root.stickynotes";
    $stid = oci_parse($conn, $query);
    oci_execute($stid);

    $result = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)["MAXID"];
    if ($result == 0 or $result == null) {
        $result = 1;
    }
    return $result;
}
function createStickyNote($title, $value = "", $userid)
{
    global $conn;

    //Escape Notes attributes
    $query = "INSERT INTO root.stickynotes (PK_STICKYNOTE, title, value, FK_user) VALUES (:new_id, :stickynotetitle, :stickynotevalue, :id_user)";
    $stid = oci_parse($conn, $query);
    oci_bind_by_name($stid, ":id_user", $userid);
    $newid = GetNextIDFromStickyNote();
    oci_bind_by_name($stid, ":new_id", $newid);
    oci_bind_by_name($stid, ":stickynotetitle", $title);
    oci_bind_by_name($stid, ":stickynotevalue", $title);

    if (!oci_execute($stid)) {
        return false;
    }
}

function ChangeStickyNoteTitle($stickynoteID, $newTitle)
{
    global $conn;

    $query = "UPDATE root.stickynotes SET title = :newtitle WHERE PK_stickynote = :id_stickynote";
    $stid = oci_parse($conn, $query);
    oci_bind_by_name($stid, ":newtitle", $newTitle);
    oci_bind_by_name($stid, ":id_stickynote", $stickynoteID);


    if (!oci_execute($stid)) {
        return false;
    }
}

function deleteStickyNote($PK_stickynote)
{
    global $conn;
    //Escape Notes attributes
    $query = "DELETE FROM root.stickynotes WHERE PK_stickynote = :id_stickynote";
    $stid = oci_parse($conn, $query);
    oci_bind_by_name($stid, ":id_stickynote", $PK_stickynote);

    if (!oci_execute($stid)) {
        return false;
    }
}
function GetNextIDFromNotes()
{
    global $conn;

    $query = "SELECT MAX(ID) + 1 as maxid FROM root.notes";
    $stid = oci_parse($conn, $query);
    oci_execute($stid);

    $result = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)["MAXID"];
    if ($result == 0 or $result == null) {
        $result = 1;
    }
    return $result;
}
function uploadNote($note)
{
    global $conn;
    //Escape Notes attributes
    $query = "INSERT INTO root.notes n (n.ID, n.value, n.examName, n.FK_subject, n.FK_user, n.FK_semester) VALUES (:nextid, :note_value, :note_examname, :id_subject, :id_user, :id_semester)";
    $stid = oci_parse($conn, $query);
    oci_bind_by_name($stid, ":note_value", $note->value);
    oci_bind_by_name($stid, ":note_examname", $note->examName);
    $nextid = GetNextIDFromNotes();
    oci_bind_by_name($stid, ":nextid", $nextid);
    oci_bind_by_name($stid, ":id_subject", $note->FK_subject);
    oci_bind_by_name($stid, ":id_user", $note->FK_user);
    oci_bind_by_name($stid, ":id_semester", $note->FK_semester);

    if (!oci_execute($stid)) {
        return false;
    }
}


function setUsername($user, $newusername, $password)
{
    global $conn;


    if ($user->passwordhash == hash("sha256", $user->email . $password)) {
        $query = "UPDATE root.users SET username=:newusername WHERE id=:id_user";
        $stid = oci_parse($conn, $query);
        oci_bind_by_name($stid, ":newusername", $newusername);
        oci_bind_by_name($stid, ":id_user", $user->id);


        oci_execute($stid);
    } else {
        return false;
    }
    return true;
}

function setEmail($user, $newemail, $password)
{
    global $conn;


    if ($user->passwordhash == hash("sha256", $user->email . $password)) {
        $updatedPsw = hash("sha256", $newemail . $password);

        $query = "UPDATE root.users SET email=:newemail, email_confirmed = 0, passwordhash=:newpsw WHERE id=:id_user";

        $stid = oci_parse($conn, $query);
        oci_bind_by_name($stid, ":newpsw", $updatedPsw);
        oci_bind_by_name($stid, ":newemail", $newemail);
        oci_bind_by_name($stid, ":id_user", $user->id);


        oci_execute($stid);

        if (!sendConfirmEmail($newemail)) {
            return false;
        }
    } else {
        return false;
    }
    return true;
}

function sendConfirmEmail($receiver)
{
    return true; //Email Server does not exist
    global $rootpath;
    $subject = 'Confirm Your Email';
    $message = file_get_contents("confirm_email_mailsite.html", true);
    $message = str_replace("%%useremail%%", $receiver, $message);
    $message = str_replace("%%urlpath%%", $rootpath . "assets/conf/", $message);

    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers .= 'From: verify@helsananotes.ch' . "\r\n";
    //$headers .= 'Reply-To: gubler.florian@gmx.net' . "\r\n";
    $headers .=  'X-Mailer: PHP/' . phpversion();

    if (!mail($receiver, $subject, $message, $headers)) {
        return false;
    }
}

function setPassword($user, $oldpassword, $newpassword)
{
    global $conn;


    if ($user->passwordhash == hash("sha256", $user->email . $oldpassword)) {
        $updatedPsw = hash("sha256", $user->email . $newpassword);
        $query = "UPDATE root.users SET passwordhash=:newpsw WHERE id=:id_user";
        $stid = oci_parse($conn, $query);
        oci_bind_by_name($stid, ":newpsw", $updatedPsw);
        oci_bind_by_name($stid, ":id_user", $user->id);


        oci_execute($stid);
    } else {
        return false;
    }
    return true;
}
function GetNextIDFromSubjects()
{
    global $conn;

    $query = "SELECT MAX(ID) + 1 as maxid FROM root.subjects";
    $stid = oci_parse($conn, $query);
    oci_execute($stid);

    $result = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)["MAXID"];
    if ($result == 0 or $result == null) {
        $result = 1;
    }
    return $result;
}
function AdminTools_CreateSubject($subjectName, $FK_school, $addtionalTag, $overSubject)
{
    global $conn;
    $nextid = GetNextIDFromSubjects();
    if ($addtionalTag == "") {
        if ($overSubject == "") {
            $query = "INSERT INTO root.subjects (ID, subjectName, FK_school) VALUES (:nextid, :subjectname, :id_school)";
            $stid = oci_parse($conn, $query);
        } else {
            $query = "INSERT INTO root.subjects (ID, subjectName, FK_school, FK_overSubject) VALUES (:nextid, :subjectname, :id_school, :id_overSubject)";
            $stid = oci_parse($conn, $query);
            oci_bind_by_name($stid, ":id_overSubject", $overSubject);
        }
    } else {
        if ($overSubject == "") {
            $query = "INSERT INTO root.subjects (ID, subjectName, FK_school, additionalTag) VALUES (:nextid, :subjectname, :id_school, :additionalTag)";
            $stid = oci_parse($conn, $query);
            oci_bind_by_name($stid, ":additionalTag", $additionalTag);
        } else {
            $query = "INSERT INTO root.subjects (ID, subjectName, FK_school, additionalTag, FK_overSubject) VALUES (:nextid, :subjectname, :id_school, :additionalTag, :id_overSubject)";
            $stid = oci_parse($conn, $query);
            oci_bind_by_name($stid, ":id_overSubject", $overSubject);
            oci_bind_by_name($stid, ":additionalTag", $additionalTag);
        }
    }
    oci_bind_by_name($stid, ":subjectname", $subjectName);
    oci_bind_by_name($stid, ":id_school", $FK_school);
    oci_bind_by_name($stid, ":nextid", $nextid);

    oci_execute($stid);
}
function GetNextIDFromSemesters()
{
    global $conn;

    $query = "SELECT MAX(ID) + 1 as maxid FROM root.semesters";
    $stid = oci_parse($conn, $query);
    oci_execute($stid);

    $result = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)["MAXID"];
    if ($result == 0 or $result == null) {
        $result = 1;
    }
    return $result;
}
function AdminTools_CreateSemester($semesterTag)
{
    global $conn;

    $query = "INSERT INTO root.semesters (ID, semesterTag) VALUES (:nextid, :semester)";
    $stid = oci_parse($conn, $query);
    $nextid = GetNextIDFromSemesters();
    oci_bind_by_name($stid, ":nextid", $nextid);
    oci_bind_by_name($stid, ":semester", $semesterTag);
    oci_execute($stid);
}

function AdminTools_ChangeuserPrivileges($userID, $newPrivilege)
{
    global $conn;
    $query = "UPDATE root.users SET admin=:newprivilege WHERE id=:id_user";
    $stid = oci_parse($conn, $query);
    oci_bind_by_name($stid, ":newprivilege", $newPrivilege);
    oci_bind_by_name($stid, ":id_user", $userID);


    oci_execute($stid);
}

function AdminTools_GetUserList()
{
    global $conn;


    require_once("obj/user.class.php");
    $query = "SELECT * FROM root.users";
    $stid = oci_parse($conn, $query);

    oci_execute($stid);
    $users = array();
    while ($user = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
        array_push($users, new User($user['ID'], $user['USERNAME'], $user["EMAIL"], $user["EMAIL_CONFIRMED"], $user['PROFILEPICTURE'], $user['ADMIN'], $user['PASSWORDHASH']));
    }
    return $users;
}
function uploadPB($userid, $uploadpbfile, $uploadpbdata)
{
    global $conn;


    //generate Filename
    $target_dir = "../img/profilepictures/";
    $filecount = count(scandir($target_dir)) - 1;
    $newfilename = "profilepicture_" . ($filecount) . "." . explode(".", $uploadpbfile["name"])[count(explode(".", $uploadpbfile["name"])) - 1];
    $target_file = $target_dir . $newfilename;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $counter = 0;
    while (file_exists($target_file)) {
        $counter++;
        $newfilename = "profilepicture_" . ($filecount + $counter) . "." . explode(".", $uploadpbfile["name"])[count(explode(".", $uploadpbfile["name"])) - 1];
        $target_file = $target_dir . $newfilename;
    }

    // Check if image file is a actual image or fake image
    $check = getimagesize($uploadpbfile["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $uploadOk = 0;
    }

    // Allow certain file formats
    if (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif"
    ) {
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        return false;
    } else {
        if (move_uploaded_file($uploadpbfile["tmp_name"], $target_file)) {
            do {
                if (file_exists($target_file)) {
                    break;
                }
            } while (true);

            //Delete old File
            $query = "SELECT profilepicture FROM root.users WHERE id=:id_user";
            $stid = oci_parse($conn, $query);
            oci_bind_by_name($stid, ":id_user", $userid);
            oci_execute($stid);
            $oldimg = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)["PROFILEPICTURE"];

            if ($oldimg != "defaultpb.jpg" && file_exists($target_dir . $oldimg)) {
                unlink($target_dir . $oldimg);
            }

            //Set New File
            $query = "UPDATE root.users SET profilepicture=:newfilename WHERE id=:id_user";
            $stid = oci_parse($conn, $query);
            oci_bind_by_name($stid, ":id_user", $userid);
            oci_bind_by_name($stid, ":newfilename", $newfilename);

            oci_execute($stid);

            //Crop Image
            $image_data = json_decode($uploadpbdata);
            $source = imagecreatefromjpeg($target_file);
            $im2 = imagecrop($source, ['x' => $image_data->x, 'y' => $image_data->y, 'width' => $image_data->width, 'height' => $image_data->height]);

            imagejpeg($im2, $target_file);
        } else {
            return false;
        }
    }
}
