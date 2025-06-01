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
		email:<br>
		<input type="email" name="email" required><br>
		nome:<br>
		<input type="text" name="nome" required><br>
		cognome:<br>
		<input type="text" name="cognome" required><br>
		anno di nascita: <br>
		<input type="number" name="anno" required><br>
		luogo di nascita: <br>
		<input type="text" name="luogo" required><br>
		nickname: <br>
		<input type="nickname" name="nickname" required><br>
		password:<br>
		<input type="password" name="password" required><br>
		ruolo:<br>
		<select name="ruolo" required>
		  <option value="standard">Standard</option>
		  <option value="creatore">Creatore</option>
		  <option value="amministratore">Amministratore</option>
		</select><br>
		<input type="submit" name="submit" value="register"><br>
	</form>
</body>
</html>
<?php
	
	if($_SERVER["REQUEST_METHOD"]=="POST"){
		
		$email = filter_input(INPUT_POST,"email", FILTER_SANITIZE_SPECIAL_CHARS);
		$nome = filter_input(INPUT_POST,"nome", FILTER_SANITIZE_SPECIAL_CHARS);
		$cognome = filter_input(INPUT_POST,"cognome", FILTER_SANITIZE_SPECIAL_CHARS);
		$anno= filter_input(INPUT_POST,"anno", FILTER_SANITIZE_SPECIAL_CHARS);	
		$luogo = filter_input(INPUT_POST,"luogo", FILTER_SANITIZE_SPECIAL_CHARS);
		$nickname = filter_input(INPUT_POST,"nickname", FILTER_SANITIZE_SPECIAL_CHARS);
		$password = filter_input(INPUT_POST,"password", FILTER_SANITIZE_SPECIAL_CHARS);
		$ruolo = filter_input(INPUT_POST,"ruolo", FILTER_SANITIZE_SPECIAL_CHARS);
		
		$hash = password_hash($password, PASSWORD_DEFAULT);
		$sql = "INSERT INTO Utente(Email, Nome,Cognome,AnnoNascita, LuogoNascita, Nickname,Password,Ruolo)
				VALUES ('$email', '$nome', '$cognome', '$anno', '$luogo', '$nickname', '$hash', '$ruolo')";
		mysqli_query($conn,$sql);
		echo("Registrazione completata!");
	}

?>	
		
