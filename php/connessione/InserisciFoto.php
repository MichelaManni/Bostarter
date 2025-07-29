<?php
include 'db.php';

//STORED PROCEDURE PER INSERIMENTO FOTO
$email = $_SESSION['Email']; //ricavo email dalla sessione 
    
//$_FILES['foto'] -> contiene le info del file caricato; ISSET -> restituisce true se variabile esiste e non è null
if(isset($_FILES['foto']) && $_FILES['foto']['error']===0){ //verifica che non ci siano errori
    $cartellaPerSalvareFoto ='caricamenti/';
    if (!file_exists($cartellaPerSalvareFoto)) {
        mkdir($cartellaPerSalvareFoto, 0777, true); // crea la cartella se non esiste
    }
    $fileTmp = $_FILES['foto']['tmp_name']; //tmp_name è il percorso temporaneo in cui php salva il file caricato
    $fileName = basename($_FILES['foto']['name']); //basename estrae solo il nome del file
    $targetPath = $cartellaPerSalvareFoto . uniqid() . "_" . $fileName; //genera un nome univoco per non sovrascrivere file esistenti per salvarlo nel percorso finale

    if (move_uploaded_file($fileTmp, $targetPath)) { //move_uploaded_file-> funzione che sposta file caricato dal percorso temporaneo a percorso finale scelto
            //se va a buon fine spostamento si passa a stored procedure x salvataggio nel db
        $query = "CALL AggiungiFotoProgetto(?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("sss", $nome_progetto, $targetPath, $email);
        try {
            if ($stmt->execute()) {
                echo "<p>Foto caricata correttamente!</p>";
            } else {
                $errorMsg = $stmt->error; //messaggio errore generato dalla storedd procedure
                if (strpos($errorMsg, 'Non sei il creatore') !== false) { //errore dalla stored procedure
                    echo "<p>Errore: Non sei il creatore del progetto selezionato </p>";
                } else { //altri errori generici
                    echo "<p>Errore durante l'inserimento della foto nel DB " . htmlspecialchars($errorMsg) . "</p>";
                }
            }
        }catch(mysqli_sql_exception $e){
            echo "<p> Errore: ". htmlspecialchars($e->getMessage()) . "</p>";
        }

        $stmt->close();
    }
}
