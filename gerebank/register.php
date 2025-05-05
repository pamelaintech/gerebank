<?php
// Iniciar sessão
session_start();

include 'includes/db.php';       
include 'includes/config.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitização de dados de entrada
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING); // Sanitiza a senha
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

    // Hash da senha
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Usando prepared statements para evitar SQL Injection
    $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (:username, :password, :email)");

    try {
        $stmt->execute([
            'username' => $username,
            'password' => $hashed_password,
            'email' => $email
        ]);
        $success = "Usuário cadastrado com sucesso!";
    } catch (PDOException $e) {
        $error = "Erro: " . $e->getMessage();
    }
}

// Gerar token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Registrar</title>
    <link rel="stylesheet" href="css/register.css">
</head>
<body>
    <h2>Registrar</h2>
    <?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST">
        <input type="text" name="username" placeholder="Usuário" required>
        <input type="password" name="password" placeholder="Senha" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <button type="submit">Registrar</button>
    </form>
    <p><a href="login.php">Já tem uma conta? Login</a></p>
</body>
</html>
