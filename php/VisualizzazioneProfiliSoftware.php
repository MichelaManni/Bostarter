<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'connessione/VisualizzaProfiliSoftware.php';
    $_SESSION['nome_progetto_profili'] = $_POST['nome_progetto'];
}
?>
<!-- html per visualizzare profili e candidarsi eventualmente-->
<!DOCTYPE html>
<html>
<head>
    <title>Profili Software Richiesti</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>


<h2>Profili software richiesti per il progetto: <?= htmlspecialchars($_SESSION['nome_progetto_profili']) ?></h2>
<p> Di seguito i profili software con le skill richieste. Le candidature vengono prese in considerazione solo se si rispettano i requisiti minimi. </p>
<?php if (!empty($elenco_profili)): ?>
    <table class="t1">
        <thead>
            <tr>
                <th>Nome profilo</th>
                <th>Competenze Richieste</th>
                <th>Candidatura</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($elenco_profili as $nome_profilo => $lista_skill): ?>
                <tr>
                    <td><?= htmlspecialchars($nome_profilo) ?></td>
                    <td>
                        <ul>
                            <?php foreach ($lista_skill as $skill): ?>
                                <li><?= htmlspecialchars($skill["Competenza"]) ?> (Livello min: <?= $skill["Livello"] ?>)</li>
                            <?php endforeach; ?>
                        </ul>
                    </td>
                    <td>
                        <form action="connessione/InserisciCandidatura.php" method="post">
                            <input type="hidden" name="nome_profilo" value="<?= htmlspecialchars($nome_profilo) ?>">
                            <input type="hidden" name="nome_progetto" value="<?= htmlspecialchars($_SESSION['nome_progetto_profili']) ?>">
                            <button type="submit">Candidati</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Nessun profilo disponibile per questo progetto.</p><br>
<?php endif; ?>

<br>
<a href="VisualizzaProgetti.php"><button>Torna indietro</button></a><br>
</body>
</html>
          
