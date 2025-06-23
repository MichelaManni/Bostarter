<?php

session_start();

if($_SERVER["REQUEST_METHOD"]=="POST"){

	$email = filter_input(INPUT_POST,'email',FILTER_VALIDATE_EMAIL);
	$nome = filter_input(INPUT_POST,'nome',FILTER_SANITIZE_SPECIAL_CHARS);
	$cognome = filter_input(INPUT_POST,'cognome',FILTER_SANITIZE_SPECIAL_CHARS);
	$anno = filter_input(INPUT_POST,'anno',FILTER_SANITIZE_NUMBER_INT);
	$luogo = filter_input(INPUT_POST,'luogo',FILTER_SANITIZE_SPECIAL_CHARS);
	$nickname = filter_input(INPUT_POST,'nickname',FILTER_SANITIZE_SPECIAL_CHARS);
	$password =  $_POST['password'] ?? '';
	$ruolo = filter_input(INPUT_POST, 'ruolo', FILTER_SANITIZE_SPECIAL_CHARS);
    $codiceSicurezza = $_POST['codiceSicurezza'] ?? null;

	//controlli
	if(!$email){
		echo "<p> email non valida </p>";
		exit;
	}
	if (!preg_match('/^\d{4}$/', $anno)) {
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
<script>
function AbilitazioneCodiceSicurezza(){
	const ruolo= document.getElementById('ruolo').value;
	const codiceDiv = document.getElementById('codiceSicurezzaDiv');
	if(ruolo === "amministratore"){
		codiceDiv.style.display = 'block';
    } else {
        codiceDiv.style.display = 'none';
        document.getElementById('codiceSicurezza').value = '';
    }
}
window.onload = function() {
    AbilitazioneCodiceSicurezza();
}
</script>
</head>
<body>
<form action="index.php" method="get" style="position: absolute; top: 20px; left: 20px;">
	<button type="submit">Torna alla Home</button>
</form>
<h2> Registrazione </h2>
	
	<form method="post" action = "registrazione.php">
		<label> Email : <input type="email" name="email" required></label><br>
		<label> Nome : <input type="text" name="nome" required></label><br>
		<label> Cognome : <input type="text" name="cognome" required></label><br>
		<label> Anno di nascita : <input type="number" name="anno"  min="1900" max="2025" required></label><br>
		<label> Luogo di nascita : <input type="text" name="luogo" required></label><br>
		<label> Nickname : <input type="text" name="nickname" required></label><br>
		<label> Password : <input type="password" name="password" required></label><br>
		<label> Ruolo : 
			<select name= "ruolo" id = "ruolo" onchange="AbilitazioneCodiceSicurezza()" required>
				<option value="standard">Standard</option>
				<option value="creatore">Creatore</option>
				<option value="amministratore">Amministratore</option>
			</select>
		</label><br>
		<div id="codiceSicurezzaDiv" style="display:none;">
			<label>Codice Sicurezza: <input type="number" name="codiceSicurezza" id="codiceSicurezza"></label><br>
		</div>
		<button type="submit"> Submit </button>
</form>
</body>
</html>