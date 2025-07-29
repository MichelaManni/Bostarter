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

    $email = $_SESSION['Email']; //ricavo email dalla sessione 

    
    //chiamata alla stored procedure per inserimento progetto
    $query = "CALL AggiungiProgetto(?,?,?,?,?,?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("sssdss", $email, $nome, $descrizione, $budget, $data_limite, $tipologia);
    try{
        //esecuzione procedure 
        if($stmt->execute()){
            $_SESSION['nome_progetto']=$nome;
            $_SESSION['tipologia']=$tipologia;
            //quando il progetto viene creato si reindirizza a nuova schermata x aggiunta foto, profili/componenti
            header("Location: ../AggiuntaContenutiNuovoProgetto.php");
            exit();
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
?>