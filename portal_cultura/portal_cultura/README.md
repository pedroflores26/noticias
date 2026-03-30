# 🎨 Portal de Notícias — Cultura & Arte

Portal de notícias temático sobre **Cultura e Arte**, desenvolvido em PHP com MySQL.

## 🎨 Identidade Visual
- Paleta: Vinho · Dourado · Creme · Carvão
- Tipografia: Playfair Display (títulos) + Lato (corpo)
- Estilo editorial sofisticado

## 🗂️ Estrutura de Arquivos

```
portal_cultura/
├── index.php             # Página inicial pública
├── noticia.php           # Leitura de notícia individual
├── login.php             # Formulário de login
├── cadastro.php          # Criação de conta
├── logout.php            # Encerrar sessão
├── dashboard.php         # Painel do usuário logado
├── nova_noticia.php      # Publicar notícia
├── editar_noticia.php    # Editar notícia (apenas autor)
├── excluir_noticia.php   # Excluir notícia (apenas autor)
├── editar_usuario.php    # Editar conta do usuário
├── excluir_usuario.php   # Excluir conta do usuário
├── conexao.php           # Conexão PDO com o banco
├── funcoes.php           # Funções auxiliares
├── verifica_login.php    # Middleware de autenticação
├── css/
│   └── style.css         # Estilos globais
├── imagens/              # Upload de imagens das notícias
└── dump.sql              # Estrutura e dados do banco
```

## ⚙️ Instalação

1. Clone ou copie a pasta para `htdocs/` (XAMPP) ou `www/` (WAMP)
2. Importe o banco no phpMyAdmin:
   ```
   Importar → dump.sql
   ```
3. Edite `conexao.php` se necessário (usuário/senha do MySQL)
4. Acesse `http://localhost/portal_cultura/`

## 🔑 Usuário de teste
- **E-mail:** `marta@cultura.com`
- **Senha:** `arte123`

## 🧩 Funcionalidades
- ✅ Cadastro, login e logout
- ✅ Listagem pública de notícias (mais recentes primeiro)
- ✅ Leitura de notícia individual
- ✅ CRUD completo de notícias (apenas pelo autor)
- ✅ Upload de imagem nas notícias
- ✅ Edição e exclusão de conta
- ✅ Proteção de rotas (verifica_login.php)
- ✅ Senhas com hash bcrypt
- ✅ Prevenção de XSS com htmlspecialchars
- ✅ Queries seguras com PDO + prepared statements
