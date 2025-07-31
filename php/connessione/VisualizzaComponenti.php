<?php
include 'db.php';
//stored procedure per visualizzare le componenti
$elenco_componenti = []; //array vuoto per memorizzare componenti

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nome_progetto'])) {
    $nome_progetto = $_POST['nome_progetto'];

    $stmt = $mysqli->prepare("CALL VisualizzaComponenti(?)");
    error_log("Nome Progetto ricevuto per VisualizzaComponenti: " . $nome_progetto);
    $stmt->bind_param("s", $nome_progetto);
    if($stmt->execute()){
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
                    $elenco_componenti[] = $row;
        }
    }else{
        echo "<p> Errore nella visualizzazione dei componenti: ". htmlspecialchars($stmt->error) . "</p>";
    }

    $stmt->close();
    
}
?>

