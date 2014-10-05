<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>MTG Pagina di benvenuto</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="index.js"></script>
  </head>
  <body>
    <form onsubmit="return false" id="loginForm" >
      <label >Nome Utente:</label><input type="text" name="username" />
      <label >Password:</label><input type="password" name="password" />
      <input type="button" value="Entra" id="login" />
      Non hai un account ? <input type="button" value="Registrati ora" id="register2" />
    </form>
    <form onsubmit="return false" id="registerForm" >
      <label >Nome:</label><input type="text" name="login" />
      <label >Email:</label><input type="text" name="email" />
      <label >Nome Utente:</label><input type="text" name="username" />
      <label >Password:</label><input type="password" name="password" />
      <input type="checkbox" value="1" name="checked" />
      <input type="button" value="Registrati" id="register" />
      hai un account ? <input type="button" value="Entra ora" id="login2" />
    </form>    
  </body>
</html>
<?php ?>
