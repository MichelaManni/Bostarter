<?php
session_start();
include 'Connessione/db.php';
include 'Connessione/InviaCommenti.php';
include 'Connessione/VisualizzaCommenti.php'
?>

<!-- Parte per l'invio di un commento -->
<!DOCTYPE HTML>
<html>
<head>
<title><?php echo $nomeProgetto . "/Commenti" ?></title>
	<style>
        body { background-color: powderblue; }
        table { border-collapse: collapse; width: 100%; }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: white;
            color: black;
            padding-top: 12px;
            padding-bottom: 12px;
        }
        button {
            padding: 6px 12px;
            cursor: pointer;
        }
        form { margin: 0; } 
	</style>
</head>
<body>
    <div>
        <h1>Insersci un commento</h1><br>
        <form method="post" action="Commenti.php"><br>
        <textarea id="testo" name="testo" rows="5" cols="60" maxlength="500" required></textarea><br><br>
        <button type="submit">Invia Commento</button>
        </form>
        <br>
        <a href=VisualizzaProgetti.php><button type="submit">Torna ai progetti</button></a><br>
</body>
</html>