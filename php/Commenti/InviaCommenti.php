<?php
//*Script per inviare un commento al db
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
if (!isset($_SESSION['Email'])) {
    header("Location: index.php");
}

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['testo'])) {

        $Email_utente   = $_SESSION['Email'];
        $Nome_progetto  = $_SESSION['Progetto'];
        $Testo_inserito = isset($_POST['testo']) ? trim($_POST['testo']) : '';

        //Usa la stored procedure per inserire i commenti
        $query = "CALL InserimentoCommento(?, ?, ?)";
        if ($stmt = $mysqli->prepare($query)) {
            $stmt->bind_param('sss', $Email_utente, $Testo_inserito, $Nome_progetto);
            if ($stmt->execute()) {
                $_SESSION['flash_ok'] = 'commento inserito con successo';
                header('Location: PaginaCommenti.php');
                exit;
            }
            $stmt->close();
        }
    }
} catch (mysqli_sql_exception $e) {
    echo "Errore: " . $e->getMessage();
}
