<?php
include "navbar.php";
$notes = getNotes($user->id);
$subjects = getSubjects();

$notesbytag = array();
$endnotes = array();
$endnotes["endnote"] = 0;
foreach ($subjects as $subject) {
    if ($subject->schoolName == "LAP") {
        if (!isset($notesbytag[$subject->additionalTag])) {
            $notesbytag[$subject->additionalTag] = array();
        }
        if (!isset($endnotes[$subject->additionalTag])) {
            $endnotes[$subject->additionalTag] = array();
            $endnotes[$subject->additionalTag]["endnote"] = 0;
        }
        foreach ($notes as $note) {
            if ($note->schoolName == "LAP" && $note->subjectName == $subject->subjectName) {
                if (!isset($notesbytag[$note->additionalTag][$note->subjectName])) {
                    $notesbytag[$note->additionalTag][$note->subjectName] = array();
                }
                if (!isset($endnotes[$note->additionalTag][$note->subjectName])) {
                    $endnotes[$note->additionalTag][$note->subjectName] = 0;
                }
                array_push($notesbytag[$note->additionalTag][$note->subjectName], $note);
            }
        }
    }
}
?>
<link rel="stylesheet" href="<?php echo $rootpath; ?>assets/css/lap.css">
<div id="top-bar-gui">
    <h1 id="title-home">IPA Noten</h1>
    <a href="<?php echo $rootpath; ?>assets/sites/addnote.php" id="edit-table"><i class="far fa-edit"></i></a>
</div>
<ul id="semesters">
    <?php
    foreach ($notesbytag as $currtag => $tagsubjects) {
        $currtagid = urlencode(strtolower(str_replace(' ', '_', $currtag)));
    ?>
        <li class="semester-choose"><a href="#<?php echo $currtagid ?>"><?php echo $currtag; ?></a></li>
    <?php
    }
    ?>
</ul>
<div class="tables">
    <div id="tables">
        <div class="tables-container">
            <?php
            foreach ($notesbytag as $currtag => $tagsubjects) {
                $currtagid = urlencode(strtolower(str_replace(' ', '_', $currtag)));
                echo "<div class='semester-content' id='$currtagid'>";
                echo "<table class='table-notes-norm'>";
                foreach ($tagsubjects as $subject => $subjectnotes) {
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
                    $endnotes[$currtag]["endnote"] += $endnotes[$currtag][$subject];
                    echo "</tr>";
                }
                if (count($tagsubjects) != 0) {
                    $endnotes[$currtag]["endnote"] /= count($tagsubjects);
                    $endnotes[$currtag]["endnote"] = round($endnotes[$currtag]["endnote"] * 2) / 2;
                }
                echo "</table>";
                echo "</div>";
            }
            ?>
        </div>
        <div class="calculates">
            <h2>Notenberechnung</h2>
            <table class="table-notes-end">
                <?php
                if (isset($endnotes["Berufsfachschule Module"]["endnote"]) && isset($endnotes["ÜK Module"]["endnote"])) {
                    $endnotes["Informatikkompetenzen"] = round((($endnotes["Berufsfachschule Module"]["endnote"] * 0.8) + ($endnotes["ÜK Module"]["endnote"] * 0.2)) * 2) / 2;
                } else {
                    $endnotes["Informatikkompetenzen"] = 0;
                }

                if (isset($endnotes["IPA Abschlussprüfung"]["Resultat der Arbeit"]) && isset($endnotes["IPA Abschlussprüfung"]["Fachgespräch und Präsentation"]) && isset($endnotes["IPA Abschlussprüfung"]["Dokumentation"])) {
                    $endnotes["Abschlussarbeit"] = round((($endnotes["IPA Abschlussprüfung"]["Resultat der Arbeit"] * 0.5) + ($endnotes["IPA Abschlussprüfung"]["Fachgespräch und Präsentation"] * 0.25) + ($endnotes["IPA Abschlussprüfung"]["Dokumentation"] * 0.25)) * 2) / 2;
                } else {
                    $endnotes["Abschlussarbeit"] = 0;
                }

                //Unset all other Endnotes
                unset($endnotes["ÜK Module"]);
                unset($endnotes["Berufsfachschule Module"]);
                unset($endnotes["IPA Abschlussprüfung"]);

                foreach ($endnotes as $currtag => $tagsubjects) {
                    if ($currtag != "endnote") {
                        $notestateclass = "";
                        if ($endnotes[$currtag] > 4) {
                            $notestateclass = "grade-good";
                        } else if ($endnotes[$currtag] < 4 && $endnotes[$currtag] != 0) {
                            $notestateclass = "grade-bad";
                        }
                        echo "<tr><th>Endnote $currtag: </th><td class='$notestateclass'>" . $endnotes[$currtag] . "</td></tr>";
                    }
                }
                if ($endnotes["Informatikkompetenzen"] == 0) {
                    $endnotes["endnote"] = $endnotes["Abschlussarbeit"];
                } else if ($endnotes["Abschlussarbeit"] == 0) {
                    $endnotes["endnote"] = $endnotes["Informatikkompetenzen"];
                } else {
                    $endnotes["Endnote"] = round(($endnotes["Abschlussarbeit"] * 0.5) + ($endnotes["Informatikkompetenzen"] * 0.5), 1);
                }
                $notestateclass = "";
                if ($endnotes["endnote"] > 4) {
                    $notestateclass = "grade-good";
                } else if ($endnotes["endnote"] < 4 && $endnotes["endnote"] != 0) {
                    $notestateclass = "grade-bad";
                }
                echo "<tr><th>Endnote LAP: </th><td class='$notestateclass'>" . $endnotes["endnote"] . "</td></tr>";
                ?>
            </table>
        </div>
    </div>
</div>