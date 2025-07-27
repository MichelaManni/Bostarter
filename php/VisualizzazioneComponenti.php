<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'connessione/VisualizzaComponenti.php';
    $_SESSION['nome_progetto_componenti'] = $_POST['nome_progetto'];
}
?>
<!-- html per visualizzare componenti-->
<!DOCTYPE html>
<html>
<head>
    <title>Componenti Hardware</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>


<h2>Componenti del progetto: <?= htmlspecialchars($_SESSION['nome_progetto_componenti']) ?></h2>

<?php if (!empty($elenco_componenti)): ?>
    <table class="t1">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Descrizione</th>
                <th>Quantità</th>
                <th>Prezzo in euro</th>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = 0; $i < count($elenco_componenti); $i++):
                $componente = $elenco_componenti[$i]; ?> 
                <tr>
                    <td><?= htmlspecialchars($componente["Nome"]) ?></td>
                    <td><?= htmlspecialchars($componente["Descrizione"]) ?></td>
                    <td><?= htmlspecialchars($componente["Quantita"]) ?></td>
                    <td><?= htmlspecialchars($componente["Prezzo"]) ?></td>
                </tr>
            <?php endfor; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Nessuna componente disponibile per questo progetto.</p><br>
<?php endif; ?>
<br>
<a href="HomePage.php"><button >Torna indietro</button></a><br>
</body>
</html>
          
