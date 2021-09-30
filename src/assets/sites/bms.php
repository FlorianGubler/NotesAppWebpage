<?php
include "navbar.php";
$notes = getNotes($user->id);
$notessemester = array();
$endnotessemester = array();
foreach ($notes as $note) {
    if ($note->schoolName == "BMS") {
        if (!isset($notessemester[$note->semesterTag])) {
            $notessemester[$note->semesterTag] = array();
        }
        if (!isset($endnotessemester[$note->semesterTag])) {
            $endnotessemester[$note->semesterTag] = array();
        }
        if (!isset($notessemester[$note->semesterTag][$note->subjectName])) {
            $notessemester[$note->semesterTag][$note->subjectName] = array();
        }
        if (!isset($endnotessemester[$note->semesterTag][$note->subjectName])) {
            $endnotessemester[$note->semesterTag][$note->subjectName] = 0;
        }
        if (!isset($endnotessemester[$note->semesterTag][$note->subjectName])) {
            $endnotessemester[$note->semesterTag][$note->subjectName] = 0;
        }
        array_push($notessemester[$note->semesterTag][$note->subjectName], $note);
    }
}
?>
<link rel="stylesheet" href="<?php echo $rootpath; ?>assets/css/bms.css">
<div id="top-bar-gui">
    <h1 id="title-home">BMS Notentabelle</h1>
    <a href="<?php echo $rootpath; ?>assets/sites/addnote.php" id="edit-table"><i class="far fa-edit"></i></a>
</div>
<ul id="semesters">
    <?php
    foreach ($notessemester as $semester => $semesternotes) {
        $semesterid = urlencode(strtolower(str_replace(' ', '_', $semester)));
        echo "<li class='semester-choose'><a href='#$semesterid'>$semester</a></li>";
    }
    ?>
</ul>
<div id="tables">
    <?php
    foreach ($notessemester as $semester => $semesternotes) {
        $semesterid = urlencode(strtolower(str_replace(' ', '_', $semester)));
        echo "<div class='semester-content' id='$semesterid'>";
        echo "<table class='table-notes-norm'>";
        $endnotessemester[$semester]["endnote"] = 0;
        foreach ($semesternotes as $subject => $subjectnotes) {
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
                $endnotessemester[$semester][$note->subjectName] += $note->value;
            }
            if (count($subjectnotes) != 0) {
                $endnotessemester[$semester][$subject . "_count"] = count($subjectnotes);
            }
            echo "</tr>";
        }
        if (count($semesternotes) == 0) {
            echo "<p class='not-notes-found'>Keine Noten gefunden</p>";
        }
        echo "</table>";
    ?>
        <div class="calculates">
            <h2>Notenberechnung</h2>
            <table class="table-notes-end">
                <?php
                $semesternotescount = 0;
                foreach ($endnotessemester[$semester] as $subject => $endnotesubject) {
                    if (getSubjectsFromName($subject) != false) {
                        $overSubject = getSubjectsFromName($subject)->overSubject;
                        if ($overSubject != null) {
                            $endnotesubject /= $endnotessemester[$semester][$subject . "_count"];
                            if (!isset($endnotessemester[$semester][$overSubject])) {
                                $endnotessemester[$semester][$overSubject] = 0;
                                $endnotessemester[$semester][$overSubject . "_count"] = 1;
                            }
                            $endnotessemester[$semester][$overSubject] += $endnotesubject;
                            $endnotessemester[$semester][$overSubject] /= $endnotessemester[$semester][$overSubject . "_count"];
                            unset($endnotessemester[$semester][$subject]);
                        }
                    }
                }
                foreach ($endnotessemester[$semester] as $subject => $endnotesubject) {
                    if ($subject != "endnote") {
                        if (getSubjectsFromName($subject) != false) {
                            $overSubject = getSubjectsFromName($subject)->overSubject;
                            if ($overSubject = null) {
                            }
                            $endnotesubject /= $endnotessemester[$semester][$subject . "_count"];
                            $endnotesubject = round($endnotesubject * 2) / 2;
                            $endnotessemester[$semester]['endnote'] += $endnotesubject;
                            $semesternotescount++;
                            echo "<tr>";
                            echo "<th>$subject</th>";
                            if ($endnotesubject > 4) {
                                $notestateclass = "grade-good";
                            } else if ($endnotesubject < 4 && $endnotesubject != 0) {
                                $notestateclass = "grade-bad";
                            }
                            echo "<td class='$notestateclass'>$endnotesubject</td>";
                            echo "</tr>";
                        }
                    }
                }
                if ($semesternotescount != 0) {
                    $endnotessemester[$semester]["endnote"] /= $semesternotescount;
                    $endnotessemester[$semester]["endnote"] = round($endnotessemester[$semester]['endnote'], 1);
                }
                echo "<tr><div class='splitline'></div></tr>";
                $semesterendnote = $endnotessemester[$semester]["endnote"];
                $notestateclass = "";
                if ($endnotessemester[$semester]["endnote"] > 4) {
                    $notestateclass = "grade-good";
                } else if ($endnotessemester[$semester]["endnote"] < 4 && $endnotessemester[$semester]["endnote"] != 0) {
                    $notestateclass = "grade-bad";
                }
                echo "<tr><th>Endnote: </th><td class='$notestateclass'>$semesterendnote</td></tr>";
                ?>
            </table>
        </div>
    <?php
        echo "</div>";
    }
    ?>

</div>