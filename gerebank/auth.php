<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($require_admin) && $_SESSION['role'] !== 'admin') {
    echo "Acesso negado.";
    exit();
}
?>
