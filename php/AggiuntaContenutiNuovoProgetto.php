<?php
session_start();
include 'connessione/db.php'; 

if (!isset($_SESSION['nome_progetto']) || !isset($_SESSION['tipologia'])) {
    header("Location: InserimentoProgetto.php"); 
    exit();
}
//Pagina che dopo l'aggiunta del nuovo progetto nella table Progetto permette di aggiungere reward, foto, profilo/componente
//Affinchè un progetto sia valido si obbliga utente a inserire almeno un reward e almento un componente/profilo
$nome_progetto = $_SESSION['nome_progetto'];
$tipologia = $_SESSION['tipologia'];
$almenoUnReward = false;
$almenoUnTipoSpecifico = false; 
$almenoUnaFoto = false;

// Controllo per i Reward
$stmt = $mysqli->prepare("CALL VisualizzazioneReward(?)");
$stmt->bind_param("s", $nome_progetto);
$stmt->execute();
$res = $stmt->get_result();
$almenoUnReward = $res && $res->num_rows > 0;
$stmt->close();
while ($mysqli->more_results() && $mysqli->next_result()) { if ($r = $mysqli->store_result()) { $r->free(); } }

// Controllo per il tipo specifico (Profili per software, Componenti per hardware)
if ($tipologia === "software") {
    $stmt = $mysqli->prepare("CALL VisualizzaProfili(?)");
} else {
    $stmt = $mysqli->prepare("CALL VisualizzaComponenti(?)");
}
$stmt->bind_param("s", $nome_progetto);
$stmt->execute();
$res = $stmt->get_result();
$almenoUnTipoSpecifico = $res && $res->num_rows > 0;
$stmt->close();
while ($mysqli->more_results() && $mysqli->next_result()) { if ($r = $mysqli->store_result()) { $r->free(); } }

//controllo per foto
$stmt = $mysqli->prepare("CALL VisualizzaFotoProgetto(?)");
$stmt->bind_param("s", $nome_progetto);
$stmt->execute();
$res = $stmt->get_result();
$almenoUnaFoto = $res && $res->num_rows > 0;
$stmt->close();
while ($mysqli->more_results() && $mysqli->next_result()) { if ($r = $mysqli->store_result()) { $r->free(); } }

$progettoCompleto = $almenoUnReward && $almenoUnTipoSpecifico && $almenoUnaFoto;

?>
<!DOCTYPE html>
<head>
    <title>Contenuti Nuovo Progetto</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php if ($progettoCompleto): ?>
        <a href="HomePage.php"><button class="ButtonBack">Completa Progetto e Torna Indietro</button></a>
    <?php else: ?>
        <p class="error-message">
        <p>ATTENZIONE: Per finalizzare il progetto <?php echo htmlspecialchars($nome_progetto); ?> devi aggiungere: </p>
            
                <?php if (!$almenoUnReward): ?>
                    <p>-- Almeno un Reward -- </p>
                <?php endif; ?>
                <?php if ($tipologia == "software" && !$almenoUnTipoSpecifico): ?>
                    <p>-- Almeno un Profilo (per progetti Software) -- </p>
                <?php elseif ($tipologia == "hardware" && !$almenoUnTipoSpecifico): ?>
                    <p>-- Almeno un Componente (per progetti Hardware) -- </p>
                <?php endif; ?>
                 <?php if (!$almenoUnaFoto): ?>
                    <p>-- Almeno una Foto -- </p>
                <?php endif; ?>
        </p>
    <?php endif; ?>

    <h1>Inserisci altre informazioni per il nuovo progetto</h1>
    <p>Il progetto <?php echo htmlspecialchars($nome_progetto); ?> è stato creato correttamente! Ora è possibile aggiungere le informazioni necessarie</p>
    
    <div class="container">
        <a href="InserimentoFoto.php"><button class="Pulsantegrande" type="button">Inserisci foto</button></a>
        <a href="InserimentoReward.php"><button class="Pulsantegrande" type="button">Inserisci reward</button></a>
        <?php if ($tipologia == "software"): ?>
            <a href="InserimentoProfiloSoftware.php"><button class="Pulsantegrande" type="button">Inserisci profili</button></a>
        <?php else: ?>
            <a href="InserimentoComponente.php"><button class="Pulsantegrande" type="button">Inserisci componenti</button></a>
        <?php endif; ?>
    </div>
</body>
</html>