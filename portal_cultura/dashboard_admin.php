<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'funcoes.php';
require_once 'conexao.php';

// 🔒 só admin entra
if (!logado() || !ehAdmin()) {
    ir('login.php');
}

$u = sessaoUser();

// 👥 usuários pendentes
$st = $pdo->query("SELECT id, nome, email FROM usuarios WHERE status='pendente' ORDER BY id DESC");
$pendentes = $st->fetchAll();

$pageTitle = 'Painel do Admin';
include '_header.php';
?>

<main class="wrap">

    <div class="dash-header">
        <h1 class="dash-title">Olá, <em><?= limpar($u['nome']) ?></em></h1>
    </div>

    <!-- 🔔 USUÁRIOS PENDENTES -->
    <div class="sec-label">Usuários pendentes (<?= count($pendentes) ?>)</div>

    <?php if (empty($pendentes)): ?>
        <div class="empty">
            <h3>Nenhum usuário pendente.</h3>
            <p>Todos já foram aprovados.</p>
        </div>
    <?php else: ?>
        <div class="tbl-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pendentes as $i => $p): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= limpar($p['nome']) ?></td>
                        <td><?= limpar($p['email']) ?></td>
                        <td>
                            <a href="ativar_usuario.php?id=<?= $p['id'] ?>" 
                               class="btn btn-accent btn-sm">
                               ✔ Ativar
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

</main>

<?php include '_footer.php'; ?>