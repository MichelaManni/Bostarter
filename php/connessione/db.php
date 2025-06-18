<!-- Connessione con il database -->
<?php
// Parametri di connessione (corrispondono a quelli definiti in docker-compose)
$host = 'mysql';
$port = 3306;
$db   = 'Bostarter';
$user = 'username';
$pass = 'password';

$mysqli = mysqli_connect($host, $user, $pass, $db, $port);

// Verifica della connessione
if (!$mysqli) {
    // In caso di errore, invia header 500 e mostra messaggio
    http_response_code(500);
    die("Errore di connessione (mysqli): " . htmlspecialchars(mysqli_connect_error()));
}