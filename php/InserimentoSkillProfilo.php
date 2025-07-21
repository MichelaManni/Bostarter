<?php
session_start();
include 'connessione/db.php';
$nome_progetto = $_SESSION['nome_progetto'];
$nome_profilo = $_SESSION['nome_profilo'];
$id_profilo = $_SESSION['id_profilo_corrente'];

// Recupera tutte le skill esistenti dalla tabella Skills
$query = "SELECT Competenza FROM Skills";
$result = $mysqli->query($query);

if (!$result) {
    die("Errore durante il recupero delle skill: " . $mysqli->error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'connessione/InserisciSkillProfilo.php';
}
?>
<!--HTML PER INSERIMENTO SKILL A NUOVO PROFILO-->
<!DOCTYPE html>
<head>
    <title>Inserisci Profilo Progetto</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
	<!-- Pulsante back -->
    <a href="AggiuntaContenutiNuovoProgetto.php"><button class="ButtonBack">Torna indietro</button></a>
    <h1>Inserisci Skill per il profilo</h1>
    <form method="POST" action="InserimentoSkillProfilo.php">
        <p> Profilo aggiunto correttamente! Ora è possibile selezionare una per volta le skill richieste corrispondenti </p>
        <p>Nome del Progetto:</p>
            <input type="text" name="nome_progetto" value="<?php echo htmlspecialchars($nome_progetto); ?>" readonly required>
        <p>Nome profilo:</p>
            <input type="text" name="nome_profilo" value="<?php echo htmlspecialchars($nome_profilo); ?>" readonly required>
        <p> Skill da aggiungere: </p>
            <select name="competenza" id="competenza" required>
            <?php while($row = $result->fetch_assoc()): ?> <!-- Skill vengono scelte dall'elenco di skill presenti sulla piattaforma-->
                <option value="<?= htmlspecialchars($row['Competenza']) ?>">
                    <?= htmlspecialchars($row['Competenza']) ?>
                </option>
            <?php endwhile; ?>
            </select>
        <p>Livello della skill richiesto (0-5): </p>
            <input type="number" name="livello" min="0" max="5"> <br>
        <button type="submit">Aggiungi Skill</button>
    </form>
</body>
</html>