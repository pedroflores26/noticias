<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'funcoes.php';

// 🔒 verifica se está logado
if (!logado()) {
    header('Location: login.php');
    exit;
}