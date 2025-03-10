<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "php_students_db_2";

try{
    $conn = new PDO("mysql: host=$servername;dbname=$dbname",$username,$password);
    $conn-> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e){
    echo "connection failed:" . $e->getMessage();
    die();
}


?>