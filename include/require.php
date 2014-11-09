<?php
session_start();
require_once "include/database.php";

if (!isset($_SESSION["u_id"])) {
  echo "non loggato"; exit;
}
$name = $_SESSION["name"];
$u_id = $_SESSION["u_id"];
?>