<?php
session_start();
include "Connessione/InviaFinanziamento.php";
include "Connessione/db.php";

//Per avere le info per compilare la procedure di finanziamento
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['nome_progetto'])) {
        $_SESSION['Progetto'] = $_POST['nome_progetto'];
    }

    $nomeProgetto = $_SESSION['Progetto'];
    $EmailRegistrata = $_SESSION['Email'];
} else {
    //Reindirizza alla visualizzazione dei progetti
}
$Prezzo_Inserito = 0;
?>

<!DOCTYPE HTML>
<html>

<head>
    <title><?php echo $nomeProgetto . "/Finanzia" ?></title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1><?php echo $nomeProgetto ?></h1>
    <h1><?php echo  $EmailRegistrata ?></h1>

    <!-- Menu a tendina per scegliere la propria reward-->
    <?php
    $stmt = $mysqli->prepare("SELECT Descrizione FROM Rewards WHERE PrezzoMinimo >= ? AND NomeProgetto = ?");
    $stmt = $mysqli->prepare("SELECT Descrizione FROM Rewards WHERE PrezzoMinimo >= ? AND NomeProgetto = ?");
    $stmt->bind_param("ds", $Prezzo_Inserito, $nomeProgetto); // "d" = double (o "i" per intero), "s" = string
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0): ?>
        <form action="invio.php" method="POST" style="width: 25%">
            <label for="reward">Reward:</label>
            <select style="width: 50%" name="reward" id="reward" required>
                <option value="">-- Seleziona --</option>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($row['Descrizoine']); ?>">
                        <?php echo htmlspecialchars($row['Descrizione']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <button type="submit">Invia</button>
        </form>
    <?php else: ?>
        <p>Nessuna reward disponibile.</p>
    <?php endif; ?>

    <?php $mysqli->close(); ?>
</body>

</html>