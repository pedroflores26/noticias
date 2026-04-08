-- ============================================
-- Vernissage — Portal de Cultura & Arte (VERSÃO AJUSTADA)
-- ============================================

DROP DATABASE IF EXISTS portal_cultura;
CREATE DATABASE portal_cultura
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE portal_cultura;

-- ============================================
-- TABELA DE USUÁRIOS
-- ============================================

CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  senha VARCHAR(255) NOT NULL,
  tipo ENUM('admin','reporter') NOT NULL DEFAULT 'reporter',
  criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================
-- TABELA DE NOTÍCIAS
-- ============================================

CREATE TABLE noticias (
  id INT AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(255) NOT NULL,
  noticia TEXT NOT NULL,
  data DATETIME DEFAULT CURRENT_TIMESTAMP,
  autor INT NOT NULL,
  imagem VARCHAR(255),
  FOREIGN KEY (autor) REFERENCES usuarios(id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ============================================
-- USUÁRIOS (LOGIN GARANTIDO)
-- SENHA: 123456
-- ============================================

INSERT INTO usuarios (nome, email, senha, tipo) VALUES
('Administrador', 'admin@admin.com',
'$2y$10$wH1h6Z5n9k0k9VQ7QW3K1uJrC0gZ2QWQkFZyJ4YcH0kKjZl9W9Y6K', 'admin'),

('Reporter 1', 'rep1@teste.com',
'$2y$10$wH1h6Z5n9k0k9VQ7QW3K1uJrC0gZ2QWQkFZyJ4YcH0kKjZl9W9Y6K', 'reporter'),

('Reporter 2', 'rep2@teste.com',
'$2y$10$wH1h6Z5n9k0k9VQ7QW3K1uJrC0gZ2QWQkFZyJ4YcH0kKjZl9W9Y6K', 'reporter');

-- ============================================
-- NOTÍCIAS
-- ============================================

INSERT INTO noticias (titulo, noticia, autor) VALUES
('Portal Vernissage lançado com sucesso',
 'O portal de cultura Vernissage foi lançado com sistema completo de login, cadastro e gerenciamento de notícias.',
 1),

('Reporter já pode publicar notícias',
 'Os reporters cadastrados agora podem inserir conteúdos diretamente no sistema.',
 2);