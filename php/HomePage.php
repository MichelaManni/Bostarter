<?php
session_start();
?>
<!-- Pagina che viene vista dopo il login da cui si può accedere al resto -->
<!DOCTYPE HTML>
<html>

<head>
	<title>Bostarter</title>
	<style>
		body {
			background-color: powderblue;
			font-family: Arial, sans-serif;
			margin: 0;
			padding: 20px;
		}
		h1 {
			text-align: left;
		}
		.container {
			display: flex;
			flex-wrap: wrap;
			gap: 20px;
			justify-content: center;
			margin-top: 40px;
		}
		.container a {
			text-decoration: none;
			flex: 1 1 200px; 
			max-width: 300px;
		}
		button {
			width: 100%;
			padding: 20px;
			font-size: 1.2;
			background-color: white;
			color: black;
			border-radius: 10px;
			cursor: pointer;
		}
	</style>
</head>

<body>
	<h1>Bostarter</h1>
	<p>
		<?php
		echo "Benvenuto " . $_SESSION['Email'];
		?>
	</p>
	<div class="container">
		<a href="VisualizzaProgetti.php"><button type="button">Visualizza i progetti aperti</button></a>
		<a href="Statistiche.php"><button type="button">Visualizza statistiche</button></a>
		<a href="Profilo.php"><button type="button">Visualizza profilo personale</button></a>
		<?php if($_SESSION['Ruolo'] == "creatore"){
			echo "<a href='InserimentoProgetto.php'><button type='button'>Inserisci nuovo progetto</button></a>";
			echo "<a href='GestioneProgetti.php'><button type='button'>Gestisci i tuoi progetti</button></a>";
		}
		if($_SESSION['Ruolo'] == "amministratore"){
			echo "<a href='Profilo.php'><button type='button'>Inserisci Skills</button></a>";;
		}
		?>
		<a href="index.php"><button type="button">Esci</button></a>
	</div>
</body>

</html>