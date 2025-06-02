<?php
$host = 'mysql';
$db   = 'mariadb_test_db';
$username = 'username';
$password = 'password';


try{
	$pdo= new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $username, $password);
	
	$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

}catch(PDOException $ex) {
	echo "Connessione non riuscita: " . $ex->getMessage();
	exit();
}
?>