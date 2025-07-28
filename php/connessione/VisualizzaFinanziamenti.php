<?php
include 'db.php';
//stored procedure per visualizzare i finanziamenti effettuati su un progetto
$elenco_finanziamenti = []; //array vuoto per memorizzarli

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nome_progetto'])) {
    $nome_progetto = $_POST['nome_progetto'];

    $stmt = $mysqli->prepare("CALL VisualizzaFinanziamenti(?)");
    $stmt->bind_param("s", $nome_progetto);
    if($stmt->execute()){
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
                    $elenco_finanziamenti[] = $row;
        }
    }else{
        echo "<p> Errore nella visualizzazione dei finanziamenti: ". htmlspecialchars($stmt->error) . "</p>";
    }

    $stmt->close();
    
}
?>

