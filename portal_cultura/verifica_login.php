<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'conexao.php';

$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
$stmt->execute([$email]);
$usuario = $stmt->fetch();

if ($usuario && password_verify($senha, $usuario['senha'])) {

    $_SESSION['id'] = $usuario['id'];
    $_SESSION['tipo'] = $usuario['tipo'];

    if ($usuario['tipo'] === 'admin') {
        header("Location: dashboard_admin.php");
    } else {
        header("Location: dashboard_reporter.php");
    }
    exit;

} else {
    echo "❌ Email ou senha inválidos";
}