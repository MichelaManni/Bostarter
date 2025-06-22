

<?php
include 'Connessione/db.php';

// Da rivedere questa parte !!!!!!
function eseguiQuery($mysqli, $sql) {
    if (!$result = $mysqli->query($sql)) {
        echo "Errore nella query: " . $mysqli->error;
        return [];}
    $rows = $result->fetch_all(MYSQLI_ASSOC);
    $result->free();
    return $rows;
}

// Chiama le view
$sezioni = [
    'Top 3 Creatori più Affidabili' => 'SELECT * FROM ClassificaAffidabili',
    '3 Progetti più Vicini al Completamento' => 'SELECT * FROM ProgettiQuasiCompletati',
    '3 Utenti con più Finanziamenti' => "SELECT * FROM ClassificaUtenti"
];
// Recupero dati per tutte le sezioni
$risultati = [];
foreach ($sezioni as $titolo => $query) {
    $risultati[$titolo] = eseguiQuery($mysqli, $query);
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Bostarter /Statistiche</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: powderblue;}
        .section { padding: 20px; border-bottom: 5px solid black;}
        table { width: 100%; border-collapse: collapse;}
        th, td { padding: 8px; text-align: left; border-bottom: 5px solid black;}
    </style>
</head>
<body>
    <?php foreach ($risultati as $titolo => $rows): ?>
            <h2><?= htmlspecialchars($titolo) ?></h2>
            <?php if (!empty($rows)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Posizione</th>
                            <?php foreach (array_keys($rows[0]) as $colonna): ?>
                                <th><?= htmlspecialchars($colonna) ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rows as $i => $row): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <?php foreach ($row as $valore): ?>
                                    <td>
                                        <?= is_numeric($valore) 
                                            ? number_format($valore, 2, ',', '.') . ' €' 
                                            : htmlspecialchars($valore) ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Nessun dato disponibile.</p>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</body>
</html>