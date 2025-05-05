<?php
include('includes/db.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conta_id = $_POST['conta_id'];
    $tipo = $_POST['tipo'];
    $valor = $_POST['valor'];

    $query = "SELECT saldo FROM contas_bancarias WHERE id = $conta_id";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $saldo_atual = $row['saldo'];

    if ($tipo == 'Depósito') {
        $novo_saldo = $saldo_atual + $valor;
    } elseif ($tipo == 'Saque') {
        if ($saldo_atual >= $valor) {
            $novo_saldo = $saldo_atual - $valor;
        } else {
            echo "Saldo insuficiente!";
            exit;
        }
    }

    $query_update = "UPDATE contas_bancarias SET saldo = $novo_saldo WHERE id = $conta_id";
    mysqli_query($conn, $query_update);

    $query_insert = "INSERT INTO transacoes (conta_id, tipo, valor) VALUES ($conta_id, '$tipo', $valor)";
    mysqli_query($conn, $query_insert);

    header('Location: trans.php');
}
?>
