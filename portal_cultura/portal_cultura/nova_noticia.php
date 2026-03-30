<?php
// nova_noticia.php
require_once 'verifica_login.php';
require_once 'conexao.php';

$user = usuarioLogado();
$erro = $ok = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo  = trim($_POST['titulo']  ?? '');
    $noticia = trim($_POST['noticia'] ?? '');
    $imagem  = null;

    if (!$titulo || !$noticia) {
        $erro = 'Título e conteúdo são obrigatórios.';
    } else {
        // Upload de imagem (opcional)
        if (!empty($_FILES['imagem']['name'])) {
            $ext   = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
            $allow = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            if (!in_array($ext, $allow)) {
                $erro = 'Formato de imagem não permitido. Use JPG, PNG, WEBP ou GIF.';
            } elseif ($_FILES['imagem']['size'] > 3 * 1024 * 1024) {
                $erro = 'A imagem deve ter no máximo 3 MB.';
            } else {
                $imagem = uniqid('img_', true) . '.' . $ext;
                move_uploaded_file($_FILES['imagem']['tmp_name'], 'imagens/' . $imagem);
            }
        }

        if (!$erro) {
            $stmt = $pdo->prepare("
                INSERT INTO noticias (titulo, noticia, autor, imagem)
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([$titulo, $noticia, $user['id'], $imagem]);
            redirecionar('dashboard.php');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nova Notícia — Cultura &amp; Arte</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <div class="header-inner">
        <a href="index.php" class="logo">Cultura <span>&amp;</span> Arte</a>
        <nav>
            <a href="dashboard.php">← Painel</a>
            <a href="logout.php">Sair</a>
        </nav>
    </div>
</header>

<main>
    <div class="form-box" style="max-width:700px">
        <h2>📝 Nova Notícia</h2>

        <?php if ($erro): ?>
            <div class="msg msg-erro"><?= limpar($erro) ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="titulo">Título</label>
                <input type="text" id="titulo" name="titulo"
                       value="<?= limpar($_POST['titulo'] ?? '') ?>"
                       placeholder="Título da notícia" required>
            </div>
            <div class="form-group">
                <label for="noticia">Conteúdo</label>
                <textarea id="noticia" name="noticia" rows="10"
                          placeholder="Escreva a notícia aqui..." required><?= limpar($_POST['noticia'] ?? '') ?></textarea>
            </div>
            <div class="form-group">
                <label for="imagem">Imagem (opcional, máx. 3 MB)</label>
                <input type="file" id="imagem" name="imagem" accept="image/*">
            </div>
            <div style="display:flex;gap:.8rem;flex-wrap:wrap;margin-top:.5rem">
                <button type="submit" class="btn btn-primary">Publicar</button>
                <a href="dashboard.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</main>

<footer>
    <p>&copy; <?= date('Y') ?> <span>Cultura &amp; Arte</span></p>
</footer>

</body>
</html>
