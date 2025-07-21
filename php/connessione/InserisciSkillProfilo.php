<?php

include 'db.php';
//STORED PROCEDURE PER AGGIUNTA SKILL A PROFILO
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome_profilo']; //nome skill e livello prese dal post
    $skill = $_POST['competenza'];
    $livello = $_POST['livello'];
    $id_profilo = $_SESSION['id_profilo_corrente'];  //id profilo preso dalla session


    $stmt = $mysqli->prepare("CALL AggiungiSkillProfilo(?, ?, ?)");
    $stmt->bind_param("isi", $id_profilo, $skill, $livello);

    try {
        if ($stmt->execute()) {
            echo "<p>Skill aggiunta correttamente! E' possibile aggiungerne un'altra o tornare indietro </p>";
        } else {
            $errorMsg = $stmt->error; //messaggio errore generato dalla storedd procedure
            if (strpos($errorMsg, 'Skill già inserita per questo profilo') !== false) {
                echo "<p>Errore: Skill già inserita per questo profilo</p>";
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