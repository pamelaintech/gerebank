<?php 
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Obtendo as contas do usuário usando prepared statements
$stmt = $pdo->prepare("SELECT * FROM accounts WHERE user_id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$accounts = $stmt->fetchAll();

// Processar a transação
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitização de dados de entrada
    $account_id = filter_input(INPUT_POST, 'account_id', FILTER_SANITIZE_NUMBER_INT);
    $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
    $amount = filter_input(INPUT_POST, 'amount', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

    // Validar valor
    if ($amount <= 0) {
        $error = "O valor deve ser maior que zero.";
    } else {
        // Lógica para depósito
        if ($type === 'deposit') {
            $stmt = $pdo->prepare("UPDATE accounts SET balance = balance + ? WHERE id = ?");
            $stmt->execute([$amount, $account_id]);

            // Registrar transação
            $stmt = $pdo->prepare("INSERT INTO transactions (account_id, type, amount) VALUES (?, 'deposit', ?)");
            $stmt->execute([$account_id, $amount]);
            $success = "Depósito realizado com sucesso!";
        }

        // Lógica para saque
        if ($type === 'withdraw') {
            $stmt = $pdo->prepare("SELECT balance FROM accounts WHERE id = ?");
            $stmt->execute([$account_id]);
            $account = $stmt->fetch();

            if ($account['balance'] >= $amount) {
                $stmt = $pdo->prepare("UPDATE accounts SET balance = balance - ? WHERE id = ?");
                $stmt->execute([$amount, $account_id]);

                // Registrar transação
                $stmt = $pdo->prepare("INSERT INTO transactions (account_id, type, amount) VALUES (?, 'withdrawal', ?)");
                $stmt->execute([$account_id, $amount]);
                $success = "Saque realizado com sucesso!";
            } else {
                $error = "Saldo insuficiente para saque.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Transações</title>
    <link rel="stylesheet" href="css/trans.css">
</head>
<body>
    <h2>Realizar transações</h2>

    <?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="POST">
        <select name="account_id" required>
            <option value="">Selecione uma conta</option>
            <?php foreach ($accounts as $account): ?>
                <option value="<?php echo $account['id']; ?>">
                    Conta: <?php echo htmlspecialchars($account['account_number']); ?> - Saldo: R$ <?php echo number_format($account['balance'], 2, ',', '.'); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="type" required>
            <option value="">Selecione o tipo de transação</option>
            <option value="deposit">Depósito</option>
            <option value="withdraw">Saque</option>
        </select>

        <input type="number" name="amount" placeholder="Valor" step="0.01" required>
        <button type="submit">Realizar transação</button>
    </form>

    <p><a href="dash.php">Voltar ao dashboard</a></p>
</body>
</html>
