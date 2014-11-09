<?php
session_start();
require "include/database.php";

$user = mysql_real_escape_string($_POST["username"]);
$pass = mysql_real_escape_string($_POST["password"]);

$qr = "SELECT * FROM users WHERE username = '$user' AND password = MD5('$pass')";

$rst = mysql_query($qr, $con);
if ($rst && mysql_num_rows($rst) === 1) {
  $row = mysql_fetch_assoc($rst);
  $login = true;
  $_SESSION["u_id"] = $row["id"];
  $_SESSION["name"] = $row["name"];
  $_SESSION["username"] = $row["username"];
  $_SESSION["email"] = $row["email"];
  $url = "user_cp.php";
  $risposta["url"] = $url;
} else {
  $login = false;
  $msg = "dati di login errati";
  $risposta["msg"] = $msg;
}

$risposta["login"] = $login;
header("content-type: application/json");
echo json_encode($risposta);
?>