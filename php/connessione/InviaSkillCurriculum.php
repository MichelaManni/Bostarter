<?php
//*Script per inviare le skill del proprio curriculum dal profilo personale al db
include 'connessione/db.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (!isset($_SESSION['Email'])) {
    header("Location: index.php");
}

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Competenza']) && isset($_POST['Livello'])) {

        $Competenza_Inserita = ($_POST['Competenza']);
        $Livello_Competenza = (int)$_POST['Livello'];
        $EmailDB = $_SESSION['Email'];

        $query = "CALL AggiungiSkillUtente(?, ?,?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('ssi',$EmailDB, $Competenza_Inserita, $Livello_Competenza);

        if ($stmt->execute()) {
            echo 'Skill inserita con successo!';
        }

        $stmt->close();
    }
} catch (mysqli_sql_exception $e) {
    echo "Errore: " . $e->getMessage();
}
?>