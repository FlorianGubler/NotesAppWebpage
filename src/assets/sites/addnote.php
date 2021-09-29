<?php
include "navbar.php";

$semesters = getSemesters();
$subjects = getSubjects();
$subjectsstruct = array();
foreach ($subjects as $subject) {
  if (!isset($subjectsstruct[$subject->schoolName])) {
    $subjectsstruct[$subject->schoolName] = array();
  }
  array_push($subjectsstruct[$subject->schoolName], $subject);
}

if (isset($_POST["upload-note"])) {
  require_once("../conf/obj/note.class.php");
  uploadNote(new Note(null, $_POST["note"], $_POST["examTag"], $_POST["subject"], $user->id, null, $_POST["semester"], null, null, null, null));
  header("Location: " . $_SERVER["PHP_SELF"]);
}
?>
<link rel="stylesheet" href="<?php echo $rootpath; ?>assets/css/addnote.css">
<div id="add-note" class="modal">
  <form class="modal-content animate" action="" method="POST">
    <div class="imgcontainer">
      <h1>Note hinzufügen</h1>
    </div>
    <div class="container">
      <select onchange="checkInput();" name="subject" id="select-subject" required>
        <option disabled selected value> - Fach oder Modul - </option>
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
      <select name="semester" id="select-semester" required>
        <option disabled selected> - Semester - </option>
        <?php
        foreach ($semesters as $semester) {
          echo "<option value='$semester->id'>$semester->semesterTag</option>";
        }
        ?>
      </select>
      <input name="examTag" type="text" placeholder="Prüfungsname" required>
      <input name="note" type="number" step="0.001" placeholder="Note" required>
      <button type="submit" name="upload-note">Note hochladen</button>
      <p style='background-color: red; display: none;' id='login-response-false'>Etwas hat nicht geklappt :(</p>
      <p style='background-color: #4ada26; color: #404142;display: none;' id='login-response-true'>Note erfolgreich hochgeladen :)</p>
    </div>

    <div class="container footer-container">
      <span class="psw"><a href="mailto:gubler.florian@gmx.net">Probleme beim hochladen?</a></span>
    </div>
  </form>
</div>
<script>
  function checkInput() {
    if (document.querySelector('#select-subject option:checked').parentElement.label == "LAP") {
      document.getElementById("select-semester").style.display = "none";
    } else {
      document.getElementById("select-semester").style.display = "block";
    }
  }
</script>