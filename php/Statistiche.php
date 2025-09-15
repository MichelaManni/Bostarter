<?php
//*Pagina per vedere le statistiche
include 'connessione/db.php';
// Chiama le view
$affidabili = $mysqli->query("SELECT Nickname FROM ClassificaAffidabili")->fetch_all(MYSQLI_ASSOC);
$progetti = $mysqli->query("SELECT Nome, Descrizione, Budget, Differenza FROM ProgettiQuasiCompletati")->fetch_all(MYSQLI_ASSOC);
$finanziatori = $mysqli->query("SELECT Nickname FROM ClassificaUtenti")->fetch_all(MYSQLI_ASSOC);
$mysqli->close();
?>

<!-- Usa i dati estratti dalle 3 view per creare dinamicamente la pagina -->
<!DOCTYPE html>
<html>

<head>
    <title>Bostarter /Statistiche</title>
    <link rel="stylesheet" href="style.css">
    <style>
        td {padding: 5px;text-align: center;border-bottom: 3px solid black;}
    </style>
</head>
<!-- 3 creatori più affidabili -->
<div class="Classifica">
    <div class="titolo">
        <h2>Top 3 Creatori più Affidabili</h2> <a href="HomePage.php"><button>Homepage</button></a>
    </div>
    <table>
        <thead>
            <tr>
                <th>Posizione</th>
                <th>Nickname</th>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = 0; $i < 3; $i++): //Ogni sezione viene costruita con un for(3)
            ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= htmlspecialchars($affidabili[$i]['Nickname'] ?? 'N/A') ?></td>
                </tr>
            <?php endfor; ?>
        </tbody>
    </table>
</div>
<!-- 3 Progetti più vicini al completamento, non espressamente richieste le info aggiuntive al contrario
 delle altre dove la traccia richiede espressamente solo i nickname -->
<div class="Classifica">
    <h2 >3 Progetti più Vicini al Completamento</h2>
    <table>
        <thead>
            <tr>
                <th>Posizione</th>
                <th>Nome</th>
                <th>Descrizione</th>
                <th>Budget</th>
                <th>Da Raccogliere</th>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = 0; $i < 3; $i++): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= htmlspecialchars($progetti[$i]['Nome'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($progetti[$i]['Descrizione'] ?? 'N/A') ?></td>
                    <td><?= number_format($progetti[$i]['Budget'] ?? 0, 2, ',', '.') ?> €</td>
                    <td><?= number_format($progetti[$i]['Differenza'] ?? 0, 2, ',', '.') ?> €</td>
                </tr>
            <?php endfor; ?>
        </tbody>
    </table>
</div>
<!-- 3 Utenti con più finanziamenti -->
<div class="Classifica">
    <h2>3 Utenti con più Finanziamenti</h2>
    <table>
        <thead>
            <tr>
                <th>Posizione</th>
                <th>Nickname</th>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = 0; $i < 3; $i++): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= htmlspecialchars($finanziatori[$i]['Nickname'] ?? 'N/A') ?></td>
                </tr>
            <?php endfor; ?>
        </tbody>
    </table>
</div>
</body>
</html>