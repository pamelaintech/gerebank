<?php
include('includes/db.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $banco = $_POST['banco'];
    $numero_conta = $_POST['numero_conta'];
    $tipo_conta = $_POST['tipo_conta'];
    $saldo = 0.00; // Novo saldo começa com 0

    $query = "INSERT INTO contas_bancarias (user_id, banco, numero_conta, saldo, tipo_conta) 
              VALUES ($user_id, '$banco', '$numero_conta', $saldo, '$tipo_conta')";
    
    if (mysqli_query($conn, $query)) {
        header('Location: contas.php');
    } else {
        echo "Erro ao adicionar conta.";
    }
}
?>
