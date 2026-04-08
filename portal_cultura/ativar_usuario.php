<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'funcoes.php';
require_once 'conexao.php';

// 🔒 só admin pode usar
if (!logado() || !ehAdmin()) {
    ir('login.php');
}

$id = $_GET['id'] ?? null;

if ($id) {
    // 🔐 senha padrão (usuario vai trocar depois)
    $senhaPadrao = password_hash('123456', PASSWORD_DEFAULT);

    $pdo->prepare("UPDATE usuarios SET status='ativo', senha=? WHERE id=?")
        ->execute([$senhaPadrao, $id]);
}

// 🔙 volta pro painel admin
ir('dashboard_admin.php');