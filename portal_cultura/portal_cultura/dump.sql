-- ============================================
-- Portal de Notícias: Cultura & Arte
-- Banco de dados: portal_cultura
-- ============================================

CREATE DATABASE IF NOT EXISTS portal_cultura
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE portal_cultura;

-- Tabela de usuários
CREATE TABLE IF NOT EXISTS usuarios (
  id       INT AUTO_INCREMENT PRIMARY KEY,
  nome     VARCHAR(100)  NOT NULL,
  email    VARCHAR(150)  NOT NULL UNIQUE,
  senha    VARCHAR(255)  NOT NULL,
  criado_em DATETIME     DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabela de notícias
CREATE TABLE IF NOT EXISTS noticias (
  id        INT AUTO_INCREMENT PRIMARY KEY,
  titulo    VARCHAR(255) NOT NULL,
  noticia   TEXT         NOT NULL,
  data      DATETIME     DEFAULT CURRENT_TIMESTAMP,
  autor     INT          NOT NULL,
  imagem    VARCHAR(255) DEFAULT NULL,
  CONSTRAINT fk_autor FOREIGN KEY (autor) REFERENCES usuarios(id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ============================================
-- Dados de exemplo
-- ============================================

-- Senha padrão: arte123  (bcrypt)
INSERT INTO usuarios (nome, email, senha) VALUES
('Marta Oliveira', 'marta@cultura.com',
 '$2y$10$7QzFMwCluN9cqmqbPSWj6e/YJ2VVnqYjLvXRRifBa5mDjb8hTbJyO'),
('João Renato',    'joao@cultura.com',
 '$2y$10$7QzFMwCluN9cqmqbPSWj6e/YJ2VVnqYjLvXRRifBa5mDjb8hTbJyO');

INSERT INTO noticias (titulo, noticia, autor, imagem) VALUES
('Bienal de São Paulo abre inscrições para artistas emergentes',
 'A Bienal de São Paulo anunciou a abertura de inscrições para artistas emergentes de todo o Brasil. O edital prevê bolsas de até R$ 20.000 e exposição no pavilhão principal. As inscrições vão até o fim do mês e podem ser feitas pelo site oficial da fundação.',
 1, NULL),
('Museu de Arte Moderna recebe acervo inédito de Tarsila do Amaral',
 'O MAM-SP receberá, em caráter temporário, um conjunto de 14 obras inéditas de Tarsila do Amaral provenientes de coleções particulares europeias. A exposição marca o centenário do Modernismo Brasileiro e promete ser um dos eventos mais esperados do ano cultural.',
 2, NULL),
('Festival Internacional de Teatro debate arte e política',
 'O Festival Internacional de Teatro de Curitiba trouxe à tona discussões sobre o papel da arte na política contemporânea. Espetáculos de 18 países diferentes ocuparam os palcos da cidade durante duas semanas repletas de debates, workshops e apresentações gratuitas.',
 1, NULL);
