<?php
session_start();
//*Pagina per vedere e inviare commenti e risposte
include 'connessione/db.php';
include 'Commenti/InviaCommenti.php';
include 'Commenti/VisualizzaCommenti.php';
include 'Commenti/InviaRisposta.php';
?>

<?php
if (isset($_POST['nome_progetto'])) {
    $_SESSION['Progetto'] = $_POST['nome_progetto'];
}
$Visualizzatore = new VisualizzatoreCommenti($mysqli);
$Visualizzatore->CreaTabellaCommenti();
?>

<!DOCTYPE HTML>
<html>

<head>
    <link rel="stylesheet" href="style.css">
    <title><?php echo  $_SESSION['Progetto'] . "/Commenti" ?></title>
</head>

<body>
    <form method="post" action="PaginaCommenti.php" class="container" style="display: flex; gap: 10px; align-items: center;">
        <label>Inserisci un commento:</label>
        <textarea id="testo" name="testo" rows="3" cols="50" maxlength="500" required></textarea>
        <button type="submit">Invia</button>
    </form>

    <div class="container">
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['CodiceCommento'])) {
            $CodiceCommento = $_POST['CodiceCommento'];
            echo '
            <form method="post" class="container" style="display: flex; gap: 10px; align-items: center;">
            <label>Inserisci risposta</label>
            <textarea id="Risposta" name="Risposta" rows="3" cols="50" maxlength="500" required></textarea>
            <input type="hidden" name="CodiceCommento" value="' . htmlspecialchars($CodiceCommento) . '">
            <button type="submit">Invia</button>
            </form>';
        } ?>
    </div>
    <a href=PaginaVisualizzaProgetti.php><button type="submit">Torna ai progetti</button></a><br>
</body>

</html>