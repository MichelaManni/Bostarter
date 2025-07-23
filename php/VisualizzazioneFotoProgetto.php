<?php
session_start(); 
include 'connessione/db.php'; 

// Controlla che sia stato inviato nome progetto da post 
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['nome_progetto'])) {
    $_SESSION['nome_progetto_foto'] = $_POST['nome_progetto']; // Salva il nome del progetto nella sessione
    include 'connessione/VisualizzaFotoProgetto.php'; 
}
?>
<!--Frontend foto progetto-->
<!DOCTYPE html>
<html>
<head>
    <title>Foto Progetto</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Titolo con nome progetto -->
    <h2>Foto del progetto: <?= htmlspecialchars($_SESSION['nome_progetto_foto']) ?></h2>

    <!--Mostra tutte le foto-->
    <?php if (isset($foto_paths) && count($foto_paths) > 0): ?>
        <?php for ($i = 0; $i < count($foto_paths); $i++): ?>
            <div style="margin: 10px;">
                <img src="<?= htmlspecialchars($foto_paths[$i]) ?>" alt="Foto progetto" width="300">
            </div>
        <?php endfor; ?>
    <?php else: ?>
        <!-- Nel caso non ci siano foto -->
        <p>Nessuna foto trovata per questo progetto.</p>
    <?php endif; ?>
    <br>
    <a href="VisualizzaProgetti.php"><button>Torna alla lista progetti</button></a>
</body>
</html>
