<?php
function checkLogin($username, $password)
{
    global $conn;
    require_once("obj/user.class.php");
    $checkUserSQL = "SELECT * FROM users WHERE email='" . $username . "';";
    $result = $conn->query($checkUserSQL);
    $user = $result->fetch_assoc();
    if (hash("sha256", $username . $password) == $user["passwordhash"]) {
        return $user["id"];
    } else {
        return false;
    }
}

function getUserData($userid)
{
    global $conn;
    require_once("obj/user.class.php");
    $checkUserSQL = "SELECT * FROM users WHERE id='" . $userid . "';";
    $result = $conn->query($checkUserSQL);
    $user = $result->fetch_assoc();
    if(mysqli_num_rows($result) == 0){
        return false;
    }
    return new User($user['id'], $user['username'], $user["email"], $user["email_confirmed"], $user['profilepicture'], $user['admin']);
}

function getNotes($userid)
{
    require_once("obj/note.class.php");
    global $conn;

    $userid = $conn->real_escape_string($userid);
    $query = "SELECT notes.id, notes.value, notes.examName, notes.FK_subject, notes.FK_user, subjects.FK_school, notes.FK_semester, subjects.additionalTag, schools.schoolName, semesters.semesterTag, subjects.subjectName FROM notes INNER JOIN subjects ON notes.FK_subject = subjects.id INNER JOIN schools ON subjects.FK_school = schools.id INNER JOIN semesters ON notes.FK_semester = semesters.id WHERE FK_user=" . $userid . ";";
    $result = $conn->query($query);
    $notes = array();
    while ($row = $result->fetch_assoc()) {
        array_push($notes, new Note($row['id'], $row['value'], $row['examName'], $row['FK_subject'], $row['FK_user'], $row['FK_school'], $row['FK_semester'], $row['additionalTag'], $row['schoolName'], $row['semesterTag'], $row['subjectName']));
    }

    return $notes;
}
function getSemesters()
{
    require_once("obj/semester.class.php");
    global $conn;

    $query = "SELECT * FROM semesters;";
    $result = $conn->query($query);
    $semesters = array();
    while ($row = $result->fetch_assoc()) {
        array_push($semesters, new Semester($row["id"], $row["semesterTag"]));
    }

    return $semesters;
}
function getSchools()
{
    require_once("obj/school.class.php");
    global $conn;

    $query = "SELECT * FROM schools;";
    $result = $conn->query($query);
    $schools = array();
    while ($row = $result->fetch_assoc()) {
        array_push($schools, new School($row["id"], $row["schoolName"]));
    }

    return $schools;
}
function getSubjects()
{
    require_once("obj/subject.class.php");
    global $conn;
    $query = "SELECT subjects.id, subjects.FK_school, subjects.additionalTag, schools.schoolName, subjects.subjectName FROM subjects INNER JOIN schools ON subjects.FK_school = schools.id;";
    $result = $conn->query($query);
    $subjects = array();
    while ($row = $result->fetch_assoc()) {
        array_push($subjects, new Subject($row["id"], $row["FK_school"], $row["additionalTag"], $row["schoolName"], $row["subjectName"]));
    }

    return $subjects;
}

function DeleteStickyNotes($userid)
{
    global $conn;

    $current_date = date("Y-m-d");

    $sql = "DELETE FROM session_links WHERE FK_user=$userid AND create_date < DATE '$current_date'";
    $conn->query($sql);
}

function GetShareLink($userid)
{
    global $conn;
    DeleteStickyNotes($userid);

    $token = random_int(100000, 999999);
    $link = hash("sha256", $userid . uniqid("", true));

    $sql = "INSERT INTO session_links (FK_user, link, token) VALUES ($userid, '$link', $token); ";
    $conn->query($sql);

    $returnvalue = new stdClass();
    $returnvalue->link = $link;
    $returnvalue->token = $token;

    return $returnvalue;
}

function getStickyNotes($userid)
{
    require_once("obj/stickynotes.class.php");
    global $conn;
    $sql = "SELECT stickynotes.PK_stickynote, DATE_FORMAT(stickynotes.createtime, '%d.%m.%Y - %H:%i') AS createtime, stickynotes.title FROM stickynotes WHERE FK_user=" . $userid . ";";
    $qry = $conn->query($sql);
    $stickynotes = array();
    while ($row = $qry->fetch_assoc()) {
        array_push($stickynotes, new StickyNotes($row["PK_stickynote"], $row["createtime"], $row["title"]));
    }
    return json_encode($stickynotes);
}

function getStickyNotesVal($PK_stickyNote)
{
    global $conn;
    global $current_user;
    $sql = "SELECT stickynotes.PK_stickynote, stickynotes.value FROM stickynotes WHERE PK_stickynote = $PK_stickyNote AND FK_user=" . $current_user["id"] . ";";
    $qry = $conn->query($sql);
    $result = $row = $qry->fetch_assoc();
    $stickynoteval = new stdClass();
    $stickynoteval->PK_stickynote = $result["PK_stickynote"];
    $stickynoteval->value = $result["value"];
    return json_encode($stickynoteval);
}

