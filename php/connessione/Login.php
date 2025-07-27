<?php
//*Script per usare la stored procedure per autenticarsi
include 'Connessione/db.php';
$errore = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Email_inserita = $_POST['Email_Inserita'] ?? '';
    $Password_inserita = $_POST['Password_Inserita'] ?? '';
    //Usa la stored procedure autenticazione ottenere il ruolo serve per reindirizzare ad un ulteriore
	//autenticazione nel caso sia admin
    	$stmt = $mysqli->prepare("CALL Autenticazione(?, ?, @Esito, @RuoloRegistrato)");
        $stmt->bind_param("ss", $Email_inserita, $Password_inserita);
        $stmt->execute();
        $stmt->close();
        $result = $mysqli->query("SELECT @Esito AS esito, @RuoloRegistrato AS Ruolo");
        $row = $result->fetch_assoc();

        if ($row['esito']) {
			$_SESSION['Email'] = $Email_inserita;
			$_SESSION['Ruolo'] = $row['Ruolo'];
            if (strtolower($row['Ruolo']) === 'amministratore') {
                header("Location: AdminLogin.php");
            } 
            //Per capire se è davvero un creatore
            else if (strtolower($row['Ruolo']) === 'creatore') {
                $stmt = $mysqli->prepare("CALL GetIdCreatore(?, @IdCreatore)");
                $stmt->bind_param("s", $Email_inserita);
                $stmt->execute();
                $stmt->close();
                $result = $mysqli->query("SELECT @IdCreatore AS IdCreatore");
                $row = $result->fetch_assoc();
                if ($row && $row['IdCreatore'] !== null) {
                    $_SESSION['Creatore'] = $row['IdCreatore'];
                    header("Location: HomePage.php");
                } else {
                    header("Location: index.php");
                }
            } 
            else {
                header("Location: HomePage.php");
            }
            exit();}	
		else {$errore = "Email o password errati.";}
}
$mysqli->close();
?>