<?php
session_start();
include 'db.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Esito Candidatura</title>
    <link rel="stylesheet" href="../style.css"> 
    <h2>Esito Candidatura </h2>
</head>
<body>
<!--stored procedure per inserimento candidatura-->
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nome_profilo'], $_POST['nome_progetto'])) {
    $email_utente = $_SESSION['Email'];
    $nome_profilo = $_POST['nome_profilo'];
    $nome_progetto = $_POST['nome_progetto'];

    try {
        // Recupera l'ID del profilo
        $stmt = $mysqli->prepare("SELECT Id FROM Profili WHERE Nome = ? AND NomeProgetto = ?");
        $stmt->bind_param("ss", $nome_profilo, $nome_progetto);
        $stmt->execute();
        $stmt->bind_result($id_profilo);

        if ($stmt->fetch()) {
            $stmt->close();

            // Chiama la stored procedure per inserire la candidatura
            $stmt = $mysqli->prepare("CALL InserimentoCandidatura(?, ?)");
            $stmt->bind_param("si", $email_utente, $id_profilo);
            $stmt->execute();

            echo "<p class='successo'>Candidatura inviata con successo!</p>";
        } else {
            echo "<p class='errore'>Profilo non trovato</p>";
            $stmt->close();
        }
    } catch (mysqli_sql_exception $e) {
        $errorMsg = $e->getMessage();

        if (strpos($errorMsg, 'Skill insufficienti per la candidatura') !== false) {
            echo "<p class='errore'>Non possiedi tutte le competenze richieste per candidarti a questo profilo</p>";
        } elseif (strpos($errorMsg, 'Progetto non valido o non aperto') !== false) {
            echo "<p class='errore'>Non puoi candidarti a questo progetto perché non è attualmente aperto</p>";
        }elseif (strpos($errorMsg, 'Candidatura già effettuata') !== false) {
            echo "<p class='errore'>Hai già inviato una candidatura per questo profilo</p>";
        }elseif (strpos($errorMsg, 'già stato assegnato') !== false) {
            echo "<p class='errore'>Questo profilo è già stato assegnato. Non è possibile candidarsi</p>";
        } else {
            echo "<p class='errore'>Errore durante la candidatura: " . htmlspecialchars($errorMsg) . "</p>";
        }
    }
} else {
    echo "<p class='errore'>Richiesta non valida</p>";
    }
?>

<br>
<form action="../VisualizzazioneProfiliSoftware.php" method="post">  <!--non basta solo il button perche visualizzazione profili funziona solo se viene passato il nome del progetto tramite post-->
    <input type="hidden" name="nome_progetto" value="<?= htmlspecialchars($nome_progetto) ?>">
    <button type="submit">Torna indietro</button>
</form>

</body>
</html>
