<?php
//Mostra i progetti aperti chiamando la stored procedure apposita
include 'Connessione/db.php';
$sql = "CALL VisualizzaProgettiDisponibili()";
$result = $mysqli->query($sql);
?>

<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title>Bostarter - Progetti Aperti</title>
    <style>
        body { background-color: powderblue; }
        table { border-collapse: collapse; width: 100%; }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: white;
            color: black;
            padding-top: 12px;
            padding-bottom: 12px;
        }
        button {
            padding: 6px 12px;
            cursor: pointer;
        }
        form { margin: 0; } /* elimina margini indesiderati */
    </style>
</head>
<body>

    <h2>Progetti Disponibili</h2>
    <?php if ($result && $result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Descrizione</th>
                    <th>Data Inserimento</th>
                    <th>Data Limite</th>
                    <th>Budget Richiesto</th>
                    <th>Tipologia</th>
                    <th>Commenti</th>
                    <th>Finanzia</th>
                    <th>Azione</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
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
                        <!-- Come per i commenti manda ad una specifica pagina -->
                        <td>
                            <form action="finanzia_progetto.php" method="post">
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
                                <form action="controlla_componenti.php" method="post">
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
