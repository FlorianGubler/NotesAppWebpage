<?php
include "config.php";

$checklogin = true;
$invalid_sessionLink = false;

if (isset($_POST['submit_token']) && isset($_POST['token']) && isset($_GET['link'])) {
    $sessionLink = checkSessionLink($_GET["link"], $_POST["token"]);
    if ($sessionLink != false) {
        $semesters = getSemesters();
        $schools = getSchools();
        $subjects = getSubjects();
        $notes = getNotes($sessionLink["FK_user"]);
    } else {
        $checklogin = false;
        $invalid_sessionLink = true;
    }
} else {
    $checklogin = false;
}

function checkSessionLink($link, $token)
{
    global $conn;

    $query = "SELECT * FROM session_links WHERE link='$link' AND token=$token;";
    $result = $conn->query($query);
    if ($result->num_rows == 0) {
        return false;
    } else {
        return $result->fetch_assoc();
    }
}

function getSubjects()
{
    require_once("assets/conf/subject.class.php");
    global $conn;
    $query = "SELECT subjects.id, subjects.FK_school, subjects.additionalTag, schools.schoolName, subjects.subjectName FROM subjects INNER JOIN schools ON subjects.FK_school = schools.id;";
    $result = $conn->query($query);
    $subjects = array();
    while ($row = $result->fetch_assoc()) {
        array_push($subjects, new Subject($row["id"], $row["FK_school"], $row["additionalTag"], $row["schoolName"], $row["subjectName"]));
    }

    return json_encode($subjects);
}

function getNotes($userid)
{
    require_once("assets/conf/note.class.php");
    global $conn;

    $userid = $conn->real_escape_string($userid);
    $query = "SELECT notes.id, notes.value, notes.examName, notes.FK_subject, notes.FK_user, subjects.FK_school, notes.FK_semester, subjects.additionalTag, schools.schoolName, semesters.semesterTag, subjects.subjectName FROM notes INNER JOIN subjects ON notes.FK_subject = subjects.id INNER JOIN schools ON subjects.FK_school = schools.id INNER JOIN semesters ON notes.FK_semester = semesters.id WHERE FK_user=" . $userid . ";";
    $result = $conn->query($query);
    $notes = array();
    while ($row = $result->fetch_assoc()) {
        array_push($notes, new Note($row['id'], $row['value'], $row['examName'], $row['FK_subject'], $row['FK_user'], $row['FK_school'], $row['FK_semester'], $row['additionalTag'], $row['schoolName'], $row['semesterTag'], $row['subjectName']));
    }

    return json_encode($notes);
}
function getSemesters()
{
    require_once("assets/conf/semester.class.php");
    global $conn;

    $query = "SELECT * FROM semesters;";
    $result = $conn->query($query);
    $semesters = array();
    while ($row = $result->fetch_assoc()) {
        array_push($semesters, new Semester($row["id"], $row["semesterTag"]));
    }

    return json_encode($semesters);
}
function getSchools()
{
    require_once("assets/conf/school.class.php");
    global $conn;

    $query = "SELECT * FROM schools;";
    $result = $conn->query($query);
    $schools = array();
    while ($row = $result->fetch_assoc()) {
        array_push($schools, new School($row["id"], $row["schoolName"]));
    }

    return json_encode($schools);
}
?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sharing</title>
    <link rel="stylesheet" href="<?php echo "$rootpath"; ?>assets/css/bms.css">

    <style>
        body {
            background-color: #444444;
        }

        #code-container {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }

        #code-container label {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: white;
        }

        #invalid-input{
            color: red;
            margin: 10px;
        }

        #code-container input {
            width: 100%;
            font-family: Verdana;
            padding: 15px;
            margin: 5px 0 22px 0;
            display: inline-block;
            border: none;
            background: #adadad;
            border-radius: 5px;
        }

        #code-container button {
            background-color: #2e2e2e;
            color: white;
            padding: 16px 20px;
            margin: 8px 0;
            border: none;
            cursor: pointer;
            width: 106%;
        }
    </style>
</head>

<body>
    <?php if (!$checklogin) { ?>
        <div id="code-container">
            <form action="" method="post">
                <label for="input-code">Enter Your Code</label>
                <input autocomplete="off" placholder="Code" type="text" name="token">
                <button type="submit" name="submit_token">Enter</button>
                <?php if ($invalid_sessionLink) { ?>
                    <p id="invalid-input">* Ungültige Eingabe </p>
                <?php } ?>
            </form>
        </div>
    <?php } else { ?>
        <ul id="semesters"></ul>
        <div id="tables">
            <table id="table-notes-norm"></table>
        </div>
        <div id="calculates">
            <h2>Notenberechnung</h2>
            <table id="table-notes-end"></table>
        </div>

        <script src="assets/js/calc_bms.js"></script>

        <script>
            var semesters = <?php echo "'" . $semesters . "'"; ?>;
            var subjects = <?php echo "'" . $subjects . "'"; ?>;
            var notes = <?php echo "'" . $notes . "'"; ?>;

            SetUpSemesters(semesters, subjects, notes)
        </script>
    <?php } ?>
</body>

</html>