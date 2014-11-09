<?php
session_start();
require "../include/database.php";
if (!isset($_SESSION["u_id"])) {
  $risposta["success"] = false;
  $risposta["msg"] = "Non loggato";
  echo json_encode($risposta);
  exit;
}
$name = $_SESSION["name"];
$u_id = $_SESSION["u_id"];

// manca una funzione che verifica la validata di inserimento
// secondo le regole di magic e del mazzo scelto
$stmt = $mysql->prepare("INSERT INTO decks_cards (id_deck, id_card) VALUES (?, ?)");
$stmt->bind_param('dd', $_POST["id_deck"], $_POST["id_card"]);

$risposta = array();

if ($stmt->execute()) {
  $risposta["success"] = true;
} else {
  $risposta["success"] = false;
  $risposta["msg"] = $stmt->error;
}
$stmt->close();
header("content-type: application/json");
echo json_encode($risposta);
?>