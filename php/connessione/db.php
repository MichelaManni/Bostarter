<?php
//Classe che si occupa della connessione al db
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

// Logger Mongo + wrapper
require_once __DIR__ . '/../logger/Logger.php';
require_once __DIR__ . '/../logger/Wrapper.php';
$__mongoLogger = new MongoLogger([]);
$mysqli = new MysqliLoggerWrapper($mysqli_real, $__mongoLogger);
