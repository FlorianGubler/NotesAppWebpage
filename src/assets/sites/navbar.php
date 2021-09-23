<?php
header('Content-Type: text/html; charset=UTF-8');

include "../../config.php";
$login = false;

if (isset($_COOKIE["sessionkey"]) and isset($_COOKIE["sessionid"])) {
    $userdata = getUserData($_COOKIE["sessionkey"]);
    if ($userdata->username . $_SERVER["REMOTE_ADDR"] == $_COOKIE["sessionid"]) {
        $user = $userdata;
        $login = true;
    } else {
        header("Location: " . $rootpath . "index.php");
    }
} else {
    header("Location: " . $rootpath . "index.php");
}
?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo $rootpath; ?>assets/css/navbar.css">
    <link src="<?php echo $rootpath; ?>assets/js/cropprjs/croppr.css" rel="stylesheet" />

    <link rel="shortcut icon" type="image/x-icon" href="../img/icon.ico">

    <script src="<?php echo $rootpath ?>assets/js/all.js" data-auto-replace-svg></script> <!-- Fontawesome -->
    <script src="http://code.createjs.com/createjs-2013.12.12.min.js"></script>
    <script src="<?php echo $rootpath; ?>assets/js/copy.js"></script>
    <script src="<?php echo $rootpath; ?>assets/js/sleep.js"></script>

    <title>ProMarks</title>
</head>

<body>
    <div id="site-title-container">
        <a id="menu-expand" onclick="document.getElementById('menu-expand').style.display = 'none';" href="#menu"><i style="color: inherit;" class="fas fa-ellipsis-v"></i></a>
        <p id="site-title">ProMarks</p>
    </div>
    <button id="titlebar-shareIcon" onclick="openShareLink();"><i class="fas fa-share-alt"></i></button>
    <button id="site-download-update" onclick="console.error('No Update Available');"><i class="fas fa-download"></i></button>
    <button id="site-reload-btn" onclick="location.reload();"><i class="fas fa-redo"></i></button>
    <nav id="menu">
        <a id="menu-close" onclick="document.getElementById('menu-expand').style.display = 'block';" href="#"><svg viewBox="0 0 10 10">
                <polygon points="10.2,0.7 9.5,0 5.1,4.4 0.7,0 0,0.7 4.4,5.1 0,9.5 0.7,10.2 5.1,5.8 9.5,10.2 10.2,9.5 5.8,5.1" />
            </svg></a>
        <div id="nav-user">
            <img id="profile-pic" src="<?php echo $rootpath; ?>assets/img/profilepictures/<?php echo $user->profilepicture; ?>" alt="Profile Picture" draggable="false">
            <a href="<?php echo $rootpath; ?>assets/sites/configuser.php" id="menu-user" class="use-user-name"><?php echo $user->username; ?></a>
        </div>
        <div id="menu-splitline"></div>
        <ul>
            <li><a id="home-anchor" href="home.php"><i class="fas fa-home"></i> Home</a></li>
            <li><a id="bms-anchor" href="bms.php#bms_abschlussnoten"><i class="fas fa-school"></i> BMS Notenstand</a></li>
            <li><a id="lap-anchor" href="lap.php#BerufsfachschuleModule"><i class="fas fa-chalkboard-teacher"></i> LAP Notenstand</a></li>
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
        <div class="copyright-nav" style="cursor: pointer;" onclick='window.api.send("toMain", JSON.stringify({ type: "Window", cmd: "OpenExternal", attributes: JSON.stringify("https://dekinotu.myhostpoint.ch/notes/") }))'>
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
                        Diese Links werden nach ca. 1 Tag ungültig. Zudem ist zurzeit nur das BMS Notensharing möglich.
                    </p>
                </div>
            </div>

            <button id="renew-share-link" onclick="renewSharelink();"><i class="fas fa-sync-alt"></i></button>

            <label>Sharelink</label>
            <div class="share-values-container">
                <p id="share-link">example.com</p>
                <button class="share-copy-btn" onclick="copySmth(document.getElementById('share-link').getAttribute('link'));"><i class="fas fa-copy"></i></button>
            </div>

            <label>Token</label>
            <div class="share-values-container">
                <p id="share-token">123456</p>
                <button class="share-copy-btn" onclick="copySmth(document.getElementById('share-token').innerHTML);"><i class="fas fa-copy"></i></button>
            </div>
        </div>
    </div>
    <div id="internet-connection-failed">
        <img src="<?php echo $rootpath ?>assets/img/ban.svg" alt="ban-icon">
        <p>Es konnte keine Verbindung zum Server hergestellt werden</p>
    </div>
    <div id="retry" onclick="location.reload(true)">
        <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd">
            <path d="M7 9h-7v-7h1v5.2c1.853-4.237 6.083-7.2 11-7.2 6.623 0 12 5.377 12 12s-5.377 12-12 12c-6.286 0-11.45-4.844-11.959-11h1.004c.506 5.603 5.221 10 10.955 10 6.071 0 11-4.929 11-11s-4.929-11-11-11c-4.66 0-8.647 2.904-10.249 7h5.249v1z" />
        </svg>
        <p>Wiederholen</p>
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

        function renewSharelink() {
            //Renew SharLink with Ajax Request
        }

        async function showNewShareLink(response) {
            await sleep(2000);
            full_link = "https://dekinotu.myhostpoint.ch/notes/share.php?link=" + response.link;
            document.getElementById("share-link").setAttribute("link", full_link);
            document.getElementById("share-link").addEventListener("click", e => {
                window.api.send('toMain', JSON.stringify({
                    type: 'Window',
                    cmd: 'OpenExternal',
                    attributes: JSON.stringify(full_link)
                }));
            });
            document.getElementById("share-link").innerHTML = full_link.slice(0, 30) + "...";
            document.getElementById("share-token").innerHTML = response.token;
            document.getElementById("renew-share-link").classList.remove("renew-share-link-loading");
        }
    </script>
</body>

</html>