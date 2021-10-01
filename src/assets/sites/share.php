<?php
include "../../config.php";

$checklogin = true;
$invalid_sessionLink = false;

if (isset($_POST['submit_token']) && isset($_POST['token']) && isset($_GET['link'])) {
    $sessionLink = checkSessionLink($_GET["link"], $_POST["token"]);
    if ($sessionLink != false) {
        $semesters = getSemesters();
        $schools = getSchools();
        $subjects = getSubjects();
        $notes = getNotes($sessionLink["FK_USER"]);
        $user = getUserData($sessionLink["FK_USER"]);
    } else {
        $checklogin = false;
        $invalid_sessionLink = true;
    }
} else {
    $checklogin = false;
}
?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sharing</title>
    <link rel="shortcut icon" type="image/x-icon" href="../img/icon.ico">
    <link rel="stylesheet" href="<?php echo $rootpath; ?>assets/css/navbar.css">
    <link rel="stylesheet" href="<?php echo $rootpath; ?>assets/css/share.css">
    <link rel="stylesheet" href="<?php echo $rootpath; ?>assets/css/bms.css">
    <link rel="stylesheet" href="<?php echo $rootpath; ?>assets/css/lap.css">
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
        <div class="navigation">
            <?php
            foreach ($schools as $school) {
                echo "<div onclick='navigationMgr(\"$school->schoolName\");'>$school->schoolName</div>";
            }
            ?>
        </div>
        <div id="content-container">
            <div class="school-content" id="BMS">
                <?php
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
                        array_push($notessemester[$note->semesterTag][$note->subjectName], $note);
                    }
                }

                function checkifOverSubjectsExist($subjectname)
                {
                    global $notessemester;
                    foreach ($notessemester as $semester) {
                        foreach ($semester as $subject => $subjectnotes) {
                            if ($subject == $subjectname) {
                                return true;
                            }
                        }
                    }
                    return false;
                }
                ?>
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
                <div class="tables">
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
                                            if (!checkifOverSubjectsExist($overSubject) || !isset($endnotessemester[$semester][$overSubject])) {
                                                $endnotessemester[$semester][$overSubject] = 0;
                                                $endnotessemester[$semester][$overSubject . "_count"] = 0;
                                            }
                                            $endnotessemester[$semester][$overSubject . "_count"] += 1;
                                            $endnotessemester[$semester][$overSubject] += $endnotesubject;
                                            if (!checkifOverSubjectsExist($overSubject)) {
                                                $endnotessemester[$semester][$overSubject] /= $endnotessemester[$semester][$overSubject . "_count"];
                                            }
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
            </div>
            <div class="school-content" id="LAP">
                <?php
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
            </div>
        </div>

    <?php } ?>
    <script>
        function navigationMgr(openid) {
            contents = document.getElementById("content-container").getElementsByClassName("school-content");
            for (let i = 0; i < contents.length; i++) {
                contents[i].style.display = "none";
            }
            document.getElementById(openid).style.display = "block";
        }
        navigationMgr("BMS");
    </script>
</body>

</html>