<?php
session_start();
include 'includes/db.php';

$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

$stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
$stmt->execute(['username' => $username]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role']; 

    header("Location: dash.php");
    exit();
} else {
    $error = "Credenciais inválidas!";
    header("Location: login.php?error=" . urlencode($error));
    exit();
}
?>
