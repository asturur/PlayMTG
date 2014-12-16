<?php
if (!isset($_SESSION["u_id"])) {
  $risposta["success"] = false;
  $risposta["msg"] = "Non loggato";
  echo json_encode($risposta);
  exit;
}
$name = $_SESSION["name"];
$u_id = $_SESSION["u_id"];
?>