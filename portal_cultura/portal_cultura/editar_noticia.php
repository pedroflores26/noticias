<?php
// editar_noticia.php
require_once 'verifica_login.php';
require_once 'conexao.php';

$user = usuarioLogado();
$id   = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)
      ?: filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id) redirecionar('dashboard.php');

// Busca e verifica autoria
$stmt = $pdo->prepare("SELECT * FROM noticias WHERE id = ? AND autor = ?");
$stmt->execute([$id, $user['id']]);
$n = $stmt->fetch();
if (!$n) redirecionar('dashboard.php');

$erro = $ok = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo  = trim($_POST['titulo']  ?? '');
    $noticia = trim($_POST['noticia'] ?? '');
    $imagem  = $n['imagem'];

    if (!$titulo || !$noticia) {
        $erro = 'Título e conteúdo são obrigatórios.';
    } else {
        if (!empty($_FILES['imagem']['name'])) {
            $ext   = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
            $allow = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            if (!in_array($ext, $allow)) {
                $erro = 'Formato de imagem não permitido.';
            } elseif ($_FILES['imagem']['size'] > 3 * 1024 * 1024) {
                $erro = 'A imagem deve ter no máximo 3 MB.';
            } else {
                // Remove imagem antiga
                if ($imagem && file_exists('imagens/' . $imagem)) {
                    unlink('imagens/' . $imagem);
                }
                $imagem = uniqid('img_', true) . '.' . $ext;
                move_uploaded_file($_FILES['imagem']['tmp_name'], 'imagens/' . $imagem);
            }
        }

        if (!$erro) {
            $upd = $pdo->prepare("UPDATE noticias SET titulo=?, noticia=?, imagem=? WHERE id=? AND autor=?");
            $upd->execute([$titulo, $noticia, $imagem, $id, $user['id']]);
            $ok = 'Notícia atualizada com sucesso!';
            $n  = array_merge($n, ['titulo' => $titulo, 'noticia' => $noticia, 'imagem' => $imagem]);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Notícia — Cultura &amp; Arte</title>
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
        <h2>✏️ Editar Notícia</h2>

        <?php if ($erro): ?>
            <div class="msg msg-erro"><?= limpar($erro) ?></div>
        <?php elseif ($ok): ?>
            <div class="msg msg-ok"><?= limpar($ok) ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $id ?>">
            <div class="form-group">
                <label for="titulo">Título</label>
                <input type="text" id="titulo" name="titulo"
                       value="<?= limpar($n['titulo']) ?>" required>
            </div>
            <div class="form-group">
                <label for="noticia">Conteúdo</label>
                <textarea id="noticia" name="noticia" rows="10" required><?= limpar($n['noticia']) ?></textarea>
            </div>
            <div class="form-group">
                <label for="imagem">Nova imagem (opcional, substitui a atual)</label>
                <?php if ($n['imagem'] && file_exists('imagens/' . $n['imagem'])): ?>
                    <img src="imagens/<?= limpar($n['imagem']) ?>" alt="Imagem atual"
                         style="max-height:150px;margin-bottom:.6rem;border-radius:4px;border:var(--borda)">
                <?php endif; ?>
                <input type="file" id="imagem" name="imagem" accept="image/*">
            </div>
            <div style="display:flex;gap:.8rem;flex-wrap:wrap;margin-top:.5rem">
                <button type="submit" class="btn btn-primary">Salvar alterações</button>
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
