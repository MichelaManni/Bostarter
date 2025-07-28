<?php
include 'db.php'; //connessione al database

//Chiamata alla stored procedure per l'inserimento di nuova reward
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //recupero i dati inviati dal form html
    $descrizione = $_POST['descrizione'];
    $foto = $_FILES['foto']['name'];
    
    $nome_progetto = $_SESSION['nome_progetto'];
    $email = $_SESSION['Email'];

    $query = "CALL GetIdCreatore(?, @idCreatore)";   //chiama la stored procedure per ritornare l'id dal creatore avendo la mail
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->close();

    $result = $mysqli->query("SELECT @idCreatore AS id");  //recupero valore output id
    $row = $result->fetch_assoc();
    $idCreatore = $row['id'];

    if ($idCreatore === null) {
        echo "<p>creatore non trovato</p>";
        exit;
    }
    //Gestione caricamento foto che è facolattivo
    $targetPath = null; // Default in caso non venga caricata nessuna foto

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) { //stesso procedimento di connessione/InserisciFoto
        $cartellaPerSalvareFoto = 'caricamenti/';
        if (!file_exists($cartellaPerSalvareFoto)) {
            mkdir($cartellaPerSalvareFoto, 0777, true);
        }

        $fileTmp = $_FILES['foto']['tmp_name'];
        $fileName = basename($_FILES['foto']['name']);
        $targetPath = $cartellaPerSalvareFoto . uniqid() . "_" . $fileName;

        if (!move_uploaded_file($fileTmp, $targetPath)) {
            echo "<p>Errore nel salvataggio della foto</p>";
            exit;
        }
    }

    //Chiamata alla stored procedure 
    $stmt = $mysqli->prepare("CALL InserimentoReward(?, ?, ?, ?)");
    $stmt->bind_param("sdsis", $descrizione,  $nome_progetto, $idCreatore, $targetPath);

    try {
        if ($stmt->execute()) {
            echo "<p>Reward inserito correttamente! Ora è possibile inserirne un'altra o tornare indietro </p>";
        } else {
            echo "<p>Errore durante l'inserimento del reward: " . htmlspecialchars($stmt->error) . "</p>";
        }
    } catch (mysqli_sql_exception $e) {
        echo "<p>Errore: " . htmlspecialchars($e->getMessage()) . "</p>";
    }

    $stmt->close();
}
?>

            
