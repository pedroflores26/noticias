<?php
// index.php – Página inicial pública
session_start();
require_once 'conexao.php';
require_once 'funcoes.php';

$stmt = $pdo->query("
    SELECT n.id, n.titulo, n.noticia, n.data, n.imagem, u.nome AS autor_nome
    FROM noticias n
    INNER JOIN usuarios u ON u.id = n.autor
    ORDER BY n.data DESC
");
$noticias = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cultura &amp; Arte — Portal de Notícias</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <div class="header-inner">
        <a href="index.php" class="logo">Cultura <span>&amp;</span> Arte</a>
        <nav>
            <a href="index.php">Início</a>
            <?php if (estaLogado()): ?>
                <a href="dashboard.php">Painel</a>
                <a href="nova_noticia.php" class="btn-nav">+ Nova Notícia</a>
                <a href="logout.php">Sair</a>
            <?php else: ?>
                <a href="login.php">Entrar</a>
                <a href="cadastro.php" class="btn-nav">Cadastrar</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<section class="hero">
    <h1>Portal de Cultura &amp; Arte</h1>
    <div class="divider-gold"></div>
    <p>As últimas notícias do mundo cultural, exposições, festivais e muito mais.</p>
</section>

<main class="container">
    <?php if (empty($noticias)): ?>
        <div class="empty">
            <h3>Nenhuma notícia publicada ainda.</h3>
            <p>Seja o primeiro a publicar!</p>
            <?php if (estaLogado()): ?>
                <a href="nova_noticia.php" class="btn btn-primary" style="margin-top:1rem">Publicar Notícia</a>
            <?php else: ?>
                <a href="cadastro.php" class="btn btn-primary" style="margin-top:1rem">Criar Conta</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="grid-noticias">
            <?php foreach ($noticias as $n): ?>
            <article class="card">
                <div class="card-img">
                    <?php if ($n['imagem'] && file_exists('imagens/' . $n['imagem'])): ?>
                        <img src="imagens/<?= limpar($n['imagem']) ?>" alt="<?= limpar($n['titulo']) ?>">
                    <?php else: ?>
                        <span class="placeholder-icon">🎨</span>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <div class="card-meta">
                        <span class="tag">Cultura</span>
                        <span>🗓 <?= formatarData($n['data']) ?></span>
                    </div>
                    <h2><?= limpar($n['titulo']) ?></h2>
                    <p><?= limpar(resumo($n['noticia'])) ?></p>
                </div>
                <div class="card-footer">
                    <small>✍️ <?= limpar($n['autor_nome']) ?></small>
                    <a href="noticia.php?id=<?= $n['id'] ?>" class="btn btn-primary">Ler mais</a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<footer>
    <p>&copy; <?= date('Y') ?> <span>Cultura &amp; Arte</span> — Todos os direitos reservados.</p>
</footer>

</body>
</html>
