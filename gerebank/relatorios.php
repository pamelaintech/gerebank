<?php
include('includes/db.php');
session_start();

$user_id = $_SESSION['user_id'];
$filter_tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';
$filter_data_inicio = isset($_GET['data_inicio']) ? $_GET['data_inicio'] : '';
$filter_data_fim = isset($_GET['data_fim']) ? $_GET['data_fim'] : '';

// Construir a consulta SQL com filtros opcionais
$query = "SELECT t.*, c.banco, c.tipo_conta, c.numero_conta 
          FROM transacoes t 
          JOIN contas_bancarias c ON t.conta_id = c.id 
          WHERE c.user_id = $user_id";

if ($filter_tipo) {
    $query .= " AND t.tipo = '$filter_tipo'";
}

if ($filter_data_inicio && $filter_data_fim) {
    $query .= " AND t.data_transacao BETWEEN '$filter_data_inicio' AND '$filter_data_fim'";
}

$result = mysqli_query($conn, $query);
?>

<h2>Relatórios de Transações</h2>

<form method="GET" action="relatorios.php">
  <label for="tipo">Filtrar por Tipo:</label>
  <select name="tipo">
    <option value="">Todos</option>
    <option value="Depósito">Depósito</option>
    <option value="Saque">Saque</option>
  </select>

  <label for="data_inicio">Data Início:</label>
  <input type="date" name="data_inicio" value="<?php echo $filter_data_inicio; ?>">

  <label for="data_fim">Data Fim:</label>
  <input type="date" name="data_fim" value="<?php echo $filter_data_fim; ?>">

  <input type="submit" value="Filtrar">
</form>

<table>
  <tr>
    <th>Banco</th>
    <th>Tipo de Conta</th>
    <th>Número da Conta</th>
    <th>Tipo de Transação</th>
    <th>Valor</th>
    <th>Data da Transação</th>
  </tr>
  <?php while($row = mysqli_fetch_assoc($result)): ?>
    <tr>
      <td><?php echo $row['banco']; ?></td>
      <td><?php echo $row['tipo_conta']; ?></td>
      <td><?php echo $row['numero_conta']; ?></td>
      <td><?php echo $row['tipo']; ?></td>
      <td><?php echo $row['valor']; ?></td>
      <td><?php echo $row['data_transacao']; ?></td>
    </tr>
  <?php endwhile; ?>
</table>
