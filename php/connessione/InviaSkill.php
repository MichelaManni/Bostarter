<?php
//*Script per inviare le skill inserite dagli amministratori al db
include 'Connessione/db.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Skill_Inserita'])) {
        if (!isset($_SESSION['Email']) || !isset($_SESSION['Codice'])) {
            die('Sessione non valida. Effettua il login o seleziona un progetto.');
        }

        $Skill_inserita = trim($_POST['Skill_Inserita']);
        $Codice_sicurezza = (int) $_SESSION['Codice'];

        $query = "CALL InserimentoCompetenza(?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('si', $Skill_inserita, $Codice_sicurezza);

        if ($stmt->execute()) {
            echo 'Skill inserita con successo!';
        }

        $stmt->close();
    }
} catch (mysqli_sql_exception $e) {
    echo "Errore: " . $e->getMessage();
}
?>
