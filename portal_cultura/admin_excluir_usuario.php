<?php
require_once 'verifica_admin.php';
require_once 'conexao.php';

$me = sessaoUser();
$id = filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);

// Não pode excluir a si mesmo
if (!$id || $id == $me['id']) ir('dashboard_admin.php');

// Remove imagens das notícias do usuário
$imgs = $pdo->prepare("SELECT imagem FROM noticias WHERE autor=?");
$imgs->execute([$id]);
foreach ($imgs->fetchAll() as $r)
    if ($r['imagem'] && file_exists('imagens/'.$r['imagem']))
        unlink('imagens/'.$r['imagem']);

// Cascata no FK já apaga as notícias, mas removemos as imagens antes
$pdo->prepare("DELETE FROM usuarios WHERE id=?")->execute([$id]);
ir('dashboard_admin.php');
