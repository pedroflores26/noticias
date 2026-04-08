<?php
session_start();
require_once 'conexao.php';
require_once 'funcoes.php';

$noticias = $pdo->query("
    SELECT n.id, n.titulo, n.noticia, n.data, n.imagem, u.nome AS autor_nome
    FROM noticias n INNER JOIN usuarios u ON u.id=n.autor
    ORDER BY n.data DESC
")->fetchAll();

$pageTitle = 'Portal de Cultura & Arte';
include '_header.php';
?>

<section class="hero">
    <div class="hero-kicker">Edição Cultural</div>
    <h1>Arte, Cultura &amp; <em>Pensamento</em></h1>
    <div class="hero-rule"></div>
    <p class="hero-desc">Exposições, festivais, literatura e tudo que move o universo cultural.</p>
    <div id="weather-widget" class="weather">
        <span style="opacity:.5;font-size:.7rem;font-family:var(--sans)">Carregando clima...</span>
    </div>
</section>

<main class="wrap">
    <?php if (empty($noticias)): ?>
        <div class="empty">
            <h3>Nenhuma notícia publicada ainda.</h3>
            <p>Seja o primeiro a escrever para o Vernissage.</p>
            <a href="<?= logado() ? 'nova_noticia.php' : 'cadastro.php' ?>"
               class="btn btn-accent" style="margin-top:1.5rem">
                <?= logado() ? 'Publicar Agora' : 'Criar Conta' ?>
            </a>
        </div>
    <?php else: ?>
        <div class="sec-label">Últimas Publicações</div>
        <div class="news-grid">
            <?php foreach ($noticias as $n): ?>
            <article class="card">
                <div class="card-thumb">
                    <?php if ($n['imagem'] && file_exists('imagens/'.$n['imagem'])): ?>
                        <img src="imagens/<?= limpar($n['imagem']) ?>" alt="<?= limpar($n['titulo']) ?>">
                    <?php else: ?>
                        <div class="art-placeholder">
                            <span class="icon">◈</span>
                            <span>Vernissage</span>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <div class="card-meta">
                        <span class="cat">Cultura &amp; Arte</span>
                        <span class="dot">·</span>
                        <span><?= dataFmt($n['data']) ?></span>
                    </div>
                    <h2><?= limpar($n['titulo']) ?></h2>
                    <p><?= limpar(resumo($n['noticia'])) ?></p>
                </div>
                <div class="card-foot">
                    <span class="card-author">✍ <?= limpar($n['autor_nome']) ?></span>
                    <a href="noticia.php?id=<?= $n['id'] ?>" class="btn btn-primary btn-sm">Ler →</a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<script src="css/weather.js"></script>
<?php include '_footer.php'; ?>
