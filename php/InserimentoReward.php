<?php
session_start();
$nome_progetto = $_SESSION['nome_progetto'];

if($_SERVER["REQUEST_METHOD"]=="POST"){
    include 'connessione/InserisciReward.php';
}

?>
<!--form HTML per l'inserimento di un nuovo progetto-->
<!DOCTYPE html>
<head>
    <title> Creazione Progetto </title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    	<!-- Pulsante back -->   
    <a href="AggiuntaContenutiNuovoProgetto.php"><button class="ButtonBack">Torna indietro</button></a>
    
    <form action="InserimentoReward.php" method="POST" enctype="multipart/form-data" >  <!--invia dati allo script -->
        <h1>Inserisci nuova reward</h1>
        <p> Nome Progetto :  </p>
            <input type="text" name="nome_progetto" value="<?php echo htmlspecialchars($nome_progetto); ?>" readonly required>
        <p > Descrizione : </p>
            <textarea name="descrizione" required> </textarea>
        <p > Carica una foto del reward : </p>
           <input type="file" name="foto" accept=".jpg,.jpeg,.png" required>
        <br>

        <button>Aggiungi Reward</button>

    </form>
</body>
</html>