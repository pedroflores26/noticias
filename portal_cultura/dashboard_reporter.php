<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'funcoes.php';
require_once 'conexao.php';

if (!logado()) {
    ir('login.php');
}

// Admin não acessa painel de reporter
if (ehAdmin()) {
    ir('dashboard_admin.php');
}

$u = sessaoUser();

$st = $pdo->prepare("SELECT id,titulo,data FROM noticias WHERE autor=? ORDER BY data DESC");
$st->execute([$u['id']]);
$minhas = $st->fetchAll();

$pageTitle = 'Painel do Reporter';
include '_header.php';
?>

<main class="wrap">
    <div class="dash-header">
        <h1 class="dash-title">Olá, <em><?= limpar($u['nome']) ?></em></h1>
        <a href="nova_noticia.php" class="btn btn-accent">+ Nova publicação</a>
    </div>

    <div class="sec-label">Minhas Publicações (<?= count($minhas) ?>)</div>

    <?php if (empty($minhas)): ?>
        <div class="empty">
            <h3>Nenhuma publicação ainda.</h3>
            <p>Compartilhe seu olhar sobre o mundo cultural.</p>
            <a href="nova_noticia.php" class="btn btn-accent" style="margin-top:1.5rem">Publicar agora</a>
        </div>
    <?php else: ?>
        <div class="tbl-wrap">
            <table>
                <thead>
                    <tr><th>#</th><th>Título</th><th>Data</th><th>Ações</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($minhas as $i => $n): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= limpar($n['titulo']) ?></td>
                        <td><?= dataFmt($n['data']) ?></td>
                        <td>
                            <div class="row-actions">
                                <a href="noticia.php?id=<?= $n['id'] ?>" class="btn btn-ghost btn-sm">Ver</a>
                                <a href="editar_noticia.php?id=<?= $n['id'] ?>" class="btn btn-outline btn-sm">Editar</a>
                                <a href="excluir_noticia.php?id=<?= $n['id'] ?>" class="btn btn-danger btn-sm"
                                   onclick="return confirm('Excluir esta notícia?')">Excluir</a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <div class="acct-card">
        <h3>Minha Conta</h3>
        <p style="font-family:var(--sans);font-size:.82rem;color:var(--txt-3);margin-bottom:1rem">
            <?= limpar($u['nome']) ?> &nbsp;·&nbsp; Reporter
        </p>
        <div style="display:flex;gap:.7rem;flex-wrap:wrap">
            <a href="editar_usuario.php" class="btn btn-outline btn-sm">✏ Editar perfil</a>
            <a href="excluir_usuario.php" class="btn btn-danger btn-sm"
               onclick="return confirm('Excluir sua conta permanentemente?')">✕ Excluir conta</a>
        </div>
    </div>
</main>

<?php include '_footer.php'; ?>