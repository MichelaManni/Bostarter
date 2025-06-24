<?php
include 'db.php';
//recupera i dati inviati tramite POST dal form html
$email = $_POST['email'];
$nome = $_POST['nome'];
$cognome = $_POST['cognome'];
$anno = (int)$_POST['anno']; //anno da convertire in int
$luogo = $_POST['luogo'];
$nickname = $_POST['nickname'];
$password = $_POST['password'];
$ruolo = $_POST['ruolo'];
$codiceSicurezza = $_POST['codiceSicurezza'] ?? null; //se non è presente è null (evita warning)

$passDaInserire = $password;
//$hash = password_hash($password, PASSWORD_DEFAULT); meglio per discorso sicurezza

//stored procedure Registrazione
$query = "CALL Registrazione(?,?,?,?,?,?,?,?,?)";

if($stmt= $mysqli->prepare($query)){//se preparazione della query ha avuto successo
   if ($codiceSicurezza === null || $codiceSicurezza === '') {
    $paramCodice = null;  // Se codice=null o è string vuota allora si passa null alla procedure
    } else {
        $paramCodice = (int)$codiceSicurezza;  //converte valore in int
    }

    $stmt->bind_param( "sssissssi",$email, $nome, $cognome, $anno, $luogo, $nickname, $passDaInserire , $ruolo, $paramCodice);

    try{
        //esecuzione query
        if($stmt->execute()){
            echo "<p> Registrazione effettuata con successo </p>";
        }else{
            $errorMsg = $stmt->error; //messaggio errore generato dalla storedd procedure
            if (strpos($errorMsg, 'Email già registrata') !== false) {
                echo "<p>Errore: L'email è già registrata.</p>";
            } elseif (strpos($errorMsg, 'Codice sicurezza obbligatorio') !== false) {
                echo "<p>Errore: Codice sicurezza obbligatorio per amministratori.</p>";
            } else { //altri errori generici
                echo "<p>Errore durante la registrazione: " . htmlspecialchars($errorMsg) . "</p>";
            }
        }
    }catch(mysqli_sql_exception $e){
        echo "<p> Errore: ". htmlspecialchars($e->getMessage()) . "</p>";
    }
    $stmt->close();

}else{ //se preparazione fallisce
    echo "<p>Errore nella preparazione della query: " . htmlspecialchars($mysqli->error) . "</p>";
}

?>