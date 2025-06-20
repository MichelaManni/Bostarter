<?php
session_start();
include 'Connessione/db.php';
$errore = '';

//Script per il login -> se arriva un post(tramite il pulsane login)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$Email = $_POST['Email'];
	$Password = $_POST['Password'];
	//Metodo    
	$Query = "SELECT Email FROM Utente where Email = '$Email' limit 1";
	$result  = mysqli_query($mysqli, $Query);
	$EmailDB = mysqli_fetch_assoc($result)['Email'];

	if ($Email == $EmailDB) {
		$Query = "SELECT Password FROM Utente where Email = '$Email' limit 1";
		$result  = mysqli_query($mysqli, $Query);
		$PasswordDB = mysqli_fetch_assoc($result)['Password'];

		if ($Password == $PasswordDB) {
			$_SESSION['Email'] = $Email;
			header("Location: HomePage.php");
			exit;
		} else {
			$errore = "Password errata.";
		}
	}
	else{
		$errore = "Email errata.";
	}
	mysqli_close($mysqli);
}
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