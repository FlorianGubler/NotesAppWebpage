<?php
include "navbar.php";
?>
<link rel="stylesheet" href="<?php echo $rootpath; ?>assets/css/home.css">
<h1 id="title-home">Hallo, <?php echo $user->username; ?></h1>
<h2>Aktionen</h2>
<div id="notes-container">
	<div onclick="location.href='bms.php#bms_abschlussnoten';">
		<p class="dash-icon"><i class="fas fa-school"></i></p><a>BMS Noten</a>
	</div>
	<div onclick="location.href='lap.php#berufsfachschule_module';">
		<p class="dash-icon"><i class="fas fa-chalkboard-teacher"></i></p><a>IPA Noten</a>
	</div>
	<div onclick="location.href='addnote.php';">
		<p class="dash-icon"><i class="far fa-plus-square"></i></p><a>Noten hinzufügen</a>
	</div>
	<div onclick="location.href='stickynotes.php';">
		<p class="dash-icon"><i class="far fa-comment-alt"></i></p><a>Notizen</a>
	</div>
</div>