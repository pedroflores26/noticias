<?php
// excluir_usuario.php
require_once 'verifica_login.php';
require_once 'conexao.php';

$user = usuarioLogado();

// Cascade ON DELETE remove as notícias automaticamente pelo FK
// mas vamos remover as imagens manualmente antes
$stmtImg = $pdo->prepare("SELECT imagem FROM noticias WHERE autor = ?");
$stmtImg->execute([$user['id']]);
foreach ($stmtImg->fetchAll() as $row) {
    if ($row['imagem'] && file_exists('imagens/' . $row['imagem'])) {
        unlink('imagens/' . $row['imagem']);
    }
}

$del = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
$del->execute([$user['id']]);

deslogarUsuario();
redirecionar('index.php');
