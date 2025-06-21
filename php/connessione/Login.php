<?php
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
			//Se la password matcha si può loggare,solo in questo caso
			//salva per la sessione ruolo e email che saranno usati per le operazioni successive
			$Query = "SELECT Ruolo FROM Utente where Email = '$Email' limit 1";
			$result  = mysqli_query($mysqli, $Query);
			$_SESSION['Ruolo'] = mysqli_fetch_assoc($result)['Ruolo'];
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