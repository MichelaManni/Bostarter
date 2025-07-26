<?php
include 'db.php';
//stored procedure per visualizzare i profili software richiesti
$elenco_profili = []; // array dei profili

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nome_progetto'])) {
    $nome_progetto = $_POST['nome_progetto'];

    $stmt = $mysqli->prepare("CALL VisualizzaProfili(?)");
    $stmt->bind_param("s", $nome_progetto);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        //i profili vengono raggruppati per nome e ad ogni nome si associano le skill richieste
        while ($row = $result->fetch_assoc()) {
            $nome_profilo = $row["Nome"];
            $competenza = $row["CompetenzaRichiesta"];
            $livello = $row["Livello"];
            //Se il profilo non è ancora stato inserito si inizializza
            if (!isset($elenco_profili[$nome_profilo])) {
                $elenco_profili[$nome_profilo] = [];
            }
            //si aggiunge la skill al profilo corrispondente
            $elenco_profili[$nome_profilo][] = [
                "Competenza" => $competenza,                    //operatore => definisce coppia chiave-vallore in array associativo, chiave=> valore
                "Livello" => $livello
            ];
        }

        
    } else {
        echo "<p>Errore durante la visualizzazione dei profili software richiesti: " . htmlspecialchars($stmt->error) . "</p>";
    }

    $stmt->close();
}
?>
