<?php
session_start();
//*Pagina per il login degli admin, se al login si risulta admin si viene reindirizzati qui
include 'Connessione/db.php';
$errore = '';
//Molto simile al login normale ma viene controllato solo il codice di sicurezza
//Chiama la stored procedure dell'autenticazione admin che è quasi identica appunto all'autenticazione normale
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Cod_Inserito'])) {
	$Email_Inserita = $_SESSION['Email'] ?? '';
	$Cod_Inserito = $_POST['Cod_Inserito'] ?? '';

	$stmt = $mysqli->prepare("CALL AutenticazioneAdmin(?, ?, @Esito)");
	$stmt->bind_param("ss", $Email_Inserita, $Cod_Inserito);
	$stmt->execute();
	$stmt->close();
	$result = $mysqli->query("SELECT @Esito AS esito");
	$row = $result->fetch_assoc();

	if ($row['esito']) {
		header("Location: HomePage.php");
		$_SESSION['Codice'] = $Cod_Inserito;
		exit();
	} else {
		$errore = "Codice di sicurezza non valido";
	}
}
$mysqli->close();
?>

<!DOCTYPE HTML>
<html>

<head>
	<title>Bostarter</title>
	<link rel="stylesheet" href="style.css">
</head>

<body>
	<h1> Autenticarsi come amministratore, inserie codice di sicurezza </h1>
	<div>
		<form action="" method="post">
			<input type="Number" name="Cod_Inserito" required /><br>
			<a><button type="submit">Login</button></a><br>
		</form>
		<a href="index.php"><button>Esci</button></a><br>
		<?php if ($errore): ?>
			<div class="errore"><?= htmlspecialchars($errore) ?></div>
		<?php endif; ?>
	</div>
</body>

</html>