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
//fare una query per contare i mazzi dell utente e vedere se sono troppi
// o qui oppure prima di arrivare qui, direttamente dal tasto nuovo mazzo
// manca il parametro per l-utente  che specifica quanti mazzi puo'avere
// oltre che manca un pannello per impostare questi parametri.
$stmt = $mysql->prepare("INSERT INTO decks (u_id, deckname) VALUES (?, ?)");
$stmt->bind_param('ds', $u_id, $_POST["deckname"]);

$risposta = array();

if ($stmt->execute()) {
  $risposta["success"] = true;
  $risposta["nome"] = $_POST["deckname"];
  $risposta["id"] = $stmt->insert_id;
} else {
  $risposta["success"] = false;
  if ($stmt->errno == 1062) {
    $risposta["msg"] = "Nome deck già utilizzato";
  } else {
    $risposta["msg"] = $stmt->error." ".$stmt->errno;
  }
}
$stmt->close();
header("content-type: application/json");
echo json_encode($risposta);
?>