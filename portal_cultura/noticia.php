<?php
session_start();
require_once 'conexao.php';
require_once 'funcoes.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) ir('index.php');

$st = $pdo->prepare("SELECT n.*, u.nome AS autor_nome FROM noticias n INNER JOIN usuarios u ON u.id=n.autor WHERE n.id=?");
$st->execute([$id]);
$n = $st->fetch();
if (!$n) ir('index.php');

$pageTitle = $n['titulo'];
$backLink  = ['index.php', 'Início'];
include '_header.php';
?>

<main class="wrap">
    <article class="article-wrap">
        <div class="article-kicker">Cultura &amp; Arte</div>

        <?php if ($n['imagem'] && file_exists('imagens/'.$n['imagem'])): ?>
            <img class="article-img" src="imagens/<?= limpar($n['imagem']) ?>" alt="<?= limpar($n['titulo']) ?>">
        <?php endif; ?>

        <h1><?= limpar($n['titulo']) ?></h1>

        <div class="article-meta">
            <span>✍ <?= limpar($n['autor_nome']) ?></span>
            <span>◈ <?= dataFmt($n['data']) ?></span>
        </div>

        <div class="article-body">
            <?= nl2br(limpar($n['noticia'])) ?>
        </div>

        <?php
        $u = sessaoUser();
        if (logado() && ($u['id'] == $n['autor'] || ehAdmin())):
            $editLink   = ehAdmin() ? 'admin_editar_noticia.php' : 'editar_noticia.php';
            $deleteLink = ehAdmin() ? 'admin_excluir_noticia.php' : 'excluir_noticia.php';
        ?>
        <div class="article-actions">
            <a href="<?= $editLink ?>?id=<?= $n['id'] ?>" class="btn btn-outline">✏ Editar</a>
            <a href="<?= $deleteLink ?>?id=<?= $n['id'] ?>" class="btn btn-danger"
               onclick="return confirm('Excluir esta notícia?')">✕ Excluir</a>
        </div>
        <?php endif; ?>
    </article>
</main>

<?php include '_footer.php'; ?>
