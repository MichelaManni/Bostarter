<?php 
session_start();
include 'Connessione/Login.php'
?>

<!DOCTYPE HTML>
<html>

<head>
	<title>Bostarter</title>
	<style>
		body {
			background-color: powderblue;
			text-align: center;
		}
	</style>
</head>

<body>
	<h1> Bostarter </h1>
	<div>
		<form action="" method="post">
			<input type="Text" name="Email" required /><br>
			<input type="Password" name="Password" required /><br>
			<a href=HomePage.php><button type="submit">Login</button></a><br>
			<a href=registrazione.php>Se non hai un account registrati qui!</a>
		</form>
		<?php if ($errore): ?>
			<div class="errore"><?= htmlspecialchars($errore) ?></div>
		<?php endif; ?>
	</div>
</body>

</html>