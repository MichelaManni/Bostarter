<?php
session_start();
include 'connessione/db.php'; 
$nome_progetto_corrente = ''; // Inizializza la variabile

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nome_progetto'])) {
    $nome_progetto_corrente = $_POST['nome_progetto'];
    $_SESSION['nome_progetto_componenti'] = $nome_progetto_corrente; // Aggiorna la sessione con il nuovo progetto
}else if (isset($_SESSION['nome_progetto_componenti'])) {
    $nome_progetto_corrente = $_SESSION['nome_progetto_componenti'];
} 


$elenco_componenti = []; // Inizializza l'array dei componenti

if (!empty($nome_progetto_corrente)) {
    $stmt = $mysqli->prepare("CALL VisualizzaComponenti(?)");
    if ($stmt) {
        $stmt->bind_param("s", $nome_progetto_corrente);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $elenco_componenti[] = $row;
            }
        } else {
            // Gestione errori esecuzione query
            error_log("Errore esecuzione VisualizzaComponenti: " . $stmt->error);
        }
        $stmt->close();
    } else {
        // Gestione errori prepare statement
        error_log("Errore prepare VisualizzaComponenti: " . $mysqli->error);
    }

    // Pulisce i risultati pendenti dalla stored procedure
    while ($mysqli->more_results() && $mysqli->next_result()) {
        if ($res = $mysqli->store_result()) {
            $res->free();
        }
    }
}
?>
<!--HMTL x visualizzare componenti-->
<!DOCTYPE html>
<html>
<head>
    <title>Componenti Hardware</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Componenti del progetto: <?= htmlspecialchars($nome_progetto_corrente) ?></h2>

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