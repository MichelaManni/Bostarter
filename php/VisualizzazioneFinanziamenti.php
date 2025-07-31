<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'connessione/VisualizzaFinanziamenti.php';
    $nome_progetto_finanziamenti = $_POST['nome_progetto'];

    $budgetProgetto = 0;
    $finanziatoAttuale = 0;
    //Stored Procedure successive servono per far visualizzare all'utente quanto manca al raggiungimento del budget
    // Ottieni il Budget del progetto
    $stmtBudget = $mysqli->prepare("CALL OttieneProjectBudget(?)");
    if ($stmtBudget) {
        $stmtBudget->bind_param("s", $nome_progetto_finanziamenti);
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
        $stmtFinanziato->bind_param("s", $nome_progetto_finanziamenti);
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
}
?>
<!-- html per visualizzare l'elenco dei finanziamenti fatti fino ad ora-->
<!DOCTYPE html>
<html>
<head>
    <title>Finanziamenti Progetto</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>


<h2>Finanziamenti del progetto: <?= htmlspecialchars($_SESSION['nome_progetto_finanziamenti']) ?></h2>
<div class="project-summary">
    <p>Budget Totale Progetto: <strong>€ <?php echo number_format($budgetProgetto, 2); ?></strong></p>
    <p>Totale Finanziato ad oggi: <strong style="color: green">€ <?php echo number_format($finanziatoAttuale, 2); ?></strong></p>
    <?php if ($mancanteAlBudget > 0): ?>
        <p>Mancano: <strong style="color: red;">€ <?php echo number_format($mancanteAlBudget, 2); ?></strong> al raggiungimento del budget!</p>
    <?php else: ?>
        <p><strong>Il budget è stato raggiunto!</strong> (o superato)</p>
    <?php endif; ?>
</div>
<hr>

<?php if (!empty($elenco_finanziamenti)): ?>
    <table class="t1">
        <thead>
            <tr>
                <th>Email Utente Finanziatore</th>
                <th>Importo</th>
                <th>Data Finanziamento</th>
                <th>Descrizione Reward</th>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = 0; $i < count($elenco_finanziamenti); $i++):
                $finanziamento = $elenco_finanziamenti[$i]; ?> 
                <tr>
                    <td><?= htmlspecialchars($finanziamento["EmailUtente"]) ?></td>
                    <td><?= htmlspecialchars($finanziamento["Importo"]) ?></td>
                    <td><?= htmlspecialchars($finanziamento["DataFinanziamento"]) ?></td>
                    <td>
                        <?php
                        if ($finanziamento["Descrizione"] !== null) {
                            echo htmlspecialchars($finanziamento["Descrizione"]);
                        } else {
                            echo "Nessuna reward";
                        }
                        ?>
                    </td>
                </tr>
            <?php endfor; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Nessun finanziamento effettuato per questo progetto</p><br>
<?php endif; ?>
<br>
<a href="VisualizzaProgettiPersonali.php"><button >Torna indietro</button></a><br>
</body>
</html>
          
