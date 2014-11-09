<?php
require "../include/require.php";
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
} else {
  $risposta["success"] = false;
  if ($stmt->errno === 1022) {
    $risposta["msg"] = "Nome deck già utilizzato";
  } else {
    $risposta["msg"] = $stmt->error;
  }
}
$stmt->close();
echo json_encode($risposta);
?>