<?php
$host = 'mysql';
$db   = 'mariadb_test_db';
$user = 'username';
$pass = 'password';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}
echo "<h1>Connessione al database riuscita!</h1>";
$conn->close();
