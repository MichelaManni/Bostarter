<?php
include 'db.php';

$email = $_POST['email'];
$nome = $_POST['nome'];
$cognome = $_POST['cognome'];
$anno = (int)$_POST['anno'];
$luogo = $_POST['luogo'];
$nickname = $_POST['nickname'];
$password = $_POST['password'];
$ruolo = $_POST['ruolo'];
$codiceSicurezza = $_POST['codiceSicurezza'] ?? null;

$passDaInserire = $password;
//$hash = password_hash($password, PASSWORD_DEFAULT); meglio per discorso sicurezza

$query = "CALL Registrazione(?,?,?,?,?,?,?,?,?)";

if($stmt= $mysqli->prepare($query)){
    $paramCodice = ($codiceSicurezza === null || $codiceSicurezza === '') ? null : (int)$codiceSicurezza;

    $stmt->bind_param( "sssissssi",$email, $nome, $cognome, $anno, $luogo, $nickname, $passDaInserire , $ruolo, $paramCodice);

    try{

        if($stmt->execute()){
            echo "<p> Registrazione effettuata con successo </p>";
        }else{
            $errorMsg = $stmt->error;
            if (strpos($errorMsg, 'Email già registrata') !== false) {
                echo "<p>Errore: L'email è già registrata.</p>";
            } elseif (strpos($errorMsg, 'Codice sicurezza obbligatorio') !== false) {
                echo "<p>Errore: Codice sicurezza obbligatorio per amministratori.</p>";
            } else {
                echo "<p>Errore durante la registrazione: " . htmlspecialchars($errorMsg) . "</p>";
            }
        }
    }catch(mysqli_sql_exception $e){
        echo "<p> Errore: ". htmlspecialchars($e->getMessage()) . "</p>";
    }
    $stmt->close();

}else{
    echo "<p>Errore nella preparazione della query: " . htmlspecialchars($mysqli->error) . "</p>";
}

?>