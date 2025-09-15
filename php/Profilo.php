<?php
session_start();
//Pagina per visualizzare il proprio profilo e dunque le proprie skill e inserirne di nuove
include "connessione/db.php";
include "connessione/InviaSkillCurriculum.php";
$EmailDB = $_SESSION['Email'];
?>

<!DOCTYPE HTML>
<html>

<head>
    <link rel="stylesheet" href="style.css">
    <title>Profilo Personale</title>
</head>

<body>
    <div class="container">
        <!-- dezione che mostra le informazioni base del profilo -->
        <div style="width:30%;text-align:left">
            <h1>Informazioni personali</h1>
            <?php
            $Query  = "SELECT Nome, Cognome, AnnoNascita, LuogoNascita, Nickname, Ruolo
                 FROM Utente
                 WHERE Email = '$EmailDB' LIMIT 1;";
            $result = mysqli_query($mysqli_real, $Query);

            if ($result && mysqli_num_rows($result) > 0) {
                $row   = mysqli_fetch_assoc($result);
                $email = htmlspecialchars($EmailDB, ENT_QUOTES, 'UTF-8');
                $nome  = htmlspecialchars($row['Nome']         ?? '', ENT_QUOTES, 'UTF-8');
                $cogn  = htmlspecialchars($row['Cognome']      ?? '', ENT_QUOTES, 'UTF-8');
                $anno  = htmlspecialchars($row['AnnoNascita']  ?? '', ENT_QUOTES, 'UTF-8');
                $luogo = htmlspecialchars($row['LuogoNascita'] ?? '', ENT_QUOTES, 'UTF-8');
                $nick  = htmlspecialchars($row['Nickname']     ?? '', ENT_QUOTES, 'UTF-8');
                $ruolo = htmlspecialchars($row['Ruolo']        ?? '', ENT_QUOTES, 'UTF-8');

                echo "<p>Email: {$email}</p>";
                echo "<p>Nome: {$nome}</p>";
                echo "<p>Cognome: {$cogn}</p>";
                echo "<p>Anno di nascita: {$anno}</p>";
                echo "<p>Luogo di nascita: {$luogo}</p>";
                echo "<p>Nickname: {$nick}</p>";
                echo "<p>Ruolo: {$ruolo}</p>";

                echo "<a href='VisualizzazioneFinanziamentiPropri.php'><button>Visualizza Finanziamenti Fatti</button></a> ";
                echo "<a href='VisualizzazioneCandidatureEffettuate.php'><button>Visualizza Esito Candidature Effettuate</button></a>";
            } else {
                echo "<p>Profilo non trovato.</p>";
            }
            ?>
        </div>

        <!-- Mostrare le skills proprie -->
        <div style="width:30%">
            <h1>Competenze di curriculum</h1>
            <?php
            $stmt = $mysqli->prepare("CALL VisualizzaSkillsUtente(?)");
            $stmt->bind_param("s",  $EmailDB);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            ?>
            <ul class="lista-skill" style="max-height: 200px; overflow-y: scroll; padding-right: 10px; background-color: white; margin: 0; padding: 0; list-style: none;">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <li style="border-bottom: 1px solid #ddd; padding: 8px 10px;">
                        <?php echo htmlspecialchars($row['CompetenzaUtente']) . ' – Livello: ' . htmlspecialchars($row['Livello']); ?>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>

        <!-- Scegliere nuove skill per inserirle -->
        <div style="width: 30%;">
            <h1>Aggiungi una competenza</h1>
            <div class="container">
                <?php
                $stmt = $mysqli->prepare("SELECT Competenza FROM Skills");
                $stmt->execute();
                $result = $stmt->get_result();
                ?>
                <?php if ($result && $result->num_rows > 0): ?>

                    <form method="POST">
                        <label for="Competenza">Competenza:</label>
                        <select name="Competenza" id="Competenza" required>
                            <option value="">-- Seleziona --</option>
                            <!-- Menu a tendina per scegliere la skill = a quello delle reward nel finanziamento -->
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <option value="<?php echo htmlspecialchars($row['Competenza'], ENT_QUOTES); ?>">
                                    <?php echo htmlspecialchars($row['Competenza']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>

                        <!-- Per scegliere il livello della competenza -->
                        <select name="Livello" id="Livello" required>
                            <option value="">-- 1 a 5 --</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>

                        <button type="submit">Invia</button>
                    </form>
                <?php else: ?>
                    <p>Nessuna competenza disponibile.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <br>
    <a href="HomePage.php"><button>Homepage</button></a>
</body>

</html>