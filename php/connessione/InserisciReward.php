<?php

include 'db.php'; //connessione al database
//Stored procedure per inserimento reward
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $descrizione = $_POST['descrizione'];
    $nome_progetto = $_SESSION['nome_progetto'];
    $email = $_SESSION['Email'];
    
    $targetPath = null; 
    $hasError = false; // per tracciare se si è verificato un errore

    // Gestione dell'upload della foto
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
        $cartellaPerSalvareFoto = 'caricamenti/';
        if (!file_exists($cartellaPerSalvareFoto)) {
            mkdir($cartellaPerSalvareFoto, 0777, true);
        }

        $fileTmp = $_FILES['foto']['tmp_name'];
        $fileName = basename($_FILES['foto']['name']);
        $targetPath = $cartellaPerSalvareFoto . uniqid() . "_" . $fileName;

        if (!move_uploaded_file($fileTmp, $targetPath)) {
            $_SESSION['error_message'] = "Errore nel salvataggio della foto. Riprova.";
            $hasError = true;
        }
    } else if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
        // Errore di upload del file
        // UPLOAD_ERR_NO_FILE significa che nessun file è stato caricato, gestito dal 'required' HTML
        $_SESSION['error_message'] = "Errore nel caricamento della foto: " . $_FILES['foto']['error'] . " Verifica dimensione e formato";
        $hasError = true;
    } else {
        // Questo se 'required' nell'HTML non funziona
        $_SESSION['error_message'] = "La foto del reward è obbligatoria";
        $hasError = true;
    }

    // Se errore nell'upload della foto non si va avanti con l'inserimento
    if ($hasError) {
        header("Location: ../InserimentoReward.php"); // Reindirizza alla pagina 
        exit();
    }

    // Preparazione ed esecuzione della stored procedure
    $stmt = $mysqli->prepare("CALL InserimentoReward(?, ?, ?, ?)");
    $stmt->bind_param("ssss", $descrizione, $nome_progetto, $email, $targetPath); 

    try {
        if ($stmt->execute()) {
            while ($mysqli->more_results() && $mysqli->next_result()) {
                if ($res = $mysqli->store_result()) {
                    $res->free();// Pulisce i risultati  dalla stored procedure se ce ne sono.
                }
            }
        } else {
            // Errore generato dalla stored procedure
            $_SESSION['error_message'] = "Errore nell'inserimento del reward: " . htmlspecialchars($stmt->error);
        }
    } catch (mysqli_sql_exception $e) {
        // Errore generale del database
        $_SESSION['error_message'] = "Errore di sistema: " . htmlspecialchars($e->getMessage());
    } finally {
        $stmt->close(); 
    }

    // Reindirizza alla pagina di gestione contenuti dopo elaborazione del POST
    header("Location: ../AggiuntaContenutiNuovoProgetto.php"); 
    exit();
}
?>