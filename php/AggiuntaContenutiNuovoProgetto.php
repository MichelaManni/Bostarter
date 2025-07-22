<?php
session_start();
$nome_progetto = $_SESSION['nome_progetto'] ;
$tipologia = $_SESSION['tipologia'];
?>
<!DOCTYPE html>
<head>
    <title>Contenuti Nuovo Progetto</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

	<!-- Pulsante back -->   
    <a href="HomePage.php"><button class="ButtonBack">Torna alla homepage</button></a>
    <h1>Inserisci altre informazioni per il nuovo progetto</h1>
    <p> Il progetto è stato creato correttamente! Ora è possibile aggiungere altre informazioni</p>
    <div class="container">
		<a href="InserimentoFoto.php"><button class="Pulsantegrande" type="button">Inserisci foto</button></a>
        <a href="InserimentoReward.php"><button class="Pulsantegrande" type="button">Inserisci reward</button></a>
		<?php if ($_SESSION['tipologia'] == "software"): ?>
            <a href="InserimentoProfiloSoftware.php"><button class="Pulsantegrande" type="button">Inserisci profili</button></a>
        <?php else: ?>
            <a href="InserimentoComponente.php"><button class="Pulsantegrande" type="button">Inserisci componenti</button></a>
        <?php endif; ?>

    </div>

</body>
</html>