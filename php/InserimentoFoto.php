<?php
session_start();
$nome_progetto = $_SESSION['nome_progetto'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'connessione/InserisciFoto.php';
}
?>

<!DOCTYPE html>
<head>
    <title>Inserisci Foto Progetto</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

	<!-- Pulsante back -->   
    <a href="HomePage.php"><button class="ButtonBack">Torna alla homepage</button></a>
    <h1>Inserisci una foto per un progetto</h1>
    <form method="POST" action="InserimentoFoto.php" enctype="multipart/form-data">
        <p>Nome del Progetto:</p>
            <input type="text" name="nome_progetto" value="<?php echo htmlspecialchars($nome_progetto); ?>" required>
        <p>Seleziona una foto (JPG, PNG):</p>
            <input type="file" name="foto" accept=".jpg,.jpeg,.png" required>
        <button type="submit">Carica foto</button>
    </form>
</body>
</html>
