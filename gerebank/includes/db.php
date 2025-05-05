<?php
$host = '127.0.0.1';
$port = '3307';        
$db = 'banco';         
$user = 'root';
$pass = '';            

try {

    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Conexão bem-sucedida"; 
} catch (PDOException $e) {

    die("Conexão falhou: " . $e->getMessage());
}
?>
