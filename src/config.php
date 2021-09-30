<?php

$conn = oci_connect("usr", "secret", "localhost/notes", "UTF8");

$oracle_user = "root";

//$rootpath = "https://dekinotu.myhostpoint.ch/notes/";
$rootpath = "\\";

require_once("assets/conf/dbfunctions.php");
