<?php
include 'db.php';
//stored procedure per inserire nuova componente
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $descrizione = $_POST['descrizione'];
    $quantita = $_POST['quantita'];
    $prezzo = $_POST['prezzo'];
    $nome_progetto = $_SESSION['nome_progetto'];

    $stmt = $mysqli->prepare("CALL AggiungiComponente(?, ?, ?, ?, ?)");
    $stmt->bind_param("ssids", $nome, $descrizione, $quantita, $prezzo, $nome_progetto);

    try {
        if ($stmt->execute()) {
            while ($mysqli->more_results() && $mysqli->next_result()) {
                if ($res = $mysqli->store_result()) {
                    $res->free(); //pulisce risultati
                }
            }
        } else {
            // Errore generato dalla stored procedure
            $_SESSION['error_message'] = "Errore nell'inserimento del componente: " . htmlspecialchars($stmt->error);
        }
    } catch (mysqli_sql_exception $e) {
        // Errore generale del database
        $_SESSION['error_message'] = "Errore di sistema: " . htmlspecialchars($e->getMessage());
    } finally {
        $stmt->close();
    }
    // Reindirizza 
    header("Location: ../AggiuntaContenutiNuovoProgetto.php"); 
    exit();
}
?>