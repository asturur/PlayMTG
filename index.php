<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>MTG Pagina di benvenuto</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/index.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="index.js"></script>
  </head>
  <body>
  <div class="flip-container" ontouchstart="this.classList.toggle('hover');">
	<div class="flipper">
		<div class="front">
			<form onsubmit="return false" id="loginForm" >
      <label >Nome Utente: <input type="text" name="username" /></label>
      <label >Password: <input type="password" name="password" /></label>
      <label style="text-align: baseline" for="login" ><img src="images/tap.png" id="login" />: Tappa per entrare</label>
      <div>Non hai un account ? <input type="button" value="Registrati ora" id="register2" /></div>
      </form>
    </div>
    <div class="back">
      <form onsubmit="return false" id="registerForm" >
      <label >Nome:</label><input type="text" name="nome" />
      <label >Email:</label><input type="text" name="email" />
      <label >Nome Utente:</label><input type="text" name="username" />
      <label >Password:</label><input type="password" name="password" />
      <input type="checkbox" value="1" name="checked" />
      <input type="button" value="Registrati" id="register" />
      hai un account ? <input type="button" value="Entra ora" id="login2" />
      </form>
    </div>
    <div id="message">ksjdghskjghskjd</div>
  </div>
</div>
</body>
</html>
<?php ?>