# 🎨 Vernissage — Portal de Cultura & Arte

Portal de notícias sobre **Cultura e Arte**, desenvolvido em PHP + MySQL.

---

## 🚀 Instalação (XAMPP)

1. Copie a pasta `portal_cultura` para dentro de `htdocs/`
2. Abra o **phpMyAdmin** → clique em **Importar** → selecione `dump.sql` → **Executar**
3. Acesse: `http://localhost/portal_cultura/`

> Se seu MySQL usar senha, edite `conexao.php` e coloque a senha no campo `$pass`.

---

## 🔑 Usuários de teste (senha: `arte123`)

| Usuário          | E-mail                    | Tipo      |
|------------------|---------------------------|-----------|
| Administrador    | admin@vernissage.com      | **Admin** |
| Marta Oliveira   | marta@vernissage.com      | Reporter  |
| João Renato      | joao@vernissage.com       | Reporter  |

---

## 📁 Estrutura de arquivos

```
portal_cultura/
│
├── 🌐 PÚBLICAS
│   ├── index.php              ← Listagem de notícias + API do tempo
│   ├── noticia.php            ← Leitura individual
│   ├── login.php              ← Login (redireciona por tipo)
│   ├── cadastro.php           ← Cadastro (sempre cria reporter)
│   └── logout.php
│
├── 📰 REPORTER (após login)
│   ├── dashboard_reporter.php ← Painel com suas notícias
│   ├── nova_noticia.php       ← Publicar notícia
│   ├── editar_noticia.php     ← Editar própria notícia
│   ├── excluir_noticia.php    ← Excluir própria notícia
│   ├── editar_usuario.php     ← Editar próprio perfil
│   └── excluir_usuario.php    ← Excluir própria conta
│
├── 🔐 ADMIN (acesso exclusivo)
│   ├── dashboard_admin.php          ← Painel geral + stats
│   ├── admin_novo_usuario.php       ← Criar usuário (admin ou reporter)
│   ├── admin_editar_usuario.php     ← Editar qualquer usuário
│   ├── admin_excluir_usuario.php    ← Excluir qualquer usuário
│   ├── admin_editar_noticia.php     ← Editar qualquer notícia
│   └── admin_excluir_noticia.php    ← Excluir qualquer notícia
│
├── ⚙️ SISTEMA
│   ├── conexao.php            ← Conexão PDO MySQL
│   ├── funcoes.php            ← Funções auxiliares
│   ├── verifica_login.php     ← Middleware: exige login
│   ├── verifica_admin.php     ← Middleware: exige ser admin
│   ├── _header.php            ← Header reutilizável
│   └── _footer.php            ← Footer reutilizável
│
├── css/
│   ├── style.css              ← Estilos (modo claro/escuro)
│   ├── theme.js               ← Toggle de tema
│   └── weather.js             ← API do tempo (Open-Meteo)
│
├── imagens/                   ← Upload das capas
└── dump.sql                   ← Banco de dados completo
```

---

## ✅ Funcionalidades implementadas

- [x] Cadastro, login e logout
- [x] Redirecionamento por tipo (admin → painel admin, reporter → painel reporter)
- [x] API do tempo na página inicial (Open-Meteo, sem chave)
- [x] Modo claro / escuro em todo o site
- [x] CRUD completo de notícias (reporter gerencia as suas)
- [x] CRUD completo de usuários (apenas admin)
- [x] Admin pode editar/excluir qualquer notícia
- [x] Upload de imagem nas notícias
- [x] Senhas com hash bcrypt
- [x] Proteção contra XSS (htmlspecialchars)
- [x] Queries seguras com PDO + prepared statements
- [x] Layout responsivo (mobile-friendly)
