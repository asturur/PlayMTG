<?php
	// lo scrip prepara query di questo tipo

// 128MB non sono abbastanza per un file JSON da 20 mb.
// PREPARO LA CONNESSIONE AL DB
require "../include/database.php";
ini_set('memory_limit','512M');
echo "Leggo file da 20MB \n";
$stringona = file_get_contents("AllSets-x.json");
echo "Tento la decodifica JSON";
$card_array = json_decode($stringona);
$qr="INSERT INTO sets ";
echo "Dump di una CARTA\n";
foreach($card_array as $key => $set) {
  $part1 = " (";
 	$part2 = " VALUES (";
  foreach ( $set as $field=>$value) {
    if ($field != "cards") {
      if (is_array($value)) $value=json_encode($value);
      $part1 .= $field.", ";
      $part2 .= "'".$value."', ";
    } else {
      $lista_carte=$value;
    }
  }
	$part1 = substr($part1,0,-2);
	$part2 = substr($part2,0,-2);
	$part1 .= ")";
	$part2 .= ")";
  echo $querySet = $qr.$part1.$part2;
	foreach ($lista_carte as $key2=>$card) {
		foreach ($card as $field=>$value) {
      if(is_array($value) || is_object($value)) $value= json_encode($value);
      echo $field.": ".$value."\n";
    }
	}
  exit;
	//echo "INSERISCO CARTA ".$card["id"]."\n";
	//mysql_query($qr.$part1.$part2,$con);
}
?>