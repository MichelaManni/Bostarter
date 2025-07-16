<?php
session_start();
include 'Connessione/InviaSkill.php';
if($_SESSION['Ruolo'] != 'Amministratore'){}
?>
<!-- Pagina per l'inserimento delle skills -->
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
	<h1>Inserimento Skills</h1>
	<p>
		<?php
		echo "Benvenuto " . $_SESSION['Email'];
		?>
	</p>
</body>
</html>