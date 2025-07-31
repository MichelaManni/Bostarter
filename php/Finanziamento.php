<?php
session_start();
include "Connessione/InviaFinanziamento.php";
include "Connessione/db.php";

// Verifica che ci sia il progetto nella POST e salvalo in sessione
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nome_progetto'])) {
    $_SESSION['Progetto'] = $_POST['nome_progetto'];
}

if (!isset($_SESSION['Progetto']) || !isset($_SESSION['Email'])) {
    // Se manca qualcosa, rimanda alla visualizzazione progetti
    header("Location: VisualizzaProgetti.php");
    exit();
}

$nomeProgetto = $_SESSION['Progetto'];
$EmailRegistrata = $_SESSION['Email'];
$Prezzo_Inserito = 0;

$budgetProgetto = 0;
$finanziatoAttuale = 0;
//Stored Procedure successive servono per far visualizzare all'utente quanto manca al raggiungimento del budget
// Ottieni il Budget del progetto
$stmtBudget = $mysqli->prepare("CALL OttieneProjectBudget(?)");
if ($stmtBudget) {
    $stmtBudget->bind_param("s", $nomeProgetto);
    $stmtBudget->execute();
    $resultBudget = $stmtBudget->get_result();
    if ($rowBudget = $resultBudget->fetch_assoc()) {
        $budgetProgetto = (float)$rowBudget['Budget'];
    }
    $stmtBudget->close();
    while ($mysqli->more_results() && $mysqli->next_result()) {
        if ($res = $mysqli->store_result()) {
            $res->free();
        }
    }
}

// Ottiene il Totale Finanziato del progetto
$stmtFinanziato = $mysqli->prepare("CALL OttieneProjectTotaleFinanziamenti(?)");
if ($stmtFinanziato) {
    $stmtFinanziato->bind_param("s", $nomeProgetto);
    $stmtFinanziato->execute();
    $resultFinanziato = $stmtFinanziato->get_result();
    if ($rowFinanziato = $resultFinanziato->fetch_assoc()) {
        $finanziatoAttuale = (float)$rowFinanziato['TotaleFinanziato'];
    }
    $stmtFinanziato->close();
    
    while ($mysqli->more_results() && $mysqli->next_result()) {
        if ($res = $mysqli->store_result()) {
            $res->free();
        }
    }
}

$mancanteAlBudget = $budgetProgetto - $finanziatoAttuale;
?>

<!DOCTYPE HTML>
<html>

<head>
    <title><?php echo htmlspecialchars($nomeProgetto) . " / Finanzia"; ?></title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1><?php echo "Stai per finanziare -> " . htmlspecialchars($nomeProgetto); ?></h1>
    <div class="project-summary">
        <p>Budget Totale Progetto: <strong>€ <?php echo number_format($budgetProgetto, 2); ?></strong></p>
        <p>Totale Finanziato ad oggi: <strong>€ <?php echo number_format($finanziatoAttuale, 2); ?></strong></p>
        <?php if ($mancanteAlBudget > 0): ?>
            <p>Mancano: <strong style="color: green;">€ <?php echo number_format($mancanteAlBudget, 2); ?></strong> al raggiungimento del budget!</p>
        <?php else: ?>
            <p><strong>Il budget è stato raggiunto!</strong> (o superato)</p>
        <?php endif; ?>
    </div>
    <hr>
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

                $stmt = $mysqli->prepare("SELECT Codice, Descrizione FROM Rewards WHERE NomeProgetto = ?");;
                if ($stmt) {
                    $stmt->bind_param("s",  $nomeProgetto);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result && $result->num_rows > 0): ?>
                        <form method="POST">
                            <label for="reward">
                                Seleziona una reward per il tuo finanziamento di <?php echo number_format($Prezzo_Inserito, 2); ?>:
                            </label>
                            <select name="reward" id="reward" required>
                                <option value="">-- Seleziona --</option>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <option value="<?php echo (int)$row['Codice']; ?>">
                                        <?php echo htmlspecialchars($row['Descrizione']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                            <input type="hidden" name="importo" value="<?php echo $Prezzo_Inserito; ?>">
                            <input type="hidden" name="nome_progetto" value="<?php echo htmlspecialchars($nomeProgetto); ?>">
                            <input type="hidden" name="email" value="<?php echo htmlspecialchars($EmailRegistrata); ?>">
                            <button type="submit">Invia</button>
                        </form>
                    <?php else: ?>
                        <p class="finanzia-message">Nessuna reward disponibile</p>
                <?php endif;
                }
                ?>
            <?php endif; ?>
        </div>
    </div>

    <a href="VisualizzaProgetti.php"><button>Torna ai progetti</button></a><br>
</body>

</html>