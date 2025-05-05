<?php
session_start();
include 'includes/db.php';

$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
$confirm_password = filter_input(INPUT_POST, 'confirm_password', FILTER_SANITIZE_STRING);

if ($password !== $confirm_password) {
    $error = "As senhas não coincidem!";
    header("Location: register.php?error=" . urlencode($error));
    exit();
}

$stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
$stmt->execute(['username' => $username]);
$user = $stmt->fetch();

if ($user) {
    $error = "Usuário já existe!";
    header("Location: register.php?error=" . urlencode($error));
    exit();
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare('INSERT INTO users (username, password) VALUES (:username, :password)');
$stmt->execute(['username' => $username, 'password' => $hashedPassword]);

$success = "Usuário cadastrado com sucesso!";
header("Location: login.php?success=" . urlencode($success));
exit();
?>
