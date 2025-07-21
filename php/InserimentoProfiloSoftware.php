<?php
session_start();
$nome_progetto = $_SESSION['nome_progetto'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'connessione/InserisciProfiloSoftware.php';
}
?>
<!--HTML PER INSERIMENTO PROFILO AL NUOVO PROGETTO-->
<!DOCTYPE html>
<head>
    <title>Inserisci Profilo Progetto</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
	<!-- Pulsante back -->   
    <a href="AggiuntaContenutiNuovoProgetto.php"><button class="ButtonBack">Torna indietro</button></a>
    <h1>Inserisci profilo software per il nuovo progetto</h1>
    <form method="POST" action="InserimentoProfiloSoftware.php">
        <p>Nome del Progetto:</p>
            <input type="text" name="nome_progetto" value="<?php echo htmlspecialchars($nome_progetto); ?>" readonly required>
        <p>Nome profilo:</p>
            <input type="text" name="nome_profilo" required><br>
        <button type="submit">Aggiungi profilo</button>
    </form>
</body>
</html>
