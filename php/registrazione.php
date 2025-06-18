<!-- Pagina per la registrazione -->
<?php
	include("connessione/db.php"); 
?>
<!DOCTYPE html>
<html>
<head>
	<title>Registrazione</title>
</head>
<body>
	<form method="post">
		<h2>Registrazione Bostarter</h2>
		Email:<br>
		<input type="email" name="email" required><br>
		Nome:<br>
		<input type="text" name="nome" required><br>
		Cognome:<br>
		<input type="text" name="cognome" required><br>
		Anno di nascita: <br>
		<input type="number" name="anno" min="1900" required><br>
		Luogo di nascita: <br>
		<input type="text" name="luogo" required><br>
		Nickname: <br>
		<input type="text" name="nickname" required><br>
		Password:<br>
		<input type="password" name="password" required><br>
		Ruolo:<br>
		<select name="ruolo" required>
		  <option value="standard">Standard</option>
		  <option value="creatore">Creatore</option>
		  <option value="amministratore">Amministratore</option>
		</select><br>
		<p> <button type="submit" name="submit">Submit</button> </p> <br>
		<p href="index.php"> <button type="" name="back">Torna al login</button> </p> <br>
	</form>
</body>
</html>

<?php
	
	if($_SERVER["REQUEST_METHOD"]=="POST"){
		
		$email = filter_input(INPUT_POST,"email",  FILTER_VALIDATE_EMAIL);
		$nome = filter_input(INPUT_POST,"nome", FILTER_SANITIZE_SPECIAL_CHARS);
		$cognome = filter_input(INPUT_POST,"cognome", FILTER_SANITIZE_SPECIAL_CHARS);
		$anno= filter_input(INPUT_POST,"anno", FILTER_SANITIZE_SPECIAL_CHARS);	
		$luogo = filter_input(INPUT_POST,"luogo", FILTER_SANITIZE_SPECIAL_CHARS);
		$nickname = filter_input(INPUT_POST,"nickname", FILTER_SANITIZE_SPECIAL_CHARS);
		$password = filter_input(INPUT_POST,"password", FILTER_SANITIZE_SPECIAL_CHARS);
		$ruolo = filter_input(INPUT_POST,"ruolo", FILTER_SANITIZE_SPECIAL_CHARS);
		
		
		if (!preg_match('/^\d{4}$/', $anno)) {
					echo "<p>Anno di nascita non valido</p>";
		} else {
			$sql_controlloMail = "SELECT * FROM Utente WHERE Email = '$email'";
			$sql_risultatoMail = $pdo->query($sql_controlloMail);
			if($sql_risultatoMail->rowCount()>0){
				echo "<p>Email già registrata, sceglierne una nuova </p>";
			}else{
				
				$hash = password_hash($password, PASSWORD_DEFAULT);
				$sql = "INSERT INTO Utente(Email, Nome,Cognome,AnnoNascita, LuogoNascita, Nickname,Password,Ruolo)
					VALUES ('$email', '$nome', '$cognome', '$anno', '$luogo', '$nickname', '$hash', '$ruolo')";
				try{
					$pdo->exec($sql);
					echo "<p>Registrazione avvenuta con successo</p>";
				}catch(PDOException $e){
					echo "<p>Errore durante la registrazione " . $e->getMessage() . "</p>";
				}
			}
		}
	}
		
		
?>	
		
