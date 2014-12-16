<?php
session_start();
require "../include/database.php";
require "sessione.php";

$stmt = $mysql->prepare("SELECT decks.deckname as title, cards.name, decks_cards.id_card as id, decks_cards.id as unique_id FROM `decks` 
                         LEFT JOIN decks_cards ON decks_cards.id_deck = decks.id
                         LEFT JOIN cards ON cards.id = decks_cards.id_card
                         WHERE decks.id = ? AND decks.u_id = ? ");
$stmt->bind_param('dd', $_POST["id_deck"], $u_id);
$stmt->bind_result($title, $name, $id, $unique_id);
$risposta = array();
if ($stmt->execute()) {
  $risposta["success"] = true;
  while($stmt->fetch()) {
    $risposta["title"] = $title;
    $risposta["deck"][] = array("id" => $id, "name" => $name, "unique_id" => $unique_id);
  }
}
$stmt->close();
header("content-type: application/json");
echo json_encode($risposta);
?>