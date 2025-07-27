<?php
session_start();
include 'connessione/db.php';

$email = $_SESSION['Email'];

// Chiama la stored procedure
$stmt = $mysqli->prepare("CALL VisualizzaProgettiPersonali(?)");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
?>
<!--visualizza i progetti personali-->
<!DOCTYPE html>
<html>
<head>
    <title>Progetti personali</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Bostarter- I tuoi progetti</h2>

<?php if ($result && $result->num_rows > 0): ?>
    <table class="t1">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Descrizione</th>
                <th>Data Inserimento</th>
                <th>Data Limite</th>
                <th>Budget Richiesto</th>
                <th>Tipologia</th>
                <th>Stato</th>
                <th>Gestione</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['Nome']) ?></td>
                    <td><?= htmlspecialchars($row['Descrizione']) ?></td>
                    <td><?= htmlspecialchars($row['DataInserimento']) ?></td>
                    <td><?= htmlspecialchars($row['DataLimite']) ?></td>
                    <td><?= htmlspecialchars($row['Budget']) ?> €</td>
                    <td><?= htmlspecialchars($row['Tipologia']) ?></td>
                    <td><?= htmlspecialchars($row['Stato']) ?></td>
                    <td>
                        <?php if (strtolower($row["Tipologia"]) === 'software'): ?>
                            <form action="GestioneCandidature.php" method="post">
                                <input type="hidden" name="nome_progetto" value="<?= htmlspecialchars($row['Nome']) ?>">
                                <button type="submit">Gestisci Candidature</button>
                            </form>
                        <?php else: ?>
                            <form action="GestioneComponenti.php" method="post">
                                <input type="hidden" name="nome_progetto" value="<?= htmlspecialchars($row['Nome']) ?>">
                                <button type="submit">Controlla Componenti</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Non hai ancora inserito progetti.</p>
<?php endif; ?>

<?php
$stmt->close();
$mysqli->close();
?>

<br>
<a href="HomePage.php"><button>Torna indietro</button></a>

</body>
</html>
