# 🎭 Portal Cultura — Sistema de Notícias

Sistema web desenvolvido em **PHP + MySQL** para gerenciamento de notícias culturais, com controle de acesso entre **Administrador** e **Repórteres**.

---

## 🚀 Funcionalidades

### 👑 Administrador

* Aprovar ou rejeitar cadastro de repórteres
* Acessar painel administrativo
* Gerenciar usuários
* Visualizar notícias

### 🧑‍💻 Repórter

* Criar notícias
* Editar notícias próprias
* Excluir notícias próprias
* Editar perfil

---

## 🔐 Sistema de Acesso

* Login com validação de senha criptografada (`password_hash`)
* Controle de sessão via PHP
* Diferenciação de permissões:

  * `admin`
  * `reporter`
* Status de usuário:

  * `ativo`
  * `pendente` (aguardando aprovação)

---

## 🗂️ Estrutura do Projeto

```
portal_cultura/
│
├── index.php
├── login.php
├── cadastro.php
├── logout.php
│
├── dashboard_admin.php
├── dashboard_reporter.php
│
├── nova_noticia.php
├── editar_noticia.php
├── excluir_noticia.php
│
├── editar_usuario.php
├── excluir_usuario.php
│
├── verifica_login.php
├── verifica_admin.php
├── funcoes.php
├── conexao.php
│
├── noticias.php
├── noticia.php
│
├── imagens/
└── dump.sql
```

---

## 🗄️ Banco de Dados

### Tabelas:

* `usuarios`
* `noticias`

### 👤 Usuário padrão (Admin)

```
Email: admin@email.com
Senha: 123456
```

---

## ⚙️ Tecnologias Utilizadas

* PHP (procedural)
* MySQL
* HTML5
* CSS3
* XAMPP (ambiente local)

---

## 🧪 Como Rodar o Projeto

1. Instale o XAMPP
2. Coloque a pasta em:

   ```
   htdocs/
   ```
3. Inicie:

   * Apache
   * MySQL
4. Importe o banco (`dump.sql`) no phpMyAdmin
5. Acesse:

   ```
   http://localhost/portal_cultura
   ```

---

## 🔒 Segurança Implementada

* Uso de `password_hash()` e `password_verify()`
* Proteção de rotas com `verifica_login.php`
* Validação de dados
* Controle de sessão

---


## 👨‍💻 Autor

Projeto desenvolvido para fins acadêmicos.

---

## 📌 Observações

* Apenas usuários aprovados podem acessar o sistema
* Repórteres não podem acessar funções de administrador
* Todas as ações são restritas por sessão

---
