<?php
if (session_status() === PHP_SESSION_NONE) 
  
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle) ?> — Vernissage</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="css/theme.js"></script>
</head>
<body>

<header>
    <div class="header-band">
        <b>Vernissage</b> &nbsp;·&nbsp; Cultura &nbsp;·&nbsp; Arte &nbsp;·&nbsp; Pensamento
    </div>
    <div class="header-inner">
        <a href="index.php" class="logo">
            <span class="logo-name">Ver<em>nissage</em></span>
            <span class="logo-sub">Portal de Cultura &amp; Arte</span>
        </a>
        <nav>
            <?php if (isset($backLink)): ?>
                <a href="<?= $backLink[0] ?>">← <?= $backLink[1] ?></a>
            <?php else: ?>
                <a href="index.php">Início</a>
            <?php endif; ?>

            <?php if (logado()): ?>
                <?php if (ehAdmin()): ?>
                    <a href="dashboard_admin.php">Painel <span class="badge">Admin</span></a>
                <?php else: ?>
                    <a href="dashboard_reporter.php">Painel</a>
                <?php endif; ?>
                <a href="nova_noticia.php" class="nav-cta">+ Publicar</a>
                <a href="logout.php">Sair</a>
            <?php else: ?>
                <a href="login.php">Entrar</a>
                <a href="cadastro.php" class="nav-cta">Cadastrar</a>
            <?php endif; ?>
            <button class="theme-btn" id="theme-toggle" title="Alternar tema">🌙</button>
        </nav>
    </div>
</header>
