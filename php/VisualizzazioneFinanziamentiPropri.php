<?php
session_start();
include 'connessione/VisualizzaFinanziamentiPropri.php';
$EmailUtente = $_SESSION['Email'];

?>
<!-- html per visualizzare l'elenco dei finanziamenti fatti dall'utente-->
<!DOCTYPE html>
<html>
<head>
    <title>Finanziamenti Propri</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>


<h2>Finanziamenti effettuati</h2>

<?php if (!empty($elenco_finanziamenti)): ?>
    <table class="t1">
        <thead>
            <tr>
                <th>Nome Progetto</th>
                <th>Importo</th>
                <th>Data Finanziamento</th>
                <th>Descrizione Reward</th>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = 0; $i < count($elenco_finanziamenti); $i++):
                $finanziamento = $elenco_finanziamenti[$i]; ?> 
                <tr>
                    <td><?= htmlspecialchars($finanziamento["NomeProgetto"]) ?></td>
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
    <p>Non hai ancora effettuato nessun finanziamento</p><br>
<?php endif; ?>
<br>
<a href="Profilo.php"><button >Torna indietro</button></a><br>
</body>
</html>
          
