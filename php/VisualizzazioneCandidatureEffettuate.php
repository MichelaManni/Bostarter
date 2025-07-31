<?php
session_start();
include 'connessione/VisualizzaCandidatureEffettuate.php';
$EmailUtente = $_SESSION['Email'];

?>
<!-- html per visualizzare l'elenco delle candidature fatte dall'utente-->
<!DOCTYPE html>
<html>
<head>
    <title>Candidature fatte</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>


<h2>Candidature effettuate</h2>

<?php if (!empty($elenco_candidature)): ?>
    <p> Di seguito l'elenco delle candidature che hai effettuato per dei profili software con il relativo esito.</p>
    <table class="t1">
        <thead>
            <tr>
                <th>Nome Progetto</th>
                <th>Nome Profilo</th>
                <th>Esito candidatura</th>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = 0; $i < count($elenco_candidature); $i++):
                $candidatura = $elenco_candidature[$i]; ?> 
                <tr>
                    <td><?= htmlspecialchars($candidatura["NomeProgetto"]) ?></td>
                    <td><?= htmlspecialchars($candidatura["NomeProfilo"]) ?></td>
                    <td><?= htmlspecialchars($candidatura["StatoCandidatura"]) ?></td>
                </tr>
            <?php endfor; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Non hai ancora effettuato nessuna candidatura</p><br>
<?php endif; ?>
<br>
<a href="Profilo.php"><button >Torna indietro</button></a><br>
</body>
</html>
          
