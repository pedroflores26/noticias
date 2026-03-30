<?php
// verifica_login.php – Redireciona para login se não autenticado

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/funcoes.php';

if (!estaLogado()) {
    redirecionar('login.php');
}
