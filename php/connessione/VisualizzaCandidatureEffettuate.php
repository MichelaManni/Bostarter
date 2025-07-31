<?php
include 'db.php';
//stored procedure per visualizzare le candidature effettuati dall'utente 
$elenco_candidature = []; //array vuoto per memorizzarli


$EmailUtente = $_SESSION['Email'];

$stmt = $mysqli->prepare("CALL VisualizzaCandidatureUtente(?)");
$stmt->bind_param("s", $EmailUtente);
if($stmt->execute()){
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
                $elenco_candidature[] = $row;
    }
}else{
    echo "<p> Errore nella visualizzazione delle candidature: ". htmlspecialchars($stmt->error) . "</p>";
}

$stmt->close();


?>

