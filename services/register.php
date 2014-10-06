<?php
require "../include/database.php";
require "../include/mail.class.php";

$stmt=$mysql->prepare("INSERT INTO users (username, name, email, password, ip_address) VALUES (?, ?, ?, ENCRYPT(?), ?)");
$stmt->bind_param('sssss', $_POST["username"], $_POST["nome"],  $_POST["email"], $_POST["password"], $_SERVER["REMOTE_ADDR"]);

$risposta = array();

if ($stmt->execute()) {
  //se inserito correttamente inviare la mail di conferma con link attivazione.
  $mail->new mia_mail();
  $mail->addTo($_POST["email"], $_POST["nome"]);
  $mail->Subject = "Benvenuto su Play MTG online.";
  $mail->html = "blah bla blah in HTML";
  $mail->Invia();
  $risposta["email"] = $_POST["email"];
  $risposta["success"] = true;
} else {
  $risposta["success"] = false;
  $risposta["messaggio"] = $stmt->error;
  //restituire un messaggio di errore qualsiasi
}
echo json_encode($risposta);
?>
