<?php
require_once 'verifica_login.php';
require_once 'conexao.php';

$u  = sessaoUser();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) ir('dashboard_reporter.php');

$st = $pdo->prepare("SELECT imagem FROM noticias WHERE id=? AND autor=?");
$st->execute([$id, $u['id']]);
$n = $st->fetch();
if (!$n) ir('dashboard_reporter.php');

if ($n['imagem'] && file_exists('imagens/'.$n['imagem']))
    unlink('imagens/'.$n['imagem']);

$pdo->prepare("DELETE FROM noticias WHERE id=? AND autor=?")->execute([$id, $u['id']]);
ir('dashboard_reporter.php');
