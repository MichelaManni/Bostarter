<?php
	include("connessione/db.php"); 

	function Login($Log){
	$NickLogin = 
	$query = "select * from Utenti where Nickname = '$NickLogin' limit 1"; 
	$result = mysqli_query($Log,$query);
	}
?>
