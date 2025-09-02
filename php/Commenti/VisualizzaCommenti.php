<?php
//*Classe che crea e aggiorna ogni volta la pagina dei commenti
class VisualizzatoreCommenti
{
    public $nomeProgetto;
    public $EmailRegistrata;
    public $conn;

    // Costruttore
    public function __construct($mysqli) {
        $this->conn = $mysqli;
    }

    public function CreaTabellaCommenti()
    {
        if (!isset($_SESSION['Progetto']) || !isset($_SESSION['Email'])) {
            echo "Sessione non valida.";
            return;
        }

        $nomeProgetto = $_SESSION['Progetto'];
        $EmailRegistrata = $_SESSION['Email'];

        // Controllo se si è il creatore del progetto
        $stmt = $this->conn->prepare("CALL ControlloProgetto(?, ?, @Esito)");
        $stmt->bind_param("ss", $EmailRegistrata, $nomeProgetto);
        $stmt->execute();
        $stmt->close();

        $result = $this->conn->query("SELECT @Esito AS esito");
        $row = $result->fetch_assoc();
        $esito = $row['esito'];

        // Chiamata alla stored procedure per i commenti
        $stmt = $this->conn->prepare("CALL VisualizzaCommenti(?)");
        $stmt->bind_param("s", $nomeProgetto);
        $stmt->execute();
        $result = $stmt->get_result();

        echo "<h2>Commenti del progetto: " . htmlspecialchars($nomeProgetto) . "</h2>";

        if ($result->num_rows > 0) {
            echo "<table class='T1' border='1' cellpadding='5'>
                <tr>
                    <th>Poster</th>
                    <th>Contenuto</th>
                    <th>Data</th>
                    <th>Risposta del creatore</th>";

            if ($esito) {
                echo "<th>Azioni</th>";
            }

            echo "</tr>";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>" . htmlspecialchars($row['Poster']) . "</td>
                    <td>" . htmlspecialchars($row['Contenuto']) . "</td>
                    <td>" . htmlspecialchars($row['Data']) . "</td>
                    <td>" . htmlspecialchars($row['Risposta']) . "</td>";

                if ($esito && $row['Risposta'] == '') {
                    echo "<td>
                        <form method='post'>
                            <input type='hidden' name='CodiceCommento' value='" . htmlspecialchars($row['CodiceCommento']) . "'>
                            <button type='submit'>Rispondi</button>
                        </form>
                    </td>";
                } else {
                    echo "<td></td>";
                }

                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "Nessun commento trovato per questo progetto.";
        }

        $stmt->close();
    }
}
