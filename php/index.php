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
		<form action="login.php" method="post">
			<input type="Email" name="Email" required /><br>
			<input type="Password" name="Password" required /><br>	
			<a href=HomePage.php><button type="submit">Login</button></a><br>
			<a href=registrazione.php>Se non hai un account registrati qui!</a>
		</form>
	</div>
</body>
</html>

<?php
	include("connessione/db.php"); 
?>