<?php
// excluir_noticia.php
require_once 'verifica_login.php';
require_once 'conexao.php';

$user = usuarioLogado();
$id   = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) redirecionar('dashboard.php');

// Verifica autoria e busca imagem
$stmt = $pdo->prepare("SELECT imagem FROM noticias WHERE id = ? AND autor = ?");
$stmt->execute([$id, $user['id']]);
$n = $stmt->fetch();

if (!$n) redirecionar('dashboard.php');

// Remove imagem do disco
if ($n['imagem'] && file_exists('imagens/' . $n['imagem'])) {
    unlink('imagens/' . $n['imagem']);
}

$del = $pdo->prepare("DELETE FROM noticias WHERE id = ? AND autor = ?");
$del->execute([$id, $user['id']]);

redirecionar('dashboard.php');
