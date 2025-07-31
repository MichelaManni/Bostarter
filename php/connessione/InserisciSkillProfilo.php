<?php
include 'db.php';
//stored procedure per inserire skill a profilo software
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $skill = $_POST['competenza'];
    $livello = $_POST['livello'];
    $id_profilo = $_SESSION['id_profilo_corrente']; 

    $stmt = $mysqli->prepare("CALL AggiungiSkillProfilo(?, ?, ?)");
    $stmt->bind_param("isi", $id_profilo, $skill, $livello);

    try {
        if ($stmt->execute()) {
            while ($mysqli->more_results() && $mysqli->next_result()) {
                if ($res = $mysqli->store_result()) {
                    $res->free();  //pulisce i risultati
                }
            }
        } else {
            // Errore generato dalla stored procedure 
            $_SESSION['error_message'] = "Errore nell'inserimento della skill: " . htmlspecialchars($stmt->error);
        }
    } catch (mysqli_sql_exception $e) {
        // Errore generale del database
        $_SESSION['error_message'] = "Errore di sistema: " . htmlspecialchars($e->getMessage());
    } finally {
        $stmt->close();
    }
    // reindirizza alla pagina di aggiunta skill (per mostrare eventuali errori o continuare ad aggiungere skill)
    header("Location: ../InserimentoSkillProfilo.php"); 
    exit();
}
?>