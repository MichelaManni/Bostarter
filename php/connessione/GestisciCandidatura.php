<?php
session_start();
include 'db.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Esito Candidatura</title>
    <link rel="stylesheet" href="../style.css"> 
    <h2>Esito Operazione Di Accettazione/Rifiuto Candidatura </h2>
</head>
<body>
 <!--Script che chiama la procedure per accettare o rifiutare la candidatura-->  
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_candidatura'], $_POST['esito'])) {
    $id_candidatura = intval($_POST['id_candidatura']);
    $esito = $_POST['esito'];
    $email_creatore = $_SESSION['Email'];

    $stmt = $mysqli->prepare("CALL AccettazioneCandidatura(?, ?, ?)");
    $stmt->bind_param("iss", $id_candidatura, $email_creatore, $esito);

    try {
        if ($stmt->execute()) {
            echo "<p class='successo'>Candidatura $esito con successo.</p>";
        } else {
            echo "<p class='errore'>Errore durante l'esecuzione.</p>";
        }
    } catch (mysqli_sql_exception $e) {
        echo "<p class='errore'>Errore: " . htmlspecialchars($e->getMessage()) . "</p>";
    }

    $stmt->close();
} else {
    echo "<p class='errore'>Richiesta non valida.</p>";
}

$mysqli->close();
?>

<br>
<a href='../VisualizzaProgettiPersonali.php'><button>Torna indietro</button></a>
