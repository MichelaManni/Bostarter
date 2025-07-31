<?php
session_start();
$nome_progetto = $_SESSION['nome_progetto'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'connessione/InserisciComponente.php';
    // Il reindirizzamento avviene all'interno di InserisciComponente.php
}
?>
<!--HTML per inserimento nuova componente progetto hardware-->
<!DOCTYPE html>
<head>
    <title>Inserisci Componente Progetto</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form method="POST" action="InserimentoComponente.php">
        <h1>Inserisci componente per il nuovo progetto <?php echo htmlspecialchars($nome_progetto); ?></h1>
        <?php 
        if (isset($_SESSION['error_message'])) {
            echo "<p class='error-message'>" . htmlspecialchars($_SESSION['error_message']) . "</p>"; //errore 
            unset($_SESSION['error_message']); 
        }
        ?>
        <p>Nome del Progetto:</p>
        <input type="text" name="nome_progetto_display" value="<?php echo htmlspecialchars($nome_progetto); ?>" readonly required>
        <p>Nome componente:</p>
        <input type="text" name="nome" required>
        <p>Descrizione:</p>
        <textarea name="descrizione" rows="3" required></textarea>
        <p>Quantità:</p>
        <input type="number" name="quantita" min="1" required>
        <p>Prezzo:</p>
        <input type="number" name="prezzo" step="0.01" min="0" required> <br>
        <button type="submit">Aggiungi componente</button>
    </form>
</body>
</html>