<?php
// Connessione MySQL
$host = 'mysql';
$port = 3306;
$db   = 'Bostarter';
$user = 'username';
$pass = 'password';

$mysqli_real = mysqli_connect($host, $user, $pass, $db, $port);
if (!$mysqli_real) {
    http_response_code(500);
    die("Errore di connessione (mysqli): " . htmlspecialchars(mysqli_connect_error()));
}

// Logger Mongo + wrapper mysqli
require_once __DIR__ . '/../logger/MongoLogger.php';
require_once __DIR__ . '/../logger/MysqliLoggerWrapper.php';

// Il logger usa per default le credenziali del servizio "mongodb" del docker-compose
$__mongoLogger = new MongoLogger([
  // opzionali: override via env se vuoi
  // 'host' => getenv('MONGO_HOST') ?: 'mongodb',
  // 'username' => getenv('MONGO_USERNAME') ?: 'admin_username',
  // 'password' => getenv('MONGO_PASSWORD') ?: 'admin_password',
  // 'authSource' => getenv('MONGO_AUTHSOURCE') ?: 'admin',
  // 'database' => getenv('MONGO_DATABASE') ?: 'BostarterLogs',
  // 'collection' => getenv('MONGO_COLLECTION') ?: 'event_log',
]);

// Sostituisce l'oggetto $mysqli con il wrapper che logga
$mysqli = new MysqliLoggerWrapper($mysqli_real, $__mongoLogger);
