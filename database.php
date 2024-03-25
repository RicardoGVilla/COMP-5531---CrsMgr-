<?php
$host = 'localhost';
$port = '3306'; 
$dbname = 'crs_manager_final';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES 'utf8'");
    
    // Connection successful
    echo "Database connected successfully!";
} catch (PDOException $e) {
    // Connection failed, handle the error as needed
    echo "Connection failed: " . $e->getMessage();
    die();
}
?>