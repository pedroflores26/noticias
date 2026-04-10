<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'funcoes.php';        // 🔥 garante funções
require_once 'verifica_login.php'; // 🔥 valida login
require_once 'conexao.php';

// 🔒 garante que está logado
if (!logado()) {
    ir('login.php');
}

$me = sessaoUser();

// 🧹 Remove imagens das notícias do usuário
$imgs = $pdo->prepare("SELECT imagem FROM noticias WHERE autor=?");
$imgs->execute([$me['id']]);

foreach ($imgs->fetchAll() as $r) {
    if (!empty($r['imagem']) && file_exists('imagens/' . $r['imagem'])) {
        unlink('imagens/' . $r['imagem']);
    }
}

// 🗑️ Exclui usuário (cascade remove notícias)
$pdo->prepare("DELETE FROM usuarios WHERE id=?")->execute([$me['id']]);

// 🔐 encerra sessão
encerrarSessao();

// 🔙 volta pra home
ir('index.php');