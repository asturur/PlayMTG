<?php
	// lo scrip prepara query di questo tipo
	// INSERT INTO cards  
	// (id, relatedCardId, setNumber, name, searchName, description, flavor, colors, manaCost, convertedManaCost, cardSetName, type, subType, power, toughness, loyalty, rarity, artist, cardSetId, token, promo, rulings, formats, releasedAt) 
	//VALUES ('615', '0', '115', 'Howling Mine', 'howlingmine', 'At the beginning of each player's draw step, if Howling Mine is untapped, that player draws an additional card.', '', '["None"]', '2', '2', 'Unlimited Edition', 'Artifact', '', '0', '0', '0', 'Rare', 'Mark Poole', '2ED', '', '', '[{"releasedAt":"2004-10-04","rule":"If Howling Mine leaves the battlefield before it resolves, then the last known tap or untap state of the card is used for resolution."},{"releasedAt":"2004-10-04","rule":"It does not trigger at all if this is tapped at the start of the draw step, and it checks this again on resolution."},{"releasedAt":"2004-10-04","rule":"The additional draw is separate from any other draw during your draw step. It happens when the triggered ability resolves."},{"releasedAt":"2013-04-15","rule":"The triggered ability is put onto the stack after you have already drawn your card for the turn."}]', '[{"name":"Modern","legality":"Legal"},{"name":"Legacy","legality":"Legal"},{"name":"Vintage","legality":"Legal"},{"name":"Freeform","legality":"Legal"},{"name":"Prismatic","legality":"Legal"},{"name":"Tribal Wars Legacy","legality":"Legal"},{"name":"Classic","legality":"Legal"},{"name":"Singleton 100","legality":"Legal"},{"name":"Commander","legality":"Legal"}]', '1993-12-01')

// 128MB non sono abbastanza per un file JSON da 20 mb.
// PREPARO LA CONNESSIONE AL DB
require "../include/database.php";
ini_set('memory_limit','384M');
echo "Leggo file da 20MB \n";
$stringona = file_get_contents("cards2.json");
echo "Tento la decodifica JSON";
$card_array = json_decode($stringona);
$qr="INSERT INTO cards ";
foreach($card_array as $key => $card) {
	$part1 = " (";
 	$part2 = " VALUES (";
	foreach ( $card as $field=>$value) {
		$part1 .= $field.", ";
		if ($field == "colors" || $field == "rulings" || $field == "formats" ) {
			// di questi piccoli array mantengo la parte json.
			// non mi interessa fare sotto tabelle
			$value = json_encode($value);
		}
		$part2 .= "'".addslashes($value)."', ";
	}
	$part1 = substr($part1,0,-2);
	$part2 = substr($part2,0,-2);
	$part1 .= ")";
	$part2 .= ")";
	if (mysql_query($qr.$part1.$part2,$con)) {
    echo "INSERISCO CARTA ".$card->id." ".$card->name."\n";
  } else {
    echo "**ERROR** ".$card->id." ".$card->name." ".mysql_error()."\n";
  }
}
?>