<?php
session_start();
include 'connessione/db.php';

if (!isset($_SESSION['nome_progetto']) || !isset($_SESSION['nome_profilo']) || !isset($_SESSION['id_profilo_corrente'])) {
    header("Location: AggiuntaContenutiNuovoProgetto.php"); 
    exit();
}

$nome_progetto = $_SESSION['nome_progetto'];
$nome_profilo = $_SESSION['nome_profilo'];
$id_profilo = $_SESSION['id_profilo_corrente'];

$query = "SELECT Competenza FROM Skills";
$result = $mysqli->query($query);

if (!$result) {
    die("Errore durante il recupero delle skill: " . $mysqli->error);
}

$stmt_check_skills = $mysqli->prepare("SELECT COUNT(*) FROM SkillRichieste WHERE IdProfilo = ?");
$stmt_check_skills->bind_param("i", $id_profilo);
$stmt_check_skills->execute();
$stmt_check_skills->bind_result($count_skills);
$stmt_check_skills->fetch();
$stmt_check_skills->close();

$almenoUnaSkillAggiunta = ($count_skills > 0); //tutto questo controllo poichè un profilo software deve avere almeno una skill richiesta altrimenti non ha senso

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'connessione/InserisciSkillProfilo.php';
}
?>
<!--HTML per inserire skill a profilo software-->
<!DOCTYPE html>
<head>
    <title>Inserisci Skill Profilo</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php if ($almenoUnaSkillAggiunta): ?>
        <a href="AggiuntaContenutiNuovoProgetto.php"><button class="ButtonBack">Torna indietro</button></a>
    <?php else: ?>
        <p class="error-message">Devi aggiungere almeno una skill a questo profilo prima di poter tornare indietro</p>
    <?php endif; ?>

    <h1>Inserisci Skill per il profilo <?php echo htmlspecialchars($nome_profilo); ?></h1>
    <p>Profilo aggiunto correttamente! E' necessario inserire almeno una skill richiesta per questo ruolo.</p>
    
    <?php 
    if (isset($_SESSION['error_message'])) {
        echo "<p class='error-message'>" . htmlspecialchars($_SESSION['error_message']) . "</p>";
        unset($_SESSION['error_message']); 
    }
    ?>
    <form method="POST" action="InserimentoSkillProfilo.php">
        <p>Nome del Progetto:</p>
        <input type="text" name="nome_progetto_display" value="<?php echo htmlspecialchars($nome_progetto); ?>" readonly>
        <p>Nome profilo:</p>
        <input type="text" name="nome_profilo_display" value="<?php echo htmlspecialchars($nome_profilo); ?>" readonly>
        
        <p>Skill da aggiungere:</p>
        <select name="competenza" id="competenza" required>
            <?php while($row = $result->fetch_assoc()): ?>
                <option value="<?= htmlspecialchars($row['Competenza']) ?>">
                    <?= htmlspecialchars($row['Competenza']) ?>
                </option>
            <?php endwhile; ?>
        </select>
        <p>Livello della skill richiesto (0-5): </p>
        <input type="number" name="livello" min="0" max="5" value="0"> <br>
        <button type="submit">Aggiungi Skill</button>
    </form>

    <?php if ($almenoUnaSkillAggiunta): ?>
        
        <p>Hai aggiunto almeno una skill. Ora puoi tornare indietro per aggiungere altri contenuti al progetto</p>
           
    <?php endif; ?>

</body>
</html>