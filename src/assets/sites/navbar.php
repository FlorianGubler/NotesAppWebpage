<?php
require_once("../../config.php");

$login = false;

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

if (isset($_GET["action"]) && $_GET["action"] == "renewsharelink") {
    echo json_encode(GetShareLink($user->id));
    exit;
}

?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo $rootpath; ?>assets/css/navbar.css">
    <link src="<?php echo $rootpath; ?>assets/js/cropprjs/croppr.css" rel="stylesheet" />

    <link rel="shortcut icon" type="image/x-icon" href="<?php echo $rootpath; ?>assets/img/icon.ico">

    <script src="<?php echo $rootpath ?>assets/js/fontawesome/all.js" data-auto-replace-svg></script> <!-- Fontawesome -->
    <script src="<?php echo $rootpath; ?>assets/js/copy.js"></script>
    <title>Helsana Noten</title>
</head>

<body>
    <div id="site-title-container">
        <a id="menu-expand" href="#menu"><i style="color: inherit;" class="fas fa-ellipsis-v"></i></a>
        <p id="site-title">Helsana Noten</p>
    </div>
    <button id="titlebar-shareIcon" onclick="openShareLink();"><i class="fas fa-share-alt"></i></button>
    <button id="site-download-update" onclick="console.error('No Update Available');"><i class="fas fa-download"></i></button>
    <button id="site-reload-btn" onclick="location.reload();"><i class="fas fa-redo"></i></button>
    <nav id="menu">
        <a id="menu-close" href="#"><svg viewBox="0 0 10 10">
                <polygon points="10.2,0.7 9.5,0 5.1,4.4 0.7,0 0,0.7 4.4,5.1 0,9.5 0.7,10.2 5.1,5.8 9.5,10.2 10.2,9.5 5.8,5.1" />
            </svg></a>
        <div id="nav-user">
            <img id="profile-pic" src="<?php echo $rootpath; ?>assets/img/profilepictures/<?php echo $user->profilepicture; ?>" alt="Profile Picture" draggable="false">
            <a href="<?php echo $rootpath; ?>assets/sites/configuser.php" id="menu-user" class="use-user-name"><?php echo $user->username; ?></a>
        </div>
        <div id="menu-splitline"></div>
        <ul>
            <li><a id="home-anchor" href="home.php"><i class="fas fa-home"></i> Home</a></li>
            <li><a id="bms-anchor" href="bms.php"><i class="fas fa-school"></i> BMS Notenstand</a></li>
            <li><a id="lap-anchor" href="lap.php#berufsfachschule_module"><i class="fas fa-chalkboard-teacher"></i> LAP Notenstand</a></li>
            <li><a id="addnote-anchor" href="addnote.php"><i class="far fa-plus-square"></i> Noten hinzufügen</a></li>
            <li><a id="addnote-anchor" href="stickynotes.php"><i class="far fa-comment-alt"></i> Sticky Notes</a></li>
            <?php
            if ($user->admin == true) {
            ?>
                <li id="navbar-item-admin-tools"><a id="addnote-anchor" href="admin.php"><i class="fas fa-unlock-alt"></i> Admin Tools</a></li>
            <?php
            }
            ?>
            <li><a href="<?php echo $rootpath; ?>index.php?logout=true" id="logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
        <div class="copyright-nav" style="cursor: pointer;">
            <img src="<?php echo $rootpath ?>assets/img/icon.png">
            <p>&copy; by Florian Gubler 2021 ZLI BLJ</p>
        </div>
    </nav>
    <div class="share-container" id="share-container">
        <div class="share-inner-container">
            <div class="share-title-info-container">
                <h3>Noten Teilen</h3>
                <p id="share-info"><i class="fas fa-info-circle"></i></p>

                <div id="share-info-text">
                    <p>
                        Diese Links werden nach ca. 1 Tag ungültig
                    </p>
                </div>
            </div>

            <button id="renew-share-link" onclick="renewSharelink(this);"><i class="fas fa-sync-alt"></i></button>

            <label>Sharelink</label>
            <div class="share-values-container">
                <a id="share-link" target="_blank">Link</a>
                <button class="share-copy-btn" onclick="copySmth(document.getElementById('share-link').href);"><i class="fas fa-copy"></i></button>
            </div>

            <label>Token</label>
            <div class="share-values-container">
                <p id="share-token">Token</p>
                <button class="share-copy-btn" onclick="copySmth(document.getElementById('share-token').innerHTML);"><i class="fas fa-copy"></i></button>
            </div>
        </div>
    </div>
    <script>
        el = document.getElementById("share-info");
        el.addEventListener("mouseover", e => {
            document.getElementById("share-info-text").style.display = "block";
        })
        el.addEventListener("mouseout", e => {
            document.getElementById("share-info-text").style.display = "none";
        })

        function openShareLink() {
            el = document.getElementById("share-container");
            if (el.style.display == "none" || el.style.display == "") {
                el.style.display = "block";
            } else {
                el.style.display = "none";
            }
        }

        function renewSharelink(el) {
            el.classList.add("renew-share-link-loading");
            const xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    newsharelink = null;
                    try {
                        newsharelink = JSON.parse(this.responseText);
                    } catch (e) {
                        console.log(this.responseText);
                    }
                    showNewShareLink(newsharelink);
                }
            };
            xhttp.open("GET", "<?php echo $_SERVER["PHP_SELF"]; ?>?action=renewsharelink");
            xhttp.send();
        }
        renewSharelink(document.getElementById("renew-share-link"));

        async function showNewShareLink(response) {
            LINK_MAX_LENGTH = 37;
            await sleep(2000);
            full_link = "<?php echo $rootpath; ?>share.php?link=" + response.link;
            document.getElementById("share-link").innerHTML = full_link.slice(0, LINK_MAX_LENGTH) + "...";
            if (full_link.lenght > 30) {
                document.getElementById("share-link").innerHTML = full_link;
            }
            document.getElementById("share-link").href = full_link;
            document.getElementById("share-token").innerHTML = response.token;
            document.getElementById("renew-share-link").classList.remove("renew-share-link-loading");
        }

        function sleep(milliseconds) {
            return new Promise(resolve => setTimeout(resolve, milliseconds));
        }
    </script>
</body>

</html>