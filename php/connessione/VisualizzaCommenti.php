<?php
include 'Connessione/db.php';
//*Script per visualizzare tutti i commenti relativi a un progetto
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['nome_progetto'])) {
        $_SESSION['Progetto'] = $_POST['nome_progetto'];
    }

    $nomeProgetto = $_SESSION['Progetto'];
    $EmailRegistrata = $_SESSION['Email'];

    //Controllo se si è il creatore del progetto per rispondere ai commenti
    //se esito è true allora quando si visualizzano i commenti del proprio progetto è possibile rispondere
    $stmt = $mysqli->prepare("CALL ControlloProgetto(?, ?, @Esito)");
    $stmt->bind_param("ss", $EmailRegistrata, $nomeProgetto);
    $stmt->execute();
    $stmt->close();
    $result = $mysqli->query("SELECT @esito AS esito");
    $row = $result->fetch_assoc();
    $esito = $row['esito'];

    //Chiamata alla stored procedure e costruisce una tabella con i commenti relativi al progetto
    $stmt = $mysqli->prepare("CALL VisualizzaCommenti(?)");
    $stmt->bind_param("s", $nomeProgetto);
    $stmt->execute();
    $result = $stmt->get_result();
    echo "<h2>Commenti del progetto: " . htmlspecialchars($nomeProgetto) . "</h2>";
    //Costruisce la tabella con il risultato della storede procedure, a seconda del ruolo vengono aggiunte/rimosse opzioni
    if ($result->num_rows > 0) {
        echo "<table class='T1' border='1' cellpadding='5'>
                <tr>
                    <th>Poster</th>
                    <th>Contenuto</th>
                    <th>Data</th>
                    <th>Risposta del cratore</th>";
        if ($esito) {
            echo "<th> </th>";
        }
        "</tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['Poster']) . "</td>
                    <td>" . htmlspecialchars($row['Contenuto']) . "</td>
                    <td>" . htmlspecialchars($row['Data']) . "</td>
                    <td>" . htmlspecialchars($row['Risposta']) . "</td>";
            if ($esito && $row['Risposta'] == '') {
                echo "<td><button onclick='Rispondi()'>Rispondi</button></td>";
            } else {
                echo "<td> </td>";
            }
            "</tr>";
        }
        echo "</table>";
    } else {
        echo "Nessun commento trovato per questo progetto.";
    }
    $stmt->close();
} else {
    echo "Nessun progetto specificato.";
}
