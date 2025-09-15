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
$stmt_rewards = $mysqli->prepare("SELECT COUNT(*) FROM Rewards WHERE NomeProgetto = ?");
$stmt_rewards->bind_param("s", $nome_progetto);
$stmt_rewards->execute();
$stmt_rewards->bind_result($count_rewards);
$stmt_rewards->fetch();
$stmt_rewards->close();
if ($count_rewards > 0) {
    $almenoUnReward = true;
}

// Controllo per il tipo specifico (Profili per software, Componenti per hardware)
if ($tipologia == "software") {
    $stmt_profili = $mysqli->prepare("SELECT COUNT(*) FROM Profili WHERE NomeProgetto = ?");
    $stmt_profili->bind_param("s", $nome_progetto);
    $stmt_profili->execute();
    $stmt_profili->bind_result($count_profili);
    $stmt_profili->fetch();
    $stmt_profili->close();
    if ($count_profili > 0) {
        $almenoUnTipoSpecifico = true;
    }
} else { // 'hardware'
    $stmt_componenti = $mysqli->prepare("SELECT COUNT(*) FROM Componenti WHERE NomeProgetto = ?");
    $stmt_componenti->bind_param("s", $nome_progetto);
    $stmt_componenti->execute();
    $stmt_componenti->bind_result($count_componenti);
    $stmt_componenti->fetch();
    $stmt_componenti->close();
    if ($count_componenti > 0) {
        $almenoUnTipoSpecifico = true;
    }
}
//controllo per foto
$stmt_foto = $mysqli->prepare("SELECT COUNT(*) FROM FotoProgetto WHERE NomeProgetto = ?");
$stmt_foto->bind_param("s", $nome_progetto);
$stmt_foto->execute();
$stmt_foto->bind_result($count_foto);
$stmt_foto->fetch();
$stmt_foto->close();
if ($count_foto > 0) {
    $almenoUnaFoto = true;
}
$progettoCompleto = $almenoUnReward && $almenoUnTipoSpecifico &&$almenoUnaFoto; 

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