<?php
function lista_mazzi($u_id) {
  global $con;
  $qr = "SELECT * FROM decks WHERE u_id = $u_id";
  $rst = mysql_query($qr, $con);
  $mazzi = array();
  while ($row = mysql_fetch_assoc($rst)) {
    $mazzi[$row["id"]] = $row; 
  }
  return $mazzi;
}
?>