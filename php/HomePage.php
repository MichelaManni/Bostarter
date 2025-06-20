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
		}
        a{
            text-align: center;
        }
        h1{
            text-align: left;
        }
	</style>
</head>

<body>
	<h1> Bostarter </h1>
	<div>
			<?php
			echo "Benvenuto ". $_SESSION['Email'];
			?>
			<br>
			<br>
			<a href=VisualizzaProgetti.php><button type="submit">Visualizza i progetti aperti</button></a><br>
			<a href=index.php><button type="submit">Esci</button></a><br>
	</div>
</body>

</html>