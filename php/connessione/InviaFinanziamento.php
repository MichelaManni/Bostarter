<?php
//*Per salvare i finanziamenti nel db
include "Connessione/db.php";
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (!isset($_SESSION['Email'])) {
    header("Location: index.php");
}

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['importo'], $_POST['reward'])) {

        $importo = $_POST['importo'];
        $codiceReward = $_POST['reward'];
        $EmailRegistrata = $_SESSION['Email'];
        $nomeProgetto = $_SESSION['Progetto'];

        $query = "CALL FinanziaProgetto(?, ?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('ssdi', $EmailRegistrata, $nomeProgetto, $importo, $codiceReward); // i = intero per codice reward
        $stmt->execute();

        header("Location:VisualizzaProgetti.php");
        exit;
    }
} catch (mysqli_sql_exception $e) {
    echo "Errore: " . $e->getMessage();
}
