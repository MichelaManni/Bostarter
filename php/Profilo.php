<?php
session_start();
include "Connessione/db.php";

$EmailDB = $_SESSION['Email'];
echo "<h1>Informazioni personali</h1>";
//Mostrare le informazioni base del profilo
$Query = " SELECT Nome,Cognome,AnnoNascita,LuogoNascita,Nickname,Ruolo FROM Utente WHERE Email = '$EmailDB'  limit 1;";
$result  = mysqli_query($mysqli, $Query);
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    echo "Email: " . $EmailDB . "<br>";
    echo "Nome: " . htmlspecialchars($row['Nome']) . "<br>";
    echo "Cognome: " . htmlspecialchars($row['Cognome']) . "<br>";
    echo "Anno di nascita: " . htmlspecialchars($row['AnnoNascita']) . "<br>";
    echo "Luogo di nascita: " . htmlspecialchars($row['LuogoNascita']) . "<br>";
    $Nickname = htmlspecialchars($row['Nickname']);
    echo "Nickname: " . $Nickname . "<br>";
    echo "Ruolo: " . htmlspecialchars($row['Ruolo']) . "<br>";
}
//File che trova le skill dell'utente e le stampa in una tabella
include "Connessione/VisualizzaSkillProprie.php";
?>

<!DOCTYPE HTML>
<html>
<head>
	<title><?php echo "$Nickname/Profilo"?> </title>
	<style>
		body {
			background-color: powderblue;
			font-family: Arial, sans-serif;
			margin: 0;
			padding: 20px;
		}
        h1{
            text-align: center;
            font-weight: 100;
        }
    </style>
</head>
<body>
    <br>    
    <a href="HomePage.php"><button>HomePage</button></a>
</body>
</html>