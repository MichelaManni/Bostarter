<?php
session_start();
//*Pagina che viene vista dopo il login da cui si può accedere al resto a seconda del ruolo vengono mostrate più o meno opzioni

//Lista di eventuali parametri salvati in $_SESSION[](tutti stringhe)
//-> Email = Email dell'utente 
//-> Ruolo = Ruolo dell'utente 
//-> Codice = Codice dell'admin se è loggato
//-> Progetto = Nome del progetto che si sta controllando
?>

<!DOCTYPE HTML>
<html>

<head>
	<title>Bostarter/Homepage</title>
	<link rel="stylesheet" href="style.css">
</head>

<body>
	<h1>Bostarter</h1>
	<p>
		<?php
		echo "Benvenuto " . $_SESSION['Email'];
		unset($_SESSION['Progetto']);
		?>
	</p>
	<!-- Viene controllato il ruolo per mostrare più o meno i pulsanti -->
	<div class="container">
		<a href="VisualizzaProgetti.php"><button class="Pulsantegrande" type="button">Visualizza i progetti aperti</button></a>
		<a href="Statistiche.php"><button class="Pulsantegrande" type="button">Visualizza statistiche</button></a>
		<a href="Profilo.php"><button class="Pulsantegrande" type="button">Visualizza profilo personale</button></a>
		<?php if ($_SESSION['Ruolo'] == "creatore") {
			echo "<a href='InserimentoProgetto.php'><button class='Pulsantegrande' type='button'>Inserisci nuovo progetto</button></a>";
			echo "<a href='GestioneProgetti.php'><button class='Pulsantegrande' type='button'>Gestisci i tuoi progetti</button></a>";
		}
		if ($_SESSION['Ruolo'] == "amministratore") {
			echo "<a href='InserimentoSkills.php'><button class='Pulsantegrande' type='button'>Inserisci Skills</button></a>";;
		}
		?>
		<a href="index.php"><button class="Pulsantegrande" type="button">Esci</button></a>
	</div>
</body>

</html>