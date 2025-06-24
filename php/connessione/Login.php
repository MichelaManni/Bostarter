<?php
include 'Connessione/db.php';
$errore = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['Email'] ?? '';
    $password_inserita = $_POST['Password'] ?? '';
    //Usa la stored procedure autenticazione ottenere il ruolo serve per reindirizzare ad un ulteriore
	//autenticazione nel caso sia admin
    	$stmt = $mysqli->prepare("CALL Autenticazione(?, ?, @Esito, @RuoloRegistrato)");
        $stmt->bind_param("ss", $email, $password_inserita);
        $stmt->execute();
        $stmt->close();
        $result = $mysqli->query("SELECT @Esito AS esito, @RuoloRegistrato AS ruolo");
        $row = $result->fetch_assoc();

        if ($row['esito']) {
			$_SESSION['Email'] = $email;
			$_SESSION['Ruolo'] = $row['ruolo'];
            if (strtolower($row['ruolo']) === 'amministratore') {
                header("Location: AdminLogin.php");
            } else {
                header("Location: HomePage.php");
            }
            exit();}	
		else {$errore = "Email o password errati.";}
}
$mysqli->close();
?>