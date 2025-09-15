<?php
session_start();
session_reset();
//*Pagina di login
include 'Login/Login.php';
?>

<!DOCTYPE HTML>
<html>

<head>
	<title>Bostarter</title>
	<link rel="stylesheet" href="style.css">
</head>

<body>
	<h1> Bostarter </h1>
	<div>
		<form action="" method="post">
			<p>Email</p>
			<input type="Text" name="Email_Inserita" required /><br>
			<p>Password</p>
			<input type="Password" name="Password_Inserita" required /><br>
			<a href=HomePage.php><button type="submit">Login</button></a><br>
			<a href=registrazione.php>Se non hai un account registrati qui!</a>
		</form>
		<?php if ($errore): ?>
			<div class="errore"><?= htmlspecialchars($errore) ?></div>
		<?php endif; ?>
	</div>
</body>

</html>