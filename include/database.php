<?php
date_default_timezone_set('Europe/Berlin');
$DB_SERVER = "localhost";
$DB_USER = "*";
$DB_PASS = "*"; 
$DB_NAME = "*";
//
$con = mysql_pconnect($DB_SERVER, $DB_USER, $DB_PASS);
mysql_select_db($DB_NAME, $con);
$mysql = new mysqli($DB_SERVER, $DB_USER, $DB_PASS, $DB_NAME);

?>