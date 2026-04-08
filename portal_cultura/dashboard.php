<?php
require_once 'verifica_login.php';
require_once 'conexao.php';

$user = usuarioLogado();
$stmt = $pdo->prepare("SELECT id, titulo, data FROM noticias WHERE autor = ? ORDER BY data DESC");
$stmt->execute([$user['id']]);
$minhas = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Painel — Vernissage</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>
    <div class="header-top"><span>Vernissage</span> &nbsp;·&nbsp; Cultura &nbsp;·&nbsp; Arte</div>
    <div class="header-inner">
        <a href="index.php" class="logo">
            <span class="logo-name">Ver<em>nissage</em></span>
            <span class="logo-tagline">Portal de Cultura &amp; Arte</span>
        </a>
        <nav>
            <a href="index.php">Início</a>
            <a href="nova_noticia.php" class="btn-nav">+ Publicar</a>
            <a href="editar_usuario.php">Minha Conta</a>
            <a href="logout.php">Sair</a>
        </nav>
    </div>
</header>

<main class="container">
    <div class="page-header">
        <h1 class="page-title">Olá, <em><?= limpar($user['nome']) ?></em></h1>
        <a href="nova_noticia.php" class="btn btn-accent">+ Nova publicação</a>
    </div>

    <div class="section-label">Minhas Publicações (<?= count($minhas) ?>)</div>

    <?php if (empty($minhas)): ?>
        <div class="empty">
            <h3>Nenhuma publicação ainda.</h3>
            <p>Compartilhe seu olhar sobre o mundo cultural.</p>
            <a href="nova_noticia.php" class="btn btn-primary" style="margin-top:1.5rem">Publicar agora</a>
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
                            <a href="noticia.php?id=<?= $n['id'] ?>" class="btn btn-ghost" style="padding:.3rem .9rem;font-size:.65rem">Ver</a>
                            <a href="editar_noticia.php?id=<?= $n['id'] ?>" class="btn btn-secondary" style="padding:.3rem .9rem;font-size:.65rem">Editar</a>
                            <a href="excluir_noticia.php?id=<?= $n['id'] ?>"
                               class="btn btn-danger" style="padding:.3rem .9rem;font-size:.65rem"
                               onclick="return confirm('Excluir esta notícia?')">Excluir</a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <div class="account-card">
        <h3>Sua Conta</h3>
        <p style="font-family:var(--sans);font-size:.85rem;color:var(--stone)">
            <?= limpar($user['nome']) ?>
        </p>
        <div style="margin-top:1rem;display:flex;gap:.8rem;flex-wrap:wrap;">
            <a href="editar_usuario.php" class="btn btn-secondary">✏ Editar dados</a>
            <a href="excluir_usuario.php"
               class="btn btn-danger"
               onclick="return confirm('Deseja excluir sua conta permanentemente?')">✕ Excluir conta</a>
        </div>
    </div>
</main>

<footer>
    <div class="footer-inner">
        <span class="footer-logo">Ver<em>nissage</em></span>
        <p>&copy; <?= date('Y') ?> Vernissage</p>
    </div>
</footer>
</body>
</html>
