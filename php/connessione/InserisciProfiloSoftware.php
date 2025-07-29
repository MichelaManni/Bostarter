<?php

include 'db.php';
//STORED PROCEDURE PER AGGIUNTA COMPONENTE
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_profilo = $_POST['nome_profilo'];
    $nome_progetto = $_SESSION['nome_progetto']; //nome progetto e email presi dalla session
    $email = $_SESSION['Email'];

    $stmt = $mysqli->prepare("CALL AggiungiProfiloSoftware(?, ?, ?)");
    $stmt->bind_param("sss", $email, $nome_progetto, $nome_profilo);


    try {
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $id_profilo = $row['IdNuovoProfilo'];
            $stmt->close();

            if (!$id_profilo) {
                echo "<p>Errore: ID profilo non restituito.</p>";
                exit;
            }

            $_SESSION['id_profilo_corrente'] = $id_profilo;
            $_SESSION['nome_profilo'] = $nome_profilo;
            header("Location: ../InserimentoSkillProfilo.php");
            exit();
        } else { $errorMsg = $stmt->error; //messaggio errore generato dalla storedd procedure
            if (strpos($errorMsg, 'Progetto non trovato') !== false) { //errore dalla stored procedure
                echo "<p>Errore: Progetto non trovato </p>";
            }
            else{
                echo "<p>Errore nell'inserimento: " . htmlspecialchars($stmt->error) . "</p>";
            }
        }
    } catch (mysqli_sql_exception $e) {
        echo "<p>Errore: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}
?>