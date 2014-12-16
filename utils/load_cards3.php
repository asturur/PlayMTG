<?php
	// lo scrip prepara query di questo tipo

// 128MB non sono abbastanza per un file JSON da 20 mb.
// PREPARO LA CONNESSIONE AL DB
require "../include/database.php";
ini_set('memory_limit','512M');
echo "Leggo file da 20MB \n";
$stringona = file_get_contents("AllCards-x.json");
echo "Tento la decodifica JSON";
$card_array = json_decode($stringona);
foreach($card_array as $key => $card) {
  $pippo = $card_array->$key;
  var_dump($pippo);
  exit;
}
?>