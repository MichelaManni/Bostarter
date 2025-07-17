<?php
session_start();//avvia sessione
?>
<!--form HTML per l'inserimento di un nuovo progetto-->
<!DOCTYPE html>
<head>
    <title> Creazione Progetto </title>
    <style>
	body {
		background-color: powderblue;
		text-align: center;
	}
	label {
		display: block;
		margin-bottom: 15px;
	}
</style>
</head>
<body>
    

    <form action="Connessione/InserisciProgetto.php" method="POST" style="position: absolute; top: 20px; left: 20px;">  <!--invia dati allo script connessione/InserisciProgetto.php-->
        <h2>Inserisci un nuovo progetto</h2>
        <label > Nome Progetto :  <input type="text" name="nome" required> </label><br>
        <label > Descrizione : <textarea name="descrizione" required> </textarea></label><br>
        <label > Budget richiesto in euro : <input type="number" step="0.01" name="budget" required></label><br>
        <label > Data limite : <input type="date" name="data_limite" required></label><br>
        <label > Tipologia : 
            <select name="tipologia" required>
                <option value = "hardware"> Hardware </option>
                <option value = "software"> Software </option>
            </select> 
        </label><br>

        <button>Crea progetto</button>

    </form>
</body>
</html>