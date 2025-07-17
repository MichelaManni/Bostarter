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
	 include 'Connessione/registrazioneUtente.php';
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
<title> Registrazione </title>
<style>
	body {
		background-color: powderblue;
		text-align: center;
	}
	label {
		display: block;
		margin-bottom: 15px;
	}
</style>
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
<form action="index.php" method="get" style="position: absolute; top: 20px; left: 20px;">
	<button type="submit">Torna alla Home</button>
</form>
<h2> Registrazione </h2>
<!-- form di registrazione -->
	<form method="post" action = "registrazione.php">
		<label> Email : <input type="email" name="email" required></label><br>
		<label> Nome : <input type="text" name="nome" required></label><br>
		<label> Cognome : <input type="text" name="cognome" required></label><br>
		<label> Anno di nascita : <input type="number" name="anno"  min="1900" max="2025" required></label><br>
		<label> Luogo di nascita : <input type="text" name="luogo" required></label><br>
		<label> Nickname : <input type="text" name="nickname" required></label><br>
		<label> Password : <input type="password" name="password" required></label><br>
		<!-- tendina per selezionare ruolo -->
		<label> Ruolo : 
			<select name= "ruolo" id = "ruolo" onchange="AbilitazioneCodiceSicurezza()" required>
				<option value="standard">Standard</option>
				<option value="creatore">Creatore</option>
				<option value="amministratore">Amministratore</option>
			</select>
		</label><br>
		<!-- div codice di sicurezza, di default nascosto -->
		<div id="codiceSicurezzaDiv" style="display:none;">
			<label>Codice Sicurezza: <input type="number" name="codiceSicurezza" id="codiceSicurezza"></label><br>
		</div>
		<button type="submit"> Submit </button>
</form>
</body>
</html>