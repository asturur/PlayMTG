<?php
session_start();
require "../include/database.php";

$stmt = $mysql->prepare("SELECT id,name FROM cards WHERE searchName LIKE ? LIMIT 50");
$param = "%".$_POST["search"]."%";
$stmt->bind_param('s', $param );
$stmt->bind_result($id, $name);
$risposta = array();
if ($stmt->execute()) {
  while($stmt->fetch()) {
    $risposta["cards"][] = array("id" => $id, "name" => $name);
  }
}
$stmt->close();
header("content-type: application/json");
echo json_encode($risposta);
?>