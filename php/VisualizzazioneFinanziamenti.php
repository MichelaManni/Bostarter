<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'connessione/VisualizzaFinanziamenti.php';
    $_SESSION['nome_progetto_finanziamenti'] = $_POST['nome_progetto'];
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

<?php if (!empty($elenco_finanziamenti)): ?>
    <table class="t1">
        <thead>
            <tr>
                <th>Codice</th>
                <th>Email Utente Finanziatore</th>
                <th>Importo</th>
                <th>Data Finanziamento</th>
                <th>Codice Reward</th>
                <th>Descrizione Reward</th>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = 0; $i < count($elenco_finanziamenti); $i++):
                $finanziamento = $elenco_finanziamenti[$i]; ?> 
                <tr>
                    <td><?= htmlspecialchars($finanziamento["Codice"]) ?></td>
                    <td><?= htmlspecialchars($finanziamento["EmailUtente"]) ?></td>
                    <td><?= htmlspecialchars($finanziamento["Importo"]) ?></td>
                    <td><?= htmlspecialchars($finanziamento["DataFinanziamento"]) ?></td>
                    <td> 
                        <?php
                        if ($finanziamento["CodiceReward"] !== null) {
                            echo htmlspecialchars($finanziamento["CodiceReward"]);
                        } else {
                            echo "Nessuna";
                        }
                        ?>
                    </td>
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
          
