<?php
//*Script per inviare le risposte al db
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (!isset($_SESSION['Email'])) {
    header("Location: index.php");
}

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['CodiceCommento']) && isset($_POST['Risposta'])) {

        $CodCommento = (int)$_POST['CodiceCommento'];
        $Risposta = trim($_POST['Risposta']);
        $email = $_SESSION['Email'];

        $query = "CALL InserimentoRisposta(?, ?,?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('sis', $email, $CodCommento, $Risposta);

        if ($stmt->execute()) {
            $_SESSION['flash_ok'] = 'Risposta inserita con successo';
            header('Location: PaginaCommenti.php');
            exit;
        }

        $stmt->close();
    }
} catch (mysqli_sql_exception $e) {
    echo "Errore: " . $e->getMessage();
}
