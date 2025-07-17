<?php
include 'db.php'; //connessione al database

//Chiamata alla stored procedure per l'inserimento di un nuovo progetto
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //recupero i dati inviati dal form html
    $nome = $_POST['nome'];
    $descrizione = $_POST['descrizione'];
    $budget = $_POST['budget'];
    $data_limite = $_POST['data_limite'];
    $tipologia = $_POST['tipologia'];

    $email = $_SESSION['Email']; //ricavo email dalla sessione per recuperare ID creatore

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
    }else{
        //chiamata alla stored procedure per inserimento progetto
        $query = "CALL AggiungiProgetto(?,?,?,?,?,?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("issdss", $idCreatore, $nome, $descrizione, $budget, $data_limite, $tipologia);
        try{
            //esecuzione procedure 
            if($stmt->execute()){
                echo "<p> Progetto inserito correttamente! </p>";
                echo "<div class='container'>";
                echo "<a href='../Homepage.php'><button class='Pulsantegrande' type='button'>Torna alla homepage</a></button>";
                echo "<a href='../InserimentoFoto.php'><button class='Pulsantegrande' type='button'> Aggiungi delle foto al progetto </a></button>";
                echo " </div>";
            } else{
                $errorMsg = $stmt->error; //messaggio errore generato dalla storedd procedure
                if (strpos($errorMsg, 'Id creatore non valido') !== false) {
                    echo "<p>Errore: Id creatore non valido </p>";
                }
                elseif(strpos($errorMsg, 'Nome progetto già esistente') !== false){
                    echo "<p>Errore: Progetto già esistente, cambiare nome </p>";
                } else { //altri errori generici
                echo "<p>Errore durante la registrazione: " . htmlspecialchars($errorMsg) . "</p>";
                }
            }
        }catch(mysqli_sql_exception $e){
             echo "<p> Errore: ". htmlspecialchars($e->getMessage()) . "</p>";
        }
        $stmt->close(); //chiuso statement procedure
        
    }
}
?>