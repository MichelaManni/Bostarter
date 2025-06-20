<?php
//Usa la stored procedure per visualizzare progetti disponibili"

include 'Connessione/db.php';
$sql = "CALL VisualizzaProgettiDisponibili()";
$result = $mysqli->query($sql);
echo "<h2>Progetti Disponibili</h2>";

//Creazione della tabella il pulsanti portano alle rispettive pagine di ogni progetto
if ($result->num_rows > 0) {
    echo "<table border='1' cellpadding='8'>";
    echo "<tr>
            <th>Nome</th>
            <th>Descrizione</th>
            <th>Data Inserimento</th>
            <th>Data Limite</th>
            <th>Budget Richiesto</th>
            <th>Tipologia</th>
          </tr>";
//Mette i dati in ogni cella
while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row["Nome Progetto"]) . "</td>
                <td>" . htmlspecialchars($row["Descrizione"]) . "</td>
                <td>" . htmlspecialchars($row["Data Inserimento"]) . "</td>
                <td>" . htmlspecialchars($row["Data Limite"]) . "</td>
                <td>" . htmlspecialchars($row["Budget Richiesto"]) . " €</td>
                <td>" . htmlspecialchars($row["Tipologia"]) . "</td>
                <td><button>Commenti del progetto</button></td>
                <td><button>Finanzia</button></td>";
    // è possibile mandare candidature solo per lavorare a progetti software, per gli hardware è possibile vedere la lista dei componenti
    if (strtolower($row["Tipologia"]) === 'software') {
        echo "<td><button>Manda candidatura</button></td>";
    } 
    else {
        echo "<td> <button>Controlla componenti</button></td>"; 
    }
    echo " </tr>";
    }
    echo "</table>";
} 

// Chiusura della connessione
$mysqli->close();
?>

<!-- Pagina che viene vista dopo il login da cui si può accedere al resto -->
<!DOCTYPE HTML>
<html>
<head>
<title>Bostarter - Progetti Aperti</title>
<style>
body {background-color: powderblue;}
th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color:white;
  color: black;}
td,th {
  border: 1px solid black;
  padding: 8px;}
</style>
</head>
<body>
</body>
</html>