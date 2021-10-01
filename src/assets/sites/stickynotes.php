<?php
require_once("../../config.php");

if (isset($_COOKIE["sessionkey"]) and isset($_COOKIE["sessionid"])) {
    $userdata = getUserData($_COOKIE["sessionkey"]);
    if (hash("sha256", $userdata->username . $_SERVER["REMOTE_ADDR"]) == $_COOKIE["sessionid"]) {
        $user = $userdata;
        $login = true;
    } else {
        header("Location: " . $rootpath . "index.php?logout=true");
    }
} else {
    header("Location: " . $rootpath . "index.php?logout=true");
}

if (isset($_GET["action"]) && $_GET["action"] == "GetStickyNoteValue") {
    echo getStickyNotesVal($_GET["noteid"])->value;
    exit;
}

if (isset($_POST["action"]) && $_POST["action"] == "SafeStickyNote") {
    echo saveStickyNote($_POST["noteid"], urldecode($_POST["value"]));
    exit();
}

if (isset($_POST["deleteStickyNote"])) {
    deleteStickyNote($_POST["stickynoteid"]);
    header("Location: " . $_SERVER["PHP_SELF"]);
}

if (isset($_POST["changestickynote"])) {
    ChangeStickyNoteTitle($_POST["newitle-stickynoteid"], $_POST["newtitle"]);
    header("Location: " . $_SERVER["PHP_SELF"]);
}

if (isset($_POST["createstickynote"])) {
    createStickyNote($_POST["title"], "", $user->id);
    header("Location: " . $_SERVER["PHP_SELF"]);
}

require_once("navbar.php");
$stickynotes = getStickyNotes($user->id);

?>
<script src="<?php echo $rootpath ?>assets/js/editorjs/editor.js"></script>
<script src="<?php echo $rootpath ?>assets/js/editorjs/editor_header.js"></script>
<script src="<?php echo $rootpath ?>assets/js/editorjs/editor_list.js"></script>
<script src="<?php echo $rootpath ?>assets/js/editorjs/editor_table.js"></script>
<link rel="stylesheet" href="<?php echo $rootpath; ?>assets/css/stickynotes.css">
<div class="content-title-bar-container">
    <div style="display: inherit;">
        <h1>Notizen</h1>
        <button id="list-notes-expand" onclick="epxandNotesList();"><i class="fas fa-chevron-down"></i></button>

    </div>
    <div style="display: inherit;" class="config-editor-container">
        <button onclick="openCreateStickyNote();" class="create-stickyNote"><i class="fas fa-plus"></i></button>
        <div id="loading-container">
            <div class="loader"></div>
        </div>
        <button id="autosave-off-save-editor" onclick="saveStickyNote(this.name);">Speichern</button>
        <div class="set-autosave-container">
            <input type="checkbox" id="set-autosave" onchange="set_autosave(this);" checked><label for="set-autosave">Auto Save</label>
        </div>
    </div>
</div>
<?php
if (count($stickynotes) == 0) {
?>
    <div id="no-stickynotes-found">
        <div class="no-stickynotes-found-imgcontainer">
            <img src="<?php echo $rootpath; ?>assets/img/nothing-found.png" alt="Nichts gefunden">
        </div>
        <p>Hier ist noch nichts. Klicke oben rechts auf das Plus um eine neue Notiz zu erstellen</p>
    </div>
    <script>
        nostickynotesfound = true;
    </script>
<?php
}
?>
<div id="create-stickynote-container">
    <div class="create-stickynote-header">
        <h2>Neue Notiz erstellen</h2>
        <p onclick="openCreateStickyNote();"><i class="fas fa-times"></i></p>
    </div>
    <div class="create-stickynote-body">
        <form action="" method="POST">
            <label>Name </label>
            <input type="text" id="create-stickynote-newtitle" value="Neue Notiz" name="title">
            <button type="submit" id="create-stickynote-submitbtn" name="createstickynote">Erstellen</button>
        </form>
    </div>
</div>
<div id="change-stickynote-title-container">
    <div class="change-stickynote-title-header">
        <h2>Notiztitel ändern</h2>
        <p onclick="openEditStickyNoteTitle();"><i class="fas fa-times"></i></p>
    </div>
    <div class="change-stickynote-title-body">
        <form action="" method="POST">
            <label>Neuer Titel </label>
            <input type="text" id="change-stickynote-title-newtitle" name="newtitle">
            <input type="hidden" id="change-stickynote-title-id" name="newitle-stickynoteid">
            <button type="submit" name="changestickynote" id="change-stickynote-title-submitbtn">Speichern</button>
        </form>
    </div>
</div>
<div class="content-container-stickynotes">
    <div id="list-notes-container">
        <?php
        foreach ($stickynotes as $stickynote) {
        ?>
            <div class="stickynotelistelement" onclick='showStickNote(this.getElementsByClassName("hiddennoteinput")[0].value, this);' onmouseover="showTools(this);" onmouseout="deshowTools(this);">
                <input class="hiddennoteinput" type="hidden" value='<?php echo json_encode($stickynote); ?>'>
                <div class="stickynotelistelementIcon"><i class="far fa-sticky-note"></i></div>
                <div class="StickyNoteListElActionsContainer StickyNoteListElActionsContainer_ex">
                    <form action="" method="POST">
                        <input type="hidden" name="stickynoteid" value="<?php echo $stickynote->id; ?>">
                        <button class="stickynotelistelementDelete" type="submit" name="deleteStickyNote"><i class="far fa-trash-alt"></i></button>
                    </form>
                    <div class="stickynotelistelementEdit" onclick="openEditStickyNoteTitle(<?php echo $stickynote->id; ?>, '<?php echo $stickynote->title; ?>');"><i class="fas fa-pencil-alt"></i></div>
                </div>
                <div class="stickynotelistelementContainer">
                    <p class="stickynotelistelementTitle"><?php echo $stickynote->title ?></p>
                    <p class="stickynotelistelementTime"><?php echo $stickynote->createdate ?></p>
                </div>
            </div>
        <?php
        }
        ?>
    </div>
    <div id="notes-editor-container">
        <div id="editorjs">

        </div>
    </div>
</div>
<script>
    let StickyNoteList_expanded = true;
    let current_editor;
    let autosave = true;

    function showStickNote(stickynote, el) {
        stickynote = JSON.parse(stickynote);
        stickynotelistelements = document.getElementsByClassName("stickynotelistelement");
        for (let i = 0; i < stickynotelistelements.length; i++) {
            stickynotelistelements[i].classList.remove("stickynotelistelement-active");
        }
        el.classList.add("stickynotelistelement-active");
        if (current_editor != undefined) {
            current_editor.destroy();
        }

        //Get Content
        const get_xhttp = new XMLHttpRequest();
        get_xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                stickynotevalue = "";
                try {
                    stickynotevalue = JSON.parse(this.responseText);
                } catch (e) {
                    stickynotevalue = "";
                }

                current_editor = new EditorJS({
                    tools: {
                        header: Header,
                        list: List,
                        table: Table
                    },
                    holder: 'editorjs',
                    data: stickynotevalue,
                    autofocus: true,
                    onChange: () => {
                        if (autosave) {
                            saveStickyNote(stickynote.id);
                        }
                    }
                });
                document.getElementById("autosave-off-save-editor").name = stickynote.id;
            }
        };
        get_xhttp.open("GET", "<?php echo $_SERVER["PHP_SELF"]; ?>?action=GetStickyNoteValue&noteid=" + stickynote.id);
        get_xhttp.send();
    }
    if (document.getElementById("list-notes-container").getElementsByClassName("stickynotelistelement").length > 0) {
        document.getElementById("list-notes-container").getElementsByClassName("stickynotelistelement")[0].click();
    }

    function saveStickyNote(StickyNote_ID) {
        document.getElementById("loading-container").style.display = "flex";
        current_editor.save().then((outputData) => {
            //Get Input Data
            data = encodeURI(JSON.stringify(outputData));

            //Safe Input Notes Data
            var http = new XMLHttpRequest();
            var url = '<?php echo $_SERVER["PHP_SELF"]; ?>';
            var params = 'action=SafeStickyNote&noteid=' + StickyNote_ID + '&value=' + data;
            http.open('POST', url, true);

            //Send the proper header information along with the request
            http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

            http.onreadystatechange = function() {
                if (http.readyState == 4 && http.status == 200) {
                    if (this.responseText != true) {
                        console.log("Safe Sticky Note Data Failed");
                    }
                    document.getElementById("loading-container").style.display = "none";
                }
            }
            http.send(params);


        }).catch((error) => {
            console.log('Saving failed: ', error);
        });
    }

    function openCreateStickyNote() {
        el = document.getElementById("create-stickynote-container");
        if (el.style.display == "none" || el.style.display == "") {
            el.style.display = "flex";
        } else {
            el.style.display = "none";
        }
    }

    function epxandNotesList() {
        if (StickyNoteList_expanded) {
            StickyNoteList_expanded = false;
            document.getElementById("list-notes-container").style.width = "50px";
            document.getElementById("list-notes-expand").style.transform = "rotate(180deg)";
            document.getElementById("notes-editor-container").style.width = "calc(100% - 50px)";

            noteInfos = document.getElementsByClassName("stickynotelistelementContainer");
            for (let i = 0; i < noteInfos.length; i++) {
                noteInfos[i].style.display = "none";
            }

            deleletButtons = document.getElementsByClassName("StickyNoteListElActionsContainer");
            for (let i = 0; i < deleletButtons.length; i++) {
                deleletButtons[i].classList.remove("StickyNoteListElActionsContainer_ex");
                deleletButtons[i].classList.add("StickyNoteListElActionsContainer_notex");
            }
        } else {
            StickyNoteList_expanded = true;
            document.getElementById("list-notes-container").style.width = "250px";
            document.getElementById("list-notes-expand").style.transform = "rotate(360deg)";
            document.getElementById("notes-editor-container").style.width = "calc(100% - 50px)";

            noteInfos = document.getElementsByClassName("stickynotelistelementContainer");
            for (let i = 0; i < noteInfos.length; i++) {
                noteInfos[i].style.display = "block";
            }

            deleletButtons = document.getElementsByClassName("StickyNoteListElActionsContainer");
            for (let i = 0; i < deleletButtons.length; i++) {
                deleletButtons[i].classList.add("StickyNoteListElActionsContainer_ex");
                deleletButtons[i].classList.remove("StickyNoteListElActionsContainer_notex");
            }
        }
    }

    function showTools(el) {
        el.getElementsByClassName("StickyNoteListElActionsContainer")[0].style.display = "block";

    }

    function deshowTools(el) {
        el.getElementsByClassName("StickyNoteListElActionsContainer")[0].style.display = "none";
    }

    function set_autosave() {
        el = document.getElementById("set-autosave");
        if (el.checked) {
            autosave = true;
        } else {
            autosave = false;
        }
    }

    function openEditStickyNoteTitle(id = null, title = null) {
        el = document.getElementById("change-stickynote-title-container");
        if (el.style.display == "none" || el.style.display == "") {
            document.getElementById("change-stickynote-title-id").value = id;
            document.getElementById("change-stickynote-title-newtitle").value = title;
            el.style.display = "flex";
        } else {
            el.style.display = "none";
        }
    }
</script>