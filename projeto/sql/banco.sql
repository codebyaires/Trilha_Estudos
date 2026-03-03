-- ============================================================
-- EAD SENAI — Script do Banco de Dados
-- Disciplina : Desenvolvimento Web | Aula 6
-- Banco      : ead_senai
-- ============================================================

CREATE DATABASE IF NOT EXISTS ead_senai
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE trilha_estudos;

-- ============================================================
-- TABELAS
-- ============================================================

-- ------------------------------------------------------------
-- usuarios
-- Campos do formulário : nome, email, senha
-- Campo interno        : tipo  →  $_SESSION["usuario_tipo"]
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS usuarios (
    id        INT          NOT NULL AUTO_INCREMENT,
    nome      VARCHAR(150) NOT NULL,
    email     VARCHAR(150) NOT NULL,
    senha     VARCHAR(255) NOT NULL,
    tipo      ENUM('aluno','admin') NOT NULL DEFAULT 'aluno',
    criado_em TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- cursos
-- Campos do formulário : titulo, descricao, capa, ativo
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS cursos (
    id        INT          NOT NULL AUTO_INCREMENT,
    titulo    VARCHAR(150) NOT NULL,
    descricao TEXT         NOT NULL,
    capa      VARCHAR(255)     NULL,
    ativo     TINYINT(1)   NOT NULL DEFAULT 1,
    criado_em TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- modulos
-- Campos do formulário : curso_id, titulo, descricao, ordem
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS modulos (
    id        INT          NOT NULL AUTO_INCREMENT,
    curso_id  INT          NOT NULL,
    titulo    VARCHAR(150) NOT NULL,
    descricao TEXT             NULL,
    ordem     INT          NOT NULL DEFAULT 1,
    PRIMARY KEY (id),
    FOREIGN KEY (curso_id) REFERENCES cursos(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- aulas
-- Campos do formulário : modulo_id, titulo, video_url,
--                        duracao, descricao, ordem
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS aulas (
    id        INT          NOT NULL AUTO_INCREMENT,
    modulo_id INT          NOT NULL,
    titulo    VARCHAR(150) NOT NULL,
    video_url VARCHAR(500)     NULL,
    duracao   VARCHAR(10)      NULL,
    descricao TEXT             NULL,
    ordem     INT          NOT NULL DEFAULT 1,
    PRIMARY KEY (id),
    FOREIGN KEY (modulo_id) REFERENCES modulos(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- inscricoes
-- Inserida via inscricao.php quando o aluno clica em Inscrever
-- Campos de uso        : usuario_id, curso_id
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS inscricoes (
    id         INT       NOT NULL AUTO_INCREMENT,
    usuario_id INT       NOT NULL,
    curso_id   INT       NOT NULL,
    criado_em  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_inscricao (usuario_id, curso_id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (curso_id)   REFERENCES cursos(id)   ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

