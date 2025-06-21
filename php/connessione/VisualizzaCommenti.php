<?php
include 'Connessione/db.php';
// Verifica che il dato sia stato inviato
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nome_progetto'])) {
    $_SESSION['Progetto'] = $_POST['nome_progetto'];
    $nomeProgetto = $_SESSION['Progetto'];
    
    //Chiamata alla stored procedure
    //Costruisce una tabella con i commenti relativi al progetto
    $stmt = $mysqli->prepare("CALL VisualizzaCommenti(?)");
    $stmt->bind_param("s", $nomeProgetto);
    $stmt->execute();
    $result = $stmt->get_result();
    echo "<h2>Commenti del progetto: " . htmlspecialchars($nomeProgetto) . "</h2>";

    if ($result->num_rows > 0) {
        echo "<table border='1' cellpadding='5'>
                <tr>
                    <th>Poster</th>
                    <th>Contenuto</th>
                    <th>Data</th>
                    <th>Risposta del cratore</th>
                </tr>";

        while ($row = $result->fetch_assoc()) {

            echo "<tr>
                    <td>" . htmlspecialchars($row['Poster']) . "</td>
                    <td>" . htmlspecialchars($row['Contenuto']) . "</td>
                    <td>" . htmlspecialchars($row['Data']) . "</td>
                    <td>" . htmlspecialchars($row['Risposta']) . "</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "Nessun commento trovato per questo progetto.";
    }
    $stmt->close();
    $mysqli->close();
} else {
    echo "Nessun progetto specificato.";
}
?>