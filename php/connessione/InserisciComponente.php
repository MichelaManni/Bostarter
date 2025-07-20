<?php

include 'db.php';
//STORED PROCEDURE PER AGGIUNTA COMPONENTE
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $descrizione = $_POST['descrizione'];
    $quantita = $_POST['quantita'];
    $prezzo = $_POST['prezzo'];
    $nome_progetto = $_SESSION['nome_progetto'];

    $query = "CALL AggiungiComponente(?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ssids", $nome, $descrizione, $quantita, $prezzo, $nome_progetto);

    try {
        if ($stmt->execute()) {
            echo "<p>Componente aggiunto correttamente!</p>";
        } else {
            $errorMsg = $stmt->error; //messaggio errore generato dalla storedd procedure
            if (strpos($errorMsg, 'Progetto non valido o non aperto') !== false) { //errore dalla stored procedure
                echo "<p>Errore: Problemi con il progetto: non valido o chiuso </p>";
            }
            else{
                echo "<p>Errore nell'inserimento: " . htmlspecialchars($stmt->error) . "</p>";
            }
            
        }
    } catch (mysqli_sql_exception $e) {
        echo "<p>Errore: " . htmlspecialchars($e->getMessage()) . "</p>";
    }

    $stmt->close();
}
?>
