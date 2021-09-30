<?php

$conn = oci_connect("usr", "secret", "localhost/notes", "UTF8");

$ex = oci_parse($conn, "ALTER SESSION SET NLS_NUMERIC_CHARACTERS = '.,'"); //Set delimiters for numbers
oci_execute($ex);

$oracle_user = "root";

//$rootpath = "https://dekinotu.myhostpoint.ch/notes/";
$rootpath = "\\";

require_once("assets/conf/dbfunctions.php");
