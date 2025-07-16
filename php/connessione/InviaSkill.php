<?php
include 'Connessione/db.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Skill_Inserita'])) {
        if (!isset($_SESSION['Email']) || !isset($_SESSION['Progetto'])) {
            die('Sessione non valida. Effettua il login o seleziona un progetto.');
        }
        $Skill_inserita = isset($_POST['Skill_Inserita']) ? trim($_POST['Skill_Inserita']) : '';

        //Usa la stored procedure per inserire i commenti
        $query = "CALL InserimentoCompetenza(?, ?)";
        if ($stmt = $mysqli->prepare($query)) {

            $stmt->bind_param('sss', $Skill_inserita,);
            if ($stmt->execute()) {
                echo 'Skill inserita con successo!';
            }
            $stmt->close();
        }
    }
} catch (mysqli_sql_exception $e) {
    echo "Errore: " . $e->getMessage();
}
?>
