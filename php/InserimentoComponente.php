<?php
session_start();
$nome_progetto = $_SESSION['nome_progetto'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'connessione/InserisciComponente.php';
}
?>
<!--HTML PER INSERIMENTO COMPONENTE AL NUOVO PROGETTO-->
<!DOCTYPE html>
<head>
    <title>Inserisci Componente Progetto</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
	<!-- Pulsante back -->   
    <a href="AggiuntaContenutiNuovoProgetto.php"><button class="ButtonBack">Torna indietro</button></a>
    <h1>Inserisci componente per il nuovo progetto</h1>
    <form method="POST" action="InserimentoComponente.php">
        <p>Nome del Progetto:</p>
            <input type="text" name="nome_progetto" value="<?php echo htmlspecialchars($nome_progetto); ?>" readonly required>
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
