<?php
//*Script per inviare le risposte al db
include 'Connessione/db.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (!isset($_SESSION['Email'])) {
    header("Location: index.php");
}

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['CodiceCommento']) && isset($_POST['Risposta'])) {
 
        $CodCommento = (int)$_POST['CodiceCommento'];
        $Risposta = trim($_POST['Risposta']);
        $IdCreatore = 1;

        $query = "CALL InserimentoRisposta(?, ?,?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('iis',$IdCreatore, $CodCommento, $Risposta);

        if ($stmt->execute()) {
            echo 'Risposta inserita con successo!';
            header("Commenti.php");
            exit;
        }

        $stmt->close();
    }
} catch (mysqli_sql_exception $e) {
    echo "Errore: " . $e->getMessage();
}
?>
