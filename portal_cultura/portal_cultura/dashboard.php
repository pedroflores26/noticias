<?php
// dashboard.php – Painel do usuário logado
require_once 'verifica_login.php';
require_once 'conexao.php';

$user = usuarioLogado();

$stmt = $pdo->prepare("
    SELECT id, titulo, data FROM noticias
    WHERE autor = ?
    ORDER BY data DESC
");
$stmt->execute([$user['id']]);
$minhas = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Painel — Cultura &amp; Arte</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <div class="header-inner">
        <a href="index.php" class="logo">Cultura <span>&amp;</span> Arte</a>
        <nav>
            <a href="index.php">Início</a>
            <a href="nova_noticia.php" class="btn-nav">+ Nova Notícia</a>
            <a href="editar_usuario.php">Minha Conta</a>
            <a href="logout.php">Sair</a>
        </nav>
    </div>
</header>

<main class="container">
    <h1 class="page-title">Olá, <?= limpar($user['nome']) ?> 👋</h1>

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.2rem;flex-wrap:wrap;gap:.8rem;">
        <h2 style="font-family:'Playfair Display',serif;font-size:1.3rem;color:var(--vinho-escuro)">
            Minhas Notícias (<?= count($minhas) ?>)
        </h2>
        <a href="nova_noticia.php" class="btn btn-dourado">+ Publicar nova</a>
    </div>

    <?php if (empty($minhas)): ?>
        <div class="empty">
            <h3>Você ainda não publicou nenhuma notícia.</h3>
            <a href="nova_noticia.php" class="btn btn-primary" style="margin-top:1rem">Publicar agora</a>
        </div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Título</th>
                    <th>Data</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($minhas as $i => $n): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= limpar($n['titulo']) ?></td>
                    <td><?= formatarData($n['data']) ?></td>
                    <td>
                        <div class="acoes">
                            <a href="noticia.php?id=<?= $n['id'] ?>" class="btn btn-secondary" style="padding:.35rem .9rem;font-size:.8rem">Ver</a>
                            <a href="editar_noticia.php?id=<?= $n['id'] ?>" class="btn btn-primary" style="padding:.35rem .9rem;font-size:.8rem">Editar</a>
                            <a href="excluir_noticia.php?id=<?= $n['id'] ?>"
                               class="btn btn-danger"
                               style="padding:.35rem .9rem;font-size:.8rem"
                               onclick="return confirm('Excluir esta notícia?')">Excluir</a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <div style="margin-top:2.5rem;padding:1.5rem;background:var(--branco);border:var(--borda);border-radius:6px;box-shadow:var(--sombra);">
        <h3 style="font-family:'Playfair Display',serif;color:var(--vinho);margin-bottom:.8rem">Sua Conta</h3>
        <p>👤 <strong><?= limpar($user['nome']) ?></strong></p>
        <div style="margin-top:1rem;display:flex;gap:.8rem;flex-wrap:wrap;">
            <a href="editar_usuario.php" class="btn btn-secondary">✏️ Editar dados</a>
            <a href="excluir_usuario.php"
               class="btn btn-danger"
               onclick="return confirm('Deseja excluir sua conta permanentemente?')">🗑 Excluir conta</a>
        </div>
    </div>
</main>

<footer>
    <p>&copy; <?= date('Y') ?> <span>Cultura &amp; Arte</span></p>
</footer>

</body>
</html>
