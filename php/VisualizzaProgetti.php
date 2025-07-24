<?php
//Mostra i progetti aperti chiamando la stored procedure apposita
include 'Connessione/db.php';
$sql = "CALL VisualizzaProgettiDisponibili()";
$result = $mysqli->query($sql);
unset($_SESSION['Progetto']);
//Se settato lo toglie così si possono vedere le specifiche del progetto giusto
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>Bostarter - Progetti Aperti</title>
    <link rel="stylesheet" href="style.css">
    <style>
        button {
            padding: 6px 12px;
            cursor: pointer;
        }
    </style>
</head>

<body>

    <h2>Progetti Disponibili</h2>
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
                    <th>Commenti</th>
                    <th>Foto</th>
                    <th>Reward Finanziamenti</th>
                    <th>Finanzia</th>
                    <th>Azione</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row["Nome Progetto"]) ?></td>
                        <td><?= htmlspecialchars($row["Descrizione"]) ?></td>
                        <td><?= htmlspecialchars($row["Data Inserimento"]) ?></td>
                        <td><?= htmlspecialchars($row["Data Limite"]) ?></td>
                        <td><?= htmlspecialchars($row["Budget Richiesto"]) ?> €</td>
                        <td><?= htmlspecialchars($row["Tipologia"]) ?></td>

                        <!-- Commenti rendirizza ad una pagina per vederli(ed eventualmente rispodere),
                        Ogni progetto costruisce la propria pagina con i commenti mandando con post il suo nome
                        alla pagina visualizzacommento-->
                        <td>
                            <form action="Commenti.php" method="post">
                                <input type="hidden" name="nome_progetto" value="<?= htmlspecialchars($row['Nome Progetto']) ?>">
                                <button type="submit">Commenti</button>
                            </form>
                        </td>
                        <!--si indirizza a pagina per visualizzare le foto-->
                        <td>
                            <form action="VisualizzazioneFotoProgetto.php" method="post">
                                <input type="hidden" name="nome_progetto" value="<?= htmlspecialchars($row['Nome Progetto']) ?>">
                                <button type="submit">Visualizza Foto</button>
                            </form>
                        </td>
                        <!-- Si indirizza a pagina per visualizzare i reward-->
                        <td>
                            <form action="VisualizzaReward.php" method="post">
                                <input type="hidden" name="nome_progetto" value="<?= htmlspecialchars($row['Nome Progetto']) ?>">
                                <button type="submit">Visualizza le Reward</button>
                            </form>
                        </td>
                        <!-- Per andare alla pagina dei finanziamenti -->
                        <td>
                            <form action="Finanziamento.php" method="post">
                                <input type="hidden" name="nome_progetto" value="<?= htmlspecialchars($row['Nome Progetto']) ?>">
                                <button type="submit">Finanzia</button>
                            </form>
                        </td>
                        <!-- A seconda della tipologia del progetto il bottone manda o ad una pagina per le
                         candidature o una per vedere i componenti hardware -->
                        <td>
                            <?php if (strtolower($row["Tipologia"]) === 'software'): ?>
                                <form action="manda_candidatura.php" method="post">
                                    <input type="hidden" name="nome_progetto" value="<?= htmlspecialchars($row['Nome Progetto']) ?>">
                                    <button type="submit">Manda candidatura</button>
                                </form>
                            <?php else: ?>
                                <form action="VisualizzazioneComponenti.php" method="post">
                                    <input type="hidden" name="nome_progetto" value="<?= htmlspecialchars($row['Nome Progetto']) ?>">
                                    <button type="submit">Controlla componenti</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nessun progetto disponibile.</p>
    <?php endif; ?>

    <?php
    // Chiudo la connessione
    $mysqli->close();
    ?>
    <br>
    <a href="HomePage.php"><button>HomePage</button></a>
</body>

</html>