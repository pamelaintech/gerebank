<?php
include 'includes/db.php'; // Inclui o arquivo de conexão PDO

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitização de dados de entrada
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    // Verifique se o usuário existe no banco de dados
    $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = :username");

    try {
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Login bem-sucedido, armazena informações do usuário na sessão
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: dash.php"); // Redireciona para o painel do usuário
            exit();
        } else {
            $error = "Usuário ou senha inválidos.";
        }
    } catch (PDOException $e) {
        $error = "Erro: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <h2>Login</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST">
        <input type="text" name="username" placeholder="Usuário" required>
        <input type="password" name="password" placeholder="Senha" required>
        <button type="submit">Login</button>
    </form>
    <p><a href="register.php">Não tem uma conta? Registrar</a></p>
</body>
</html>
