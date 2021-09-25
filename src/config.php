<?php
//$conn = new mysqli("dekinotu.mysql.db.hostpoint.ch", "dekinotu_user1", "CBXG2pfrpKkDWsG", "dekinotu_notenberechnung");
$conn = new mysqli("localhost", "root", "", "notes");
mysqli_set_charset($conn, "utf8");

//$rootpath = "https://dekinotu.myhostpoint.ch/notes/";
$rootpath = "\\";

require_once("assets/conf/dbfunctions.php");
