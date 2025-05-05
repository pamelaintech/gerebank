<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard do GereBank</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<?php
include('auth.php'); // Proteção de página
include('includes/db.php');

// Obter dados para o gráfico de transações
$depositos = $pdo->query("SELECT COUNT(*) AS total FROM transacoes WHERE tipo = 'Depósito'")->fetch()['total'];
$saques = $pdo->query("SELECT COUNT(*) AS total FROM transacoes WHERE tipo = 'Saque'")->fetch()['total'];

// Obter dados para o gráfico de saldo por conta
$contas = $pdo->query("SELECT tipo_conta, saldo FROM contas_bancarias WHERE user_id = {$_SESSION['user_id']}");
$tipos = [];
$saldos = [];
foreach ($contas as $row) {
    $tipos[] = "'" . $row['tipo_conta'] . "'";
    $saldos[] = $row['saldo'];
}


$ultimasTransacoes = $pdo->query("SELECT data, tipo, valor FROM transacoes WHERE user_id = {$_SESSION['user_id']} ORDER BY data DESC LIMIT 5")->fetchAll();
?>

<!-- Cabeçalho e Menu de Navegação -->
<header class="header">
    <div class="logo">
        <img src="logo.png" alt="GereBank Logo">
    </div>
    <nav class="nav">
        <ul>
            <li><a href="#">Resumo da Conta</a></li>
            <li><a href="#">Transferências</a></li>
            <li><a href="#">Extrato</a></li>
            <li><a href="#">Investimentos</a></li>
            <li><a href="#">Configurações</a></li>
        </ul>
    </nav>
</header>

<div class="container">
    <!-- Resumo do Saldo -->
    <section class="saldo-resumo">
        <h2>Saldo Disponível</h2>
        <div class="saldo">R$ <?php echo number_format(array_sum($saldos), 2, ',', '.'); ?></div>
    </section>

    <!-- Gráficos -->
    <section class="graficos">
        <h3>Transações</h3>
        <canvas id="transacoesChart" width="400" height="200"></canvas>
        <script>
        var ctx = document.getElementById('transacoesChart').getContext('2d');
        var transacoesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Depósitos', 'Saques'],
                datasets: [{
                    label: 'Número de Transações',
                    data: [<?php echo $depositos; ?>, <?php echo $saques; ?>],
                    backgroundColor: ['#36a2eb', '#ff6384'],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        enabled: true
                    }
                }
            }
        });
        </script>

        <h3>Saldo por Tipo de Conta</h3>
        <canvas id="saldoChart" width="400" height="200"></canvas>
        <script>
        var ctx = document.getElementById('saldoChart').getContext('2d');
        var saldoChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: [<?php echo implode(',', $tipos); ?>],
                datasets: [{
                    data: [<?php echo implode(',', $saldos); ?>],
                    backgroundColor: ['#36a2eb', '#ff6384', '#ffcd56', '#4bc0c0'],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        enabled: true
                    }
                }
            }
        });
        </script>
    </section>

    <!-- Últimas Transações -->
    <section class="ultimas-transacoes">
        <h3>Últimas Transações</h3>
        <table class="transacoes-tabela">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Tipo</th>
                    <th>Valor (R$)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ultimasTransacoes as $transacao) : ?>
                    <tr>
                        <td><?php echo date('d/m/Y', strtotime($transacao['data'])); ?></td>
                        <td><?php echo htmlspecialchars($transacao['tipo']); ?></td>
                        <td><?php echo number_format($transacao['valor'], 2, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <!-- Ações Rápidas -->
    <section class="acoes-rapidas">
        <button class="btn btn-transferencia">Fazer Transferência</button>
        <button class="btn btn-pagamento">Pagar Conta</button>
        <button class="btn btn-poupanca">Adicionar Poupança</button>
    </section>
</div>

</body>
</html>
