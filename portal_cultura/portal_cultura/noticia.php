<?php
// noticia.php – Leitura de notícia individual
session_start();
require_once 'conexao.php';
require_once 'funcoes.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) redirecionar('index.php');

$stmt = $pdo->prepare("
    SELECT n.*, u.nome AS autor_nome
    FROM noticias n
    INNER JOIN usuarios u ON u.id = n.autor
    WHERE n.id = ?
");
$stmt->execute([$id]);
$n = $stmt->fetch();

if (!$n) redirecionar('index.php');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= limpar($n['titulo']) ?> — Cultura &amp; Arte</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <div class="header-inner">
        <a href="index.php" class="logo">Cultura <span>&amp;</span> Arte</a>
        <nav>
            <a href="index.php">← Voltar</a>
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

<main class="container">
    <article class="noticia-full">

        <?php if ($n['imagem'] && file_exists('imagens/' . $n['imagem'])): ?>
            <img class="noticia-img" src="imagens/<?= limpar($n['imagem']) ?>" alt="<?= limpar($n['titulo']) ?>">
        <?php endif; ?>

        <h1><?= limpar($n['titulo']) ?></h1>

        <div class="meta">
            <span>✍️ <?= limpar($n['autor_nome']) ?></span>
            <span>🗓 <?= formatarData($n['data']) ?></span>
        </div>

        <div class="corpo">
            <?= nl2br(limpar($n['noticia'])) ?>
        </div>

        <?php
        $user = usuarioLogado();
        if (estaLogado() && $user['id'] == $n['autor']):
        ?>
        <div style="margin-top:2rem;display:flex;gap:.8rem;flex-wrap:wrap;">
            <a href="editar_noticia.php?id=<?= $n['id'] ?>" class="btn btn-secondary">✏️ Editar</a>
            <a href="excluir_noticia.php?id=<?= $n['id'] ?>"
               class="btn btn-danger"
               onclick="return confirm('Deseja excluir esta notícia?')">🗑 Excluir</a>
        </div>
        <?php endif; ?>

    </article>
</main>

<footer>
    <p>&copy; <?= date('Y') ?> <span>Cultura &amp; Arte</span> — Todos os direitos reservados.</p>
</footer>

</body>
</html>
