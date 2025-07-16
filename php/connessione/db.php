<?php
//*Chiamato da quasi tutti gli script che interfacciano con il db serve appunto per connettersi ad esso
//tramite mysqli
$host = 'mysql';
$port = 3306;
$db   = 'Bostarter';
$user = 'username';
$pass = 'password';
$mysqli = mysqli_connect($host, $user, $pass, $db, $port);
if (!$mysqli) {
    http_response_code(500);
    die("Errore di connessione (mysqli): " . htmlspecialchars(mysqli_connect_error()));
}