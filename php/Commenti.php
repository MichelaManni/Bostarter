<?php
session_start();
//*Pagina per vedere e inviare commenti e risposte
include 'Connessione/db.php';
include 'Connessione/InviaCommenti.php';
include 'Connessione/VisualizzaCommenti.php'
?>

<!DOCTYPE HTML>
<html>

<head>
    <title><?php echo $nomeProgetto . "/Commenti" ?></title>
    <link rel="stylesheet" href="style.css">
    <style>
        .BoxRisposta {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .BoxRisposta-Interno {
            background: white;
            padding: 10px;
            border-radius: 8px;
            width: 400px;
            height: 300px;
        }
    </style>
</head>

<body>
    <div>
        <h1>Insersci un commento</h1><br>
        <form method="post" action="Commenti.php"><br>
            <textarea id="testo" name="testo" rows="5" cols="60" maxlength="500" required></textarea><br><br>
            <button type="submit">Invia Commento</button>
        </form>
        <br>
        <a href=VisualizzaProgetti.php><button type="submit">Torna ai progetti</button></a><br>

        <div class="BoxRisposta" id="Rispondi">
            <div class="BoxRisposta-Interno">
                <h2>Aggiungi Risposta</h2>
                <p>Da Fare</p>
                <button onclick="ChiudiRisposta()">Chiudi</button>
            </div>
        </div>
        <!-- Per mostrare la pagina in sovraimpressione per rispondere-->
        <script>
            function Rispondi() {
                document.getElementById("Rispondi").style.display = "flex";
            }

            function ChiudiRisposta() {
                document.getElementById("Rispondi").style.display = "none";
            }
        </script>

</body>

</html>