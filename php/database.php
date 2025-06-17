<?php

$db_server = "host.docker.internal"; 
$db_user = "username"; 
$db_pw = "password";   
$db_name = "Bostarter"; 

$conn = mysqli_connect($db_server, $db_user, $db_pw, $db_name);

    if($conn){
        echo"1";
    }
    else{
        echo"2";
    }
?>