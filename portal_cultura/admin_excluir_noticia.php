<?php
require_once 'verifica_admin.php';
require_once 'conexao.php';

$id = filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);
if (!$id) ir('dashboard_admin.php');

$st = $pdo->prepare("SELECT imagem FROM noticias WHERE id=?");
$st->execute([$id]);
$n = $st->fetch();

if ($n) {
    if ($n['imagem'] && file_exists('imagens/'.$n['imagem']))
        unlink('imagens/'.$n['imagem']);
    $pdo->prepare("DELETE FROM noticias WHERE id=?")->execute([$id]);
}

ir('dashboard_admin.php');
