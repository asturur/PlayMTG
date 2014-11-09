<?php
session_start();
require "include/require.php";
$stile = "deck_manager.css";
$javas[] = "deck_manager.js";
$javas[] = "include/jquery-ui.min.js";
$javas[] = "include/fabric.min.js";
require "menu.php";
require "include/functions.php";
$lista_mazzi = lista_mazzi($u_id);
echo "<br />
<div id=\"lista_mazzi\" >\n
  <div class=\"deck_button\" onclick=\"crea_nuovo_mazzo()\" >Crea un nuovo mazzo</div>\n";
foreach ($lista_mazzi as $key => $value) {
  echo "<div class=\"deck_button\" onclick=\"modifica_mazzo('$key');\" >".$value["deckname"]."</div>\n";
}
echo "</div>";
?>
<div >
  <div id="deck_mod_title"></div>
  <canvas id="tavolo"></canvas>
  <div id="deck_mod">
  <button onclick="aggiungi_carta">Aggiungi</button>
  <button onclick="rimuovi_carta">Rimuovi</button>
  <button onclick="duplica_carta">Duplica</button>
  <form name="ricerca" id="ricerca" onsubmit="return false" >
  <input type="text" name="search" /><input type="button" onclick="avvia_ricerca()" value="cerca" />
  <div id="search_result"></div>
  <div id="image_card">
    <img id="card" src="" /><br />
    <button onclick="inserisci_carta()">ok</button>
  </div>
  </form>
  </div>
</div>