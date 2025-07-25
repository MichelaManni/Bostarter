<?php
session_start();
unset($_SESSION['Progetto']);
if($_SERVER["REQUEST_METHOD"]=="POST"){
    include 'connessione/InserisciProgetto.php';
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
    <a href="HomePage.php"><button class="ButtonBack">Torna alla homepage</button></a>
    
    <form action="InserimentoProgetto.php" method="POST" >  <!--invia dati allo script connessione/InserisciProgetto.php-->
        <h1>Inserisci un nuovo progetto</h1>
        <p> Nome Progetto :  </p>
        <input type="text" name="nome" required><br>
        <p > Descrizione : </p>
        <textarea name="descrizione" required> </textarea><br>
        <p> Budget richiesto in euro : </p>
        <input type="number" step="0.01" name="budget" required><br>
        <p > Data limite : </p>
        <input type="date" name="data_limite" required><br>
        <p > Tipologia : </p>
            <select name="tipologia" required>
                <option value = "hardware"> Hardware </option>
                <option value = "software"> Software </option>
            </select> 
        <br>

        <button>Crea progetto</button>

    </form>
</body>
</html>