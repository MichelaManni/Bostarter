<?php
session_start();
include "Connessione/InviaFinanziamento.php";
include "Connessione/db.php";

//Per avere le info per compilare la procedure di finanziamento
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['nome_progetto'])) {
        $_SESSION['Progetto'] = $_POST['nome_progetto'];
    }

    $nomeProgetto = $_SESSION['Progetto'];
    $EmailRegistrata = $_SESSION['Email'];
} else {
    //Reindirizza alla visualizzazione dei progetti
}
$Prezzo_Inserito = 0;
?>

<!DOCTYPE HTML>
<html>

<head>
    <title><?php echo htmlspecialchars($nomeProgetto) . " / Finanzia"; ?></title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1><?php echo "Stai per finanziare -> " . $nomeProgetto ?></h1>
    <div class="container">
        <!-- Inserimento Importo -->
        <div style="width: 50%;">
            <form method="POST">
                <label for="importo">Inserisci un importo:</label>
                <input type="number" name="importo" id="importo" step="0.01" min="1" required>
                <button type="submit">Conferma</button>
            </form>
        </div>

        <!-- Scelta della Reward tra quelle disponibili -->
        <div style="width: 50%;">
        <?php if (isset($_POST['importo']) && is_numeric($_POST['importo'])): ?>
                <?php
                $Prezzo_Inserito = (float)$_POST['importo'];
                //Selezionare le reward tra quelle disponibili con una query e di conseguenza viene creato il menu a tendina
                $stmt = $mysqli->prepare("SELECT Descrizione FROM Rewards WHERE PrezzoMinimo <= ? AND NomeProgetto = ?");
                if ($stmt) {
                    $stmt->bind_param("ds", $Prezzo_Inserito, $nomeProgetto);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result && $result->num_rows > 0): ?>
                        <form action="invio.php" method="POST">
                            <label for="reward">
                                Reward disponibile per €<?php echo number_format($Prezzo_Inserito, 2); ?>:
                            </label>
                            <select name="reward" id="reward" required>
                                <option value="">-- Seleziona --</option>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <option value="<?php echo htmlspecialchars($row['Descrizione']); ?>">
                                        <?php echo htmlspecialchars($row['Descrizione']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                            <input type="hidden" name="importo" value="<?php echo $Prezzo_Inserito; ?>">
                            <button type="submit">Invia</button>
                        </form>
                    <?php else: ?>
                        <p class="finanzia-message">Nessuna reward disponibile per questo importo.</p>
                <?php endif;
                    $stmt->close();
                }
                ?>
        <?php endif; ?>
        </div>
        <?php $mysqli->close(); ?>
    </div>
            <a href=VisualizzaProgetti.php style="align-content: center;"><button>Torna ai progetti</button></a><br>
</body>

</html>