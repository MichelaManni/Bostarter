<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'connessione/VisualizzaReward.php';
    $_SESSION['nome_progetto_reward'] = $_POST['nome_progetto'];
}
?>
<!-- html per visualizzare reward-->
<!DOCTYPE html>
<html>
<head>
    <title>Reward Finanziamenti</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>


<h2>Componenti del progetto: <?= htmlspecialchars($_SESSION['nome_progetto_reward']) ?></h2>

<?php if (!empty($elenco_reward)): ?>
    <table class="t1">
        <thead>
            <tr>
                <th>Codice</th>
                <th>Descrizione</th>
                <th>Prezzo minimo (in euro)</th>
                <th>Foto</th>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = 0; $i < count($elenco_reward); $i++):
                $reward = $elenco_reward[$i]; ?> 
                <tr>
                    <td><?= htmlspecialchars($reward["Codice"]) ?></td>
                    <td><?= htmlspecialchars($reward["Descrizione"]) ?></td>
                    <td><?= htmlspecialchars($reward["PrezzoMinimo"]) ?></td>
                    <td>
                        <?php if (!empty($reward["PercorsoFoto"])): ?> <!--aggiunge foto se presente-->
                            <img src="<?= htmlspecialchars($reward["PercorsoFoto"]) ?>" alt="Foto reward" width="100">
                        <?php else: ?>
                            Nessuna foto
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endfor; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Nessun reward disponibile per questo progetto.</p><br>
<?php endif; ?>
<br>
<a href="VisualizzaProgetti.php"><button >Torna indietro</button></a><br>
</body>
</html>
          