function saveStickyNote($id, $newvalue)
{
    global $conn;

    $newvalue = $conn->real_escape_string($newvalue);
    $query = "UPDATE stickynotes SET value='" . $newvalue . "' WHERE PK_stickynote=" . $id . ";";
    $conn->query($query);
}

function createStickyNote($title, $value = "", $userid)
{
    global $conn;

    $title = $conn->real_escape_string($title);
    $value = $conn->real_escape_string($value);

    //Escape Notes attributes
    $query = "INSERT INTO stickynotes (title, value, FK_user) VALUES ('" . $title . "', '" . $value . "', $userid);";

    if (!$conn->query($query)) {
        http_response_code(406);
    }
}

function ChangeStickyNoteTitle($stickynoteID, $newTitle, $userid)
{
    global $conn;

    $newTitle = $conn->real_escape_string($newTitle);
    $stickynoteID = $conn->real_escape_string($stickynoteID);

    $query = "UPDATE stickynotes SET title = '$newTitle' WHERE PK_stickynote = $stickynoteID AND FK_user = $userid;";

    if (!$conn->query($query)) {
        http_response_code(406);
    }
}

function deleteStickyNote($PK_stickynote)
{
    global $conn;

    //Escape Notes attributes
    $query = "DELETE FROM stickynotes WHERE PK_stickynote = '" . $PK_stickynote . "';";

    if (!$conn->query($query)) {
        http_response_code(406);
    }
}

function uploadNote($userid, $note)
{
    global $conn;

    $userid = $conn->real_escape_string($userid);

    //Escape Notes attributes
    $query = "INSERT INTO notes (value, examName, FK_subject, FK_user, FK_semester) VALUES (" . $note->value . ", '" . $note->examName . "', " . $note->FK_subject . ", " . $userid . ", " . $note->FK_semester . ");";

    if (!$conn->query($query)) {
        http_response_code(406);
    }
}


function setUsername($user, $newusername, $password)
{
    global $conn;

    if ($user["passwordhash"] == hash("sha256", $user["email"] . $password)) {
        $newusername = $conn->real_escape_string($newusername);
        $query = "UPDATE users SET username='" . $newusername . "' WHERE id=" . $user["id"] . ";";
        $conn->query($query);
    } else {
        http_response_code(403);
    }
}

function setEmail($user, $newemail, $password)
{
    global $conn;

    if ($user["passwordhash"] == hash("sha256", $user["email"] . $password)) {
        $updatedPsw = hash("sha256", $newemail . $password);

        $newemail = $conn->real_escape_string($newemail);
        $query = "UPDATE users SET email='" . $newemail . "', email_confirmed = 0, passwordhash='" . $updatedPsw . "' WHERE id=" . $user["id"] . ";";

        $conn->query($query);

        sendConfirmEmail($newemail);
    } else {
        http_response_code(403);
    }
}

function sendConfirmEmail($receiver)
{
    $subject = 'Confirm Your Email';
    $message = str_replace("XXXXXXmailXXXXXX", $receiver, file_get_contents("confirm_email_mailsite.html"));
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers .= 'From: verify@promarks.ch' . "\r\n";
    //$headers .= 'Reply-To: gubler.florian@gmx.net' . "\r\n";
    $headers .=  'X-Mailer: PHP/' . phpversion();

    if (!mail($receiver, $subject, $message, $headers)) {
        http_response_code(409); // 409 - Conflict
    }
}

function setPassword($user, $oldpassword, $newpassword)
{
    global $conn;
    if ($user["passwordhash"] == hash("sha256", $user["email"] . $oldpassword)) {
        $updatedPsw = hash("sha256", $user["email"] . $newpassword);
        $query = "UPDATE users SET passwordhash='" . $updatedPsw . "' WHERE id=" . $user["id"] . ";";
        $conn->query($query);
    } else {
        http_response_code(403);
    }
}

function AdminTools_CreateSubject($subjectName, $FK_school, $addtionalTag)
{
    global $conn;
    if ($addtionalTag == "") {
        $sql = "INSERT INTO subjects (subjectName, FK_school) VALUES ('$subjectName', $FK_school);";
    } else {
        $sql = "INSERT INTO subjects (subjectName, FK_school, additionalTag) VALUES ('$subjectName', $FK_school, '$addtionalTag');";
    }
    $conn->query($sql);
}

function AdminTools_CreateSemester($semesterTag)
{
    global $conn;
    $sql = "INSERT INTO semesters (semesterTag) VALUES ('$semesterTag');";
    $conn->query($sql);
}

function AdminTools_ChangeuserPrivileges($userID, $newPrivilege)
{
    global $conn;
    $sql = "UPDATE users SET admin=$newPrivilege WHERE id=$userID;";
    $conn->query($sql);
}

function AdminTools_GetUserList()
{
    global $conn;
    require_once("obj/user.class.php");
    $sql = "SELECT * FROM users";
    $qry = $conn->query($sql);
    $users = array();

    while ($user = $qry->fetch_assoc()) {
        array_push($users, new User($user['id'], $user['username'], $user["email"], $user["email_confirmed"], $user['profilepicture'], $user['admin']));
    }
    return $users;
}
