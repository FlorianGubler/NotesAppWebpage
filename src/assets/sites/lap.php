<?php
include "navbar.php";
$notes = getNotes($user->id);

$notesbytag["bs"] = array();
$notesbytag["uek"] = array();
$endnotes["bs"] = array();
$endnotes["uek"] = array();
$endnotes["bs"]['endnote'] = 0;
$endnotes["uek"]['endnote'] = 0;
foreach ($notes as $note) {
    if ($note->schoolName == "LAP") {
        if ($note->additionalTag == "Berufsfachschule Module") {
            $tag = "bs";
        } else if ($note->additionalTag != null && $note->additionalTag != "") {
            $tag = "uek";
        }
        if (!isset($notesbytag[$tag][$note->subjectName])) {
            $notesbytag[$tag][$note->subjectName] = array();
        }
        if (!isset($endnotes[$tag][$note->subjectName])) {
            $endnotes[$tag][$note->subjectName] = 0;
        }
        array_push($notesbytag[$tag][$note->subjectName], $note);
        $endnotes[$tag][$note->subjectName] += $note->value;
    }
}

?>
<link rel="stylesheet" href="<?php echo $rootpath; ?>assets/css/lap.css">
<div id="top-bar-gui">
    <h1 id="title-home">IPA Noten</h1>
    <a href="<?php echo $rootpath; ?>assets/sites/addnote.php" id="edit-table"><i class="far fa-edit"></i></a>
</div>
<ul id="semesters">
    <li id="bs-semester" class="semester-choose"><a href="#BerufsfachschuleModule">Berufsfachschule</a></li>
    <li id="uek-lap-semester" class="semester-choose"><a href="#container-notes-form">ÜK Module & IPA Abschlussprüfung</a></li>
</ul>
<div class="tables">
    <div id="tables">
        <div class="tables-container">
            <table id="BerufsfachschuleModule" class="table-notes-norm">
                <?php
                $currtag = "bs";
                foreach ($notesbytag[$currtag] as $subject => $subjectnotes) {
                    echo "<tr>";
                    echo "<th>$subject</th>";
                    foreach ($subjectnotes as $note) {
                        $notestateclass = "";
                        if ($note->value > 4) {
                            $notestateclass = "grade-good";
                        } else if ($note->value < 4 && $note->value != 0) {
                            $notestateclass = "grade-bad";
                        }
                        echo "<td class='$notestateclass' title='$note->examName'>$note->value</td>";
                        $endnotes[$currtag][$subject] += $note->value;
                    }
                    if (count($subjectnotes) != 0) {
                        $endnotes[$currtag][$subject] /= count($subjectnotes);
                        $endnotes[$currtag][$subject] = round($endnotes[$currtag][$subject] * 2) / 2;
                    }
                    $endnotes[$currtag]['endnote'] += $endnotes[$currtag][$subject];
                    echo "</tr>";
                }
                if (count($notesbytag[$currtag]) != 0) {
                    $endnotes[$currtag]["endnote"] /= count($notesbytag[$currtag]);
                    $endnotes[$currtag]["endnote"] = round($endnotes[$currtag]["endnote"] * 2) / 2;
                }
                ?>
            </table>
            <table id="container-notes-form" class="table-notes-norm">
                <?php
                $currtag = "uek";
                foreach ($notesbytag[$currtag] as $subject => $subjectnotes) {
                    echo "<tr>";
                    echo "<th>$subject</th>";
                    foreach ($subjectnotes as $note) {
                        $notestateclass = "";
                        if ($note->value > 4) {
                            $notestateclass = "grade-good";
                        } else if ($note->value < 4 && $note->value != 0) {
                            $notestateclass = "grade-bad";
                        }
                        echo "<td class='$notestateclass' title='$note->examName'>$note->value</td>";
                        $endnotes[$currtag][$subject] += $note->value;
                    }
                    if (count($subjectnotes) != 0) {
                        $endnotes[$currtag][$subject] /= count($subjectnotes);
                        $endnotes[$currtag][$subject] = round($endnotes[$currtag][$subject] * 2) / 2;
                    }
                    $endnotes[$currtag]['endnote'] += $endnotes[$currtag][$subject];
                    echo "</tr>";
                }
                if (count($notesbytag[$currtag]) != 0) {
                    $endnotes[$currtag]["endnote"] /= count($notesbytag[$currtag]);
                    $endnotes[$currtag]["endnote"] = round($endnotes[$currtag]["endnote"] * 2) / 2;
                }
                ?>
            </table>
        </div>
        <div class="calculates">
            <h2>Notenberechnung</h2>
            <table class="table-notes-end">
                <?php
                    $notestateclass = "";
                    if ($endnotes["bs"]["endnote"] > 4) {
                        $notestateclass = "grade-good";
                    } else if ($endnotes["bs"]["endnote"] < 4 && $endnotes["bs"]["endnote"] != 0) {
                        $notestateclass = "grade-bad";
                    }
                    echo "<tr><th>Endnote BS: </th><td class='$notestateclass'>".$endnotes["bs"]["endnote"] ."</td></tr>";
                    $notestateclass = "";
                    if ($endnotes["uek"]["endnote"] > 4) {
                        $notestateclass = "grade-good";
                    } else if ($endnotes["uek"]["endnote"] < 4 && $endnotes["uek"]["endnote"] != 0) {
                        $notestateclass = "grade-bad";
                    }
                    echo "<tr><th>Endnote ÜK: </th><td class='$notestateclass'>".$endnotes["uek"]["endnote"] ."</td></tr>";
                ?>
            </table>
        </div>
    </div>
</div>