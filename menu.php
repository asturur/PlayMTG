<html >
<head>
  <link href="css/style.css" rel="stylesheet" type="text/css">
  <link href="css/jquery-ui.css" rel="stylesheet" type="text/css">
  <link href="css/<?php echo $stile; ?>" rel="stylesheet" type="text/css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <?php foreach ($javas as $value) { ?>
	<script type="text/javascript" src="<?php echo $value; ?>"></script>
<?php } ?>
</head>
<body>
<div id="dialog" title=""></div>
<?php
  echo "Benvenuto ".$name."<br />";
  echo "<a href=\"deck_manager.php\">Gestione Deck</a>";
?>