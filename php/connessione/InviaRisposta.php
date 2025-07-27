<?php
//*Script per inviare le risposte al db
include 'Connessione/db.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (!isset($_SESSION['Email'])) {
    header("Location: index.php");
}

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Skill_Inserita'])) {

        $Skill_inserita = trim($_POST['Skill_Inserita']);
        $Codice_sicurezza = (int) $_SESSION['Codice'];

        $query = "CALL InserimentoRisposta(?, ?,?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('si', $Skill_inserita, $Codice_sicurezza);

        if ($stmt->execute()) {
            echo 'Risposta inserita con successo!';
        }

        $stmt->close();
    }
} catch (mysqli_sql_exception $e) {
    echo "Errore: " . $e->getMessage();
}
?>
