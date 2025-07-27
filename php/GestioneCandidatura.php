<?php
session_start();
include 'connessione/db.php';

$email_creatore = $_SESSION['Email'];

// Chiama la stored procedure per visualizzare le candidature
$stmt = $mysqli->prepare("CALL VisualizzaCandidatureProgettiPersonali(?)");
$stmt->bind_param("s", $email_creatore);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Candidature Ricevute</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Candidature ricevute per i tuoi progetti software</h2>

<?php if ($result && $result->num_rows > 0): ?>
    <table class="t1">
        <thead>
            <tr>
                <th>Nome Progetto</th>
                <th>Nome Profilo</th>
                <th>Email Candidato</th>
                <th>Stato</th>
                <th>Gestione</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['NomeProgetto']) ?></td>
                    <td><?= htmlspecialchars($row['NomeProfilo']) ?></td>
                    <td><?= htmlspecialchars($row['EmailUtente']) ?></td>
                    <td><?= htmlspecialchars($row['Stato']) ?></td>
                    <td>
                        <?php if ($row['Stato'] === 'in_attesa'): ?>
                            <form action="connessione/GestisciCandidatura.php" method="post" style="display:inline;">
                                <input type="hidden" name="id_candidatura" value="<?= $row['IdCandidatura'] ?>">
                                <input type="hidden" name="esito" value="accettata">
                                <button type="submit">Accetta</button>
                            </form>
                            <form action="connessione/GestisciCandidatura.php" method="post" style="display:inline;">
                                <input type="hidden" name="id_candidatura" value="<?= $row['IdCandidatura'] ?>">
                                <input type="hidden" name="esito" value="rifiutata">
                                <button type="submit">Rifiuta</button>
                            </form>
                        <?php else: ?>
                            Nessuna azione disponibile
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Non ci sono candidature disponibili per i tuoi progetti.</p>
<?php endif; ?>

<a href="VisualizzaProgettiPersonali.php"><button>Torna indietro</button></a>

</body>
</html>
