<!-- Login,prima pagina che viene vista -->
<!DOCTYPE HTML>
<html>
<head>
	<title>Bostarter</title>
	<style>
		body {background-color: powderblue;text-align: center;}
	</style>
</head>
<body>
	<h1> Bostarter </h1>
	<div>
		<input type="email" name="email" required><br>
		<input type="password" name="password" required><br>
		<a href=HomePage.php>Login</a>
		<a href=registrazione.php>Se non hai un account registrati</a>
	</div>
</body>
</html>

<?php
	include("connessione/db.php"); 
	include("login.php"); 
?>