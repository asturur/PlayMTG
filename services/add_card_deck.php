<?php
session_start();
require "../include/database.php";
require "sessione.php";

// manca una funzione che verifica la validata di inserimento
// secondo le regole di magic e del mazzo scelto
$stmt = $mysql->prepare("INSERT INTO decks_cards (id_deck, id_card) VALUES (?, ?)");
$stmt->bind_param('dd', $_POST["id_deck"], $_POST["id_card"]);

$risposta = array();

if ($stmt->execute()) {
  $unique_id = $stmt->insert_id;
  $stmt = $mysql->prepare("SELECT name, id FROM magic_cards WHERE id = ?");
  $stmt->bind_param('d', $_POST["id_card"]);
  $stmt->bind_result($card_name, $id);
  if ($stmt->execute()) {
    while($stmt->fetch()) {
      $risposta["success"] = true;
      $risposta["id_card"] =  $id;
      $risposta["name"] =  $card_name;
      $risposta["unique_id"] = $unique_id;
    }
  }
} else {
  $risposta["success"] = false;
  $risposta["msg"] = $stmt->error;
}
$stmt->close();
header("content-type: application/json");
echo json_encode($risposta);
?>