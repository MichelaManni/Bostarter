<?php
include 'db.php';
//stored procedure per visualizzare i finanziamenti effettuati dall'utente 
$elenco_finanziamenti = []; //array vuoto per memorizzarli


$EmailUtente = $_SESSION['Email'];

$stmt = $mysqli->prepare("CALL VisualizzaFinanziamentiPropri(?)");
$stmt->bind_param("s", $EmailUtente);
if($stmt->execute()){
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
                $elenco_finanziamenti[] = $row;
    }
}else{
    echo "<p> Errore nella visualizzazione dei finanziamenti: ". htmlspecialchars($stmt->error) . "</p>";
}

$stmt->close();


?>

