<?php
include 'db.php';

$elenco_reward = []; // array dei reward

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nome_progetto'])) {
    $nome_progetto = $_POST['nome_progetto'];

    $stmt = $mysqli->prepare("CALL VisualizzazioneReward(?)");
    $stmt->bind_param("s", $nome_progetto);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $elenco_reward[] = $row;
        }
    } else {
        echo "<p>Errore durante la visualizzazione dei reward: " . htmlspecialchars($stmt->error) . "</p>";
    }

    $stmt->close();
}
?>
