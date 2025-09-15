<?php
session_start();
//*Pagina visibile solo agli amministratori per inserire nuove skill selezionabili dagli utenti
include 'connessione/InviaSkill.php';
include 'connessione/db.php';
if ($_SESSION['Ruolo'] != 'Amministratore') {
}
$result = $mysqli->query("SELECT Competenza FROM Skills");
?>

<!DOCTYPE HTML>
<html>

<head>
	<title>Bostarter/Inserimento Skill</title>
	<link rel="stylesheet" href="style.css">
</head>

<body>
	<div style="display: flex; width: 100%;">
		<!-- Parte a destra contiene il form per aggiungere una skill-->
		<div style="width: 50%;">
			<form method="post">
				<label name="Skill_Inserita">Inserisci una nuova skill:</label>
				<input type="text" name="Skill_Inserita" id="parola" class="input-parola" maxlength="20" required>
				<button type="submit">Invia</button>
			</form>
		</div>
		<!-- Parte a sinistra contiene la lista di tutte le competenze(scrollabile se ce ne sono molte)-->
		<div style="width: 50%;">
			<h3>Lista competenze:</h3>
			<ul class="lista skill" style="max-height: 200px; overflow-y: scroll; padding-right: 10px; background-color: white; margin: 0; padding: 0; list-style: none;">
				<?php while ($row = $result->fetch_assoc()): ?>
					<li style="border-bottom: 1px solid #ddd; padding: 8px 10px;"><?php echo htmlspecialchars($row['Competenza']); ?></li>
				<?php endwhile; ?>
			</ul>
		</div>
	</div>
        <a href=HomePage.php style="align-content: center;"><button>HomePage</button></a><br>
</body>

</html>