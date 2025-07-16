<?php
session_start();
//*Pagina per vesere i commenti e le risposte
include 'Connessione/db.php';
include 'Connessione/InviaCommenti.php';
include 'Connessione/VisualizzaCommenti.php'
?>

<!-- Parte per l'invio di un commento -->
<!DOCTYPE HTML>
<html>

<head>
    <title><?php echo $nomeProgetto . "/Commenti" ?></title>
    <link rel="stylesheet" href="style.css">
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