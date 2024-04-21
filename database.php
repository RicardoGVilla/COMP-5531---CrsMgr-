<?php
// code logic written by:
// Ricardo Gutierrez, 40074308

$host = 'localhost';
$port = '3306'; 
$dbname = 'crs_manager_final';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES 'utf8'");
    
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}
?>
