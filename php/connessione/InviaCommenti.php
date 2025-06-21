<?php
include 'Connessione/db.php';
// Verifica sessione attiva

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['testo'])) {
    if (!isset($_SESSION['Email']) || !isset($_SESSION['Progetto'])) {
        die('Sessione non valida. Effettua il login o seleziona un progetto.');
    }
$emailUtente   = $_SESSION['Email'];
$nomeProgetto  = $_SESSION['Progetto'];
$testoInserito = isset($_POST['testo']) ? trim($_POST['testo']) : '';

//Usa la stored procedure per inserire i commenti
$query = "CALL InserimentoCommento(?, ?, ?)";
if ($stmt = $mysqli->prepare($query)) {
    // Bind dei parametri: tutti stringhe
    $stmt->bind_param('sss', $emailUtente, $testoInserito, $nomeProgetto);
    if ($stmt->execute()) {
        echo 'Commento inserito con successo!';
    } else {
        // Gestione errori SQL (anche SIGNAL lanciato nella procedura)
        echo 'Errore durante l\'esecuzione della procedura: ' . htmlspecialchars($stmt->error);
    }
    $stmt->close();
} else {
    echo 'Errore nella preparazione della procedura: ' . htmlspecialchars($mysqli->error);
}
}
?>
