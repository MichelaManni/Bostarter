<?php
//Gestisce HTML della registrazione, la validazione di base e chiama la connessione
session_start();//avvia sessione

if($_SERVER["REQUEST_METHOD"]=="POST"){
	//dati ricevuti dal form che vengono validati e sanificati 
	$email = filter_input(INPUT_POST,'email',FILTER_VALIDATE_EMAIL);
	$nome = filter_input(INPUT_POST,'nome',FILTER_SANITIZE_SPECIAL_CHARS); //rimuove caratteri dannosi
	$cognome = filter_input(INPUT_POST,'cognome',FILTER_SANITIZE_SPECIAL_CHARS);
	$anno = filter_input(INPUT_POST,'anno',FILTER_SANITIZE_NUMBER_INT);
	$luogo = filter_input(INPUT_POST,'luogo',FILTER_SANITIZE_SPECIAL_CHARS);
	$nickname = filter_input(INPUT_POST,'nickname',FILTER_SANITIZE_SPECIAL_CHARS);
	$password =  $_POST['password'] ?? '';
	$ruolo = filter_input(INPUT_POST, 'ruolo', FILTER_SANITIZE_SPECIAL_CHARS);
    $codiceSicurezza = $_POST['codiceSicurezza'] ?? null; //solo per gli amministratori

	if (!preg_match('/^\d{4}$/', $anno)) { //anno composto da 4 cifre
        echo "<p>Anno di nascita non valido.</p>";
        exit;
    }
	if($ruolo==='amministratore' && (empty($codiceSicurezza))){
		echo "<p> Per gli amministratori il codice di sicurezza è obbligatotio </p>";
		exit;
	}
	 include 'connessione/registrazioneUtente.php';
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
	<title> Registrazione </title>
	<link rel="stylesheet" href="style.css">
<!--per mostrare o nascondere il campo codice di sicurezza in base al ruolo scelto -->
<script>
function AbilitazioneCodiceSicurezza(){
	const ruolo= document.getElementById('ruolo').value; //ruolo selezionato
	const codiceDiv = document.getElementById('codiceSicurezzaDiv'); //div con codice sicurezza
	if(ruolo === "amministratore"){
		codiceDiv.style.display = 'block'; //mostra campo
    } else {
        codiceDiv.style.display = 'none'; //nasconde campo
        document.getElementById('codiceSicurezza').value = ''; //pulisce valore
    }
}
window.onload = function() { //quando pagina è caricata esegue la funzione
    AbilitazioneCodiceSicurezza();
}
</script>
</head>
<body>
	<!-- Pulsante back -->
	<a href="index.php"><button class="ButtonBack">Torna al Login</button></a>
	<h1> Bostarter-Registrazione </h1>
	<!-- form di registrazione -->
		<form method="post" action = "registrazione.php">
			<p> Email : </p><input type="email" name="email" required><br>
			<p> Nome : </p><input type="text" name="nome" required><br>
			<p> Cognome :</p> <input type="text" name="cognome" required><br>
			<p> Anno di nascita :</p> <input type="number" name="anno"  min="1900" max="2025" required><br>
			<p> Luogo di nascita : </p><input type="text" name="luogo" required><br>
			<p> Nickname : </p><input type="text" name="nickname" required><br>
			<p> Password : </p><input type="password" name="password" required><br>
			<!-- tendina per selezionare ruolo -->
			<p> Ruolo : </p>
				<select name= "ruolo" id = "ruolo" onchange="AbilitazioneCodiceSicurezza()" required>
					<option value="standard">Standard</option>
					<option value="creatore">Creatore</option>
					<option value="amministratore">Amministratore</option>
				</select>
			<br>
			<!-- div codice di sicurezza, di default nascosto -->
			<div id="codiceSicurezzaDiv" style="display:none;">
				<p>Codice Sicurezza:</p> <input type="number" name="codiceSicurezza" id="codiceSicurezza"><br>
			</div>
			<button type="submit"> Submit </button>
		</form>
</body>
</html>