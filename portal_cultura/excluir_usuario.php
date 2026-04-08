<?php
require_once 'verifica_login.php';
require_once 'conexao.php';

$me = sessaoUser();

// Remove imagens das notícias do usuário
$imgs = $pdo->prepare("SELECT imagem FROM noticias WHERE autor=?");
$imgs->execute([$me['id']]);
foreach ($imgs->fetchAll() as $r)
    if ($r['imagem'] && file_exists('imagens/'.$r['imagem']))
        unlink('imagens/'.$r['imagem']);

$pdo->prepare("DELETE FROM usuarios WHERE id=?")->execute([$me['id']]);
encerrarSessao();
ir('index.php');
