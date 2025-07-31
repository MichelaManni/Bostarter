<?php
session_start();
$nome_progetto = $_SESSION['nome_progetto'];

if($_SERVER["REQUEST_METHOD"]=="POST"){
    include 'connessione/InserisciReward.php';
}
?>
<!--HTML per inserimento nuova reward-->
<!DOCTYPE html>
<head>
    <title>Inserimento Reward</title> 
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form action="InserimentoReward.php" method="POST" enctype="multipart/form-data" >
        <h1>Inserisci nuova reward</h1>
        <?php 
        if (isset($_SESSION['error_message'])) {
            echo "<p class='error-message'>" . htmlspecialchars($_SESSION['error_message']) . "</p>"; //mostra messaggio di errore se presente
            unset($_SESSION['error_message']); // Rimuovi il messaggio dopo averlo mostrato
        }
        ?>
        <p>Nome Progetto:</p>
        <input type="text" name="nome_progetto_display" value="<?php echo htmlspecialchars($nome_progetto); ?>" readonly required>
        <p>Descrizione:</p>
        <textarea name="descrizione" required></textarea>
        <p>Carica una foto del reward:</p>
        <input type="file" name="foto" accept=".jpg,.jpeg,.png" required> <br>
        <button type="submit">Aggiungi Reward</button>
    </form>
</body>
</html>