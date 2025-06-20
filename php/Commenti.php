<?php
include 'Connessione/db.php';
include 'Connessione/VisualizzaCommenti.php';
?>

<!-- Parte per l'invio di un commento -->
<!DOCTYPE HTML>
<html>
<head>
<title><?php echo $nomeProgetto . "/Commenti" ?></title>
	<style>
		body {
			background-color: powderblue;
		}
	</style>
</head>
<body>
    <div>
        <h1>Aggiungi un commento</h1>
        <input></input>
        <a><button type="submit">Invia</button></a><br>
    </div>
	<div>
        <br>
        <a href=VisualizzaProgetti.php><button type="submit">Torna ai progetti</button></a><br>
	</div>
</body>

</html>