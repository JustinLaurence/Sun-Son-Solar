<?php
$host = 'localhost';
$dbname = 'sunson'; 
$db_user = 'root'; 
$db_pass = '';

// Connect to the database
$pdo = new PDO("mysql:host=$host;dbname=$dbname", $db_user, $db_pass);
?>