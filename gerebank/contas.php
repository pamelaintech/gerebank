<?php
include('includes/db.php');

// Supondo que a sessão tenha o user_id do usuário logado
$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM contas_bancarias WHERE user_id = $user_id";
$result = mysqli_query($conn, $query);
?>

<h2>Suas Contas Bancárias</h2>
<table>
  <tr>
    <th>Banco</th>
    <th>Número da Conta</th>
    <th>Saldo</th>
    <th>Tipo de Conta</th> <!-- Novo campo para o tipo de conta -->
  </tr>
  <?php while($row = mysqli_fetch_assoc($result)): ?>
    <tr>
      <td><?php echo $row['banco']; ?></td>
      <td><?php echo $row['numero_conta']; ?></td>
      <td><?php echo $row['saldo']; ?></td>
      <td><?php echo $row['tipo_conta']; ?></td> <!-- Exibir o tipo de conta -->
    </tr>
  <?php endwhile; ?>
</table>

<form action="contasprocess.php" method="POST">
  <input type="text" name="banco" placeholder="Banco">
  <input type="text" name="numero_conta" placeholder="Número da Conta">
  <input type="submit" value="Adicionar Conta">
</form>
