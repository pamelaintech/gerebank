<?php
$servername = "127.0.0.1:3307"; // Host e porta corretos
$username = "root";
$password = ""; // Coloque sua senha, caso tenha
$dbname = "banco"; // Nome do seu banco de dados

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>
