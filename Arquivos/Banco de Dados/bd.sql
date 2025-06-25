-- Criar banco e usar
CREATE DATABASE IF NOT EXISTS bd_cypher2;
USE bd_cypher2;

-- Tabelas principais
CREATE TABLE cargo (
    pk_cargo INT AUTO_INCREMENT PRIMARY KEY,
    nome_cargo VARCHAR(50) NOT NULL,
    nivel_cargo INT NOT NULL
);

CREATE TABLE usuario (
    pk_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nome_user VARCHAR(40) NOT NULL,
    email_user VARCHAR(40) NOT NULL UNIQUE,
    senha_user VARCHAR(255) NOT NULL,
    data_criacao DATETIME,
    senha_temporaria BOOLEAN DEFAULT FALSE,
    foto_perfil VARCHAR(255) NULL
);
ALTER TABLE usuario ADD COLUMN perfil ENUM('adm', 'funcionario', 'cliente') NOT NULL DEFAULT 'cliente';

CREATE TABLE adm (
    pk_adm INT AUTO_INCREMENT PRIMARY KEY,
    nome_adm VARCHAR(40) NOT NULL,
    email_adm VARCHAR(40) NOT NULL UNIQUE,
    senha_user VARCHAR(255) NOT NULL,
    fk_cargo INT NOT NULL,
    FOREIGN KEY (fk_cargo) REFERENCES cargo(pk_cargo)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);

CREATE TABLE funcionario (
    pk_funcionario INT AUTO_INCREMENT PRIMARY KEY,
    nome_func VARCHAR(40) NOT NULL,
    email_func VARCHAR(40) NOT NULL UNIQUE,
    senha_func VARCHAR(255) NOT NULL,
    fk_cargo INT NOT NULL,
    FOREIGN KEY (fk_cargo) REFERENCES cargo(pk_cargo)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);

CREATE TABLE codigo_game (
    pk_codgame INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(29) UNIQUE
);

CREATE TABLE jogo (
    pk_jogo INT AUTO_INCREMENT PRIMARY KEY,
    nome_jogo VARCHAR(100) NOT NULL,
    data_lanc DATE NOT NULL,
    desenvolvedora VARCHAR(150),
    disponivel_locacao BOOLEAN DEFAULT TRUE,
    imagem_jogo VARCHAR(255) NULL,
    url_jogo VARCHAR(255) NULL
);


CREATE TABLE biblioteca_usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    jogo_id INT NOT NULL,
    nome_jogo VARCHAR(100) NOT NULL,
    imagem_jogo VARCHAR(255) NULL,
    url_jogo VARCHAR(255) NULL,
    UNIQUE KEY (usuario_id, jogo_id),
    FOREIGN KEY (usuario_id) REFERENCES usuario(pk_usuario),
    FOREIGN KEY (jogo_id) REFERENCES jogo(pk_jogo)
);

CREATE TABLE compras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    cartao VARCHAR(20) NOT NULL,
    data_validade VARCHAR(5) NOT NULL,
    data_compra DATETIME DEFAULT CURRENT_TIMESTAMP,
    jogo_id INT NOT NULL,
    FOREIGN KEY (jogo_id) REFERENCES jogo(pk_jogo)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);

CREATE TABLE locacoes_pendentes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    jogo_id INT NOT NULL,
    data_pedido DATETIME DEFAULT CURRENT_TIMESTAMP,
    data_expiracao DATETIME,
    data_liberacao DATETIME,
    status ENUM('pendente', 'liberado', 'recusado') DEFAULT 'pendente',
    FOREIGN KEY (usuario_id) REFERENCES usuario(pk_usuario),
    FOREIGN KEY (jogo_id) REFERENCES jogo(pk_jogo)
);

CREATE TABLE publicadora (
    pk_publicadora INT AUTO_INCREMENT PRIMARY KEY,
    nome_publicadora VARCHAR(100) NOT NULL
);

-- Tabelas de categorias
CREATE TABLE genero (
    pk_genero INT AUTO_INCREMENT PRIMARY KEY,
    nome_gen VARCHAR(50) NOT NULL
);

CREATE TABLE tema (
    pk_tema INT AUTO_INCREMENT PRIMARY KEY,
    nome_tema VARCHAR(50) NOT NULL
);

CREATE TABLE estilo (
    pk_estilo INT AUTO_INCREMENT PRIMARY KEY,
    nome_estilo VARCHAR(50) NOT NULL
);

CREATE TABLE plataforma (
    pk_plataforma INT AUTO_INCREMENT PRIMARY KEY,
    nome_plat VARCHAR(50) NOT NULL
);

CREATE TABLE idioma (
    pk_idioma INT AUTO_INCREMENT PRIMARY KEY,
    nome_idioma VARCHAR(50) NOT NULL
);

CREATE TABLE modo (
    pk_modo INT AUTO_INCREMENT PRIMARY KEY,
    nome_modo VARCHAR(50) NOT NULL
);

-- Tabela para relacionar jogo a categorias (muitos-para-muitos)
CREATE TABLE jogo_genero (
    jogo_id INT NOT NULL,
    genero_id INT NOT NULL,
    PRIMARY KEY(jogo_id, genero_id),
    FOREIGN KEY (jogo_id) REFERENCES jogo(pk_jogo) ON DELETE CASCADE,
    FOREIGN KEY (genero_id) REFERENCES genero(pk_genero) ON DELETE CASCADE
);

CREATE TABLE jogo_tema (
    jogo_id INT NOT NULL,
    tema_id INT NOT NULL,
    PRIMARY KEY(jogo_id, tema_id),
    FOREIGN KEY (jogo_id) REFERENCES jogo(pk_jogo) ON DELETE CASCADE,
    FOREIGN KEY (tema_id) REFERENCES tema(pk_tema) ON DELETE CASCADE
);

CREATE TABLE jogo_estilo (
    jogo_id INT NOT NULL,
    estilo_id INT NOT NULL,
    PRIMARY KEY(jogo_id, estilo_id),
    FOREIGN KEY (jogo_id) REFERENCES jogo(pk_jogo) ON DELETE CASCADE,
    FOREIGN KEY (estilo_id) REFERENCES estilo(pk_estilo) ON DELETE CASCADE
);

CREATE TABLE jogo_plataforma (
    jogo_id INT NOT NULL,
    plataforma_id INT NOT NULL,
    PRIMARY KEY(jogo_id, plataforma_id),
    FOREIGN KEY (jogo_id) REFERENCES jogo(pk_jogo) ON DELETE CASCADE,
    FOREIGN KEY (plataforma_id) REFERENCES plataforma(pk_plataforma) ON DELETE CASCADE
);

CREATE TABLE jogo_idioma (
    jogo_id INT NOT NULL,
    idioma_id INT NOT NULL,
    PRIMARY KEY(jogo_id, idioma_id),
    FOREIGN KEY (jogo_id) REFERENCES jogo(pk_jogo) ON DELETE CASCADE,
    FOREIGN KEY (idioma_id) REFERENCES idioma(pk_idioma) ON DELETE CASCADE
);

CREATE TABLE jogo_modo (
    jogo_id INT NOT NULL,
    modo_id INT NOT NULL,
    PRIMARY KEY(jogo_id, modo_id),
    FOREIGN KEY (jogo_id) REFERENCES jogo(pk_jogo) ON DELETE CASCADE,
    FOREIGN KEY (modo_id) REFERENCES modo(pk_modo) ON DELETE CASCADE
);

-- Tabelas sociais
CREATE TABLE amigos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    amigo_id INT NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuario(pk_usuario),
    FOREIGN KEY (amigo_id) REFERENCES usuario(pk_usuario)
);

CREATE TABLE mensagens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    de_id INT NOT NULL,
    para_id INT NOT NULL,
    mensagem TEXT NOT NULL,
    data_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (de_id) REFERENCES usuario(pk_usuario),
    FOREIGN KEY (para_id) REFERENCES usuario(pk_usuario)
);

CREATE TABLE pedidos_amizade (
    id INT AUTO_INCREMENT PRIMARY KEY,
    de_id INT NOT NULL,
    para_id INT NOT NULL,
    status ENUM('pendente', 'aceito', 'recusado') NOT NULL DEFAULT 'pendente',
    data_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (de_id) REFERENCES usuario(pk_usuario) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (para_id) REFERENCES usuario(pk_usuario) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE historico_jogos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(100) NOT NULL,
    nome_jogo VARCHAR(100) NOT NULL,
    hora_entrada DATETIME NOT NULL
);

INSERT INTO cargo (pk_cargo, nome_cargo, nivel_cargo) VALUES
(1,'Administrador Geral', 5),
(2,'funcionário', 1);

INSERT INTO genero (pk_genero, nome_gen) VALUES
(1,'Ação'),(2,'Aventura'),(3,'Battle Royale'),(4,'Cartas/TCG (Trading Card Game)'),(5,'Corrida'),
(6,'Educacional'),(7,'Esporte'),(8,'Estratégia'),(9,'Furtivo (Stealth)'),(10,'Hack and Slash'),
(11,'Horror/Terror'),(12,'Indie'),(13,'Luta'),(14,'Metroidvania'),(15,'MMO'),
(16,'Mundo Aberto'),(17,'Musical/Ritmo'),(18,'Plataforma'),(19,'Puzzle/Quebra-cabeça'),(20,'RPG'),
(21,'Roguelike/Roguelite'),(22,'Sandbox'),(23,'Simulação'),(24,'Survival'),(25,'Tiro (FPS/TPS)'),
(26,'Visual Novel'),(27,'Co-op Local'),(28,'Co-op Online'),(29,'LAN'),(30,'Multijogador Online'),
(31,'PvE (Player vs Enviroment)'),(32,'PvP (Player vc Player)'),(33,'Singleplayer');

INSERT INTO idioma (pk_idioma, nome_idioma) VALUES
(1,'Alemão'),(2,'Chiês (Simplificado/Tradicional)'),(3,'Coreano'),(4,'Espanhol'),(5,'Francês'),
(6,'Inglês'),(7,'Italiano'),(8,'Japonês'),(9,'Português'),(10,'Russo');

INSERT INTO tema (pk_tema, nome_tema) VALUES
(1,'Apocalipse'),(2,'Cyberpunk'),(3,'Fantasia'),(4,'Faroeste'),(5,'Horror/Sobrenatural'),
(6,'Magia'),(7,'Romance'),(8,'Mundo Pós-apocalíptico'),(9,'Piratas'),(10,'Terror Psicológico');

INSERT INTO plataforma (pk_plataforma, nome_plat) VALUES
(1,'Windows'),(2,'Linux'),(3,'MacOS'),(4,'PlayStation 4|5'),(5,'Xbox Series X|S'),(6,'Nintendo Switch');

INSERT INTO modo (pk_modo, nome_modo) VALUES
(1,'Co-op Local'),(2,'Co-op Online'),(3,'LAN'),(4,'Multijogador Online'),(5,'PvE (Player vs Enviroment)'),
(6,'PvP (Player vc Player)'),(7,'Singleplayer');

INSERT INTO estilo (pk_estilo, nome_estilo) VALUES
(1,'Mundo Aberto'),(2,'Point and Click'),(3,'Roguelike'),(4,'Sandbox'),(5,'Crafting');

INSERT INTO codigo_game (pk_codgame, codigo) VALUES
(1,'ABCD1-EFGH2-IJKL3-MNOP4-QRST5'),
(2,'ZXCV6-ASDF7-QWER8-TYUI9-GHJK0'),
(3,'LMNO1-PQRS2-TUVW3-XYZA4-BCDE5'),
(4,'F1G2H-3J4K5-L6M7N-8O9P0-Q1R2S'),
(5,'1234A-5678B-9CDEF-GHIJK-LMNOP'),
(6,'QAZ12-WSX34-EDC56-RFV78-TGB90');

INSERT INTO jogo (pk_jogo, nome_jogo, data_lanc, fk_codigo, desenvolvedora, imagem_jogo) VALUES
(1,'The Last of Us Part II','2020-06-19',1,'Naughty Dog','uploads/jogo_685aebf609de3.jpg'),
(2,'Elden Ring','2022-02-25',2,'FromSoftware','uploads/jogo_685ae7e5a55a9.jpg'),
(3,'Mario Odyssey','2017-10-27',3,'Nintendo','uploads/jogo_685ae82644148.jpg'),
(4,'Enigma do Medo','2024-11-28',4,'Independente','uploads/jogo_685adc87c44be.jpg'),
(5,'Blue Prince','2025-04-10',5,'Independente','uploads/jogo_685ae89243103.jpg'),
(6,'Assassin''s Creed Valhalla','2020-11-10',6,'Ubisoft','uploads/jogo_685ae8cc1ba2a.jpg');

INSERT INTO adm (pk_adm, nome_adm, email_adm, senha_user, fk_cargo) VALUES
(1,'Matheus Leal','matheus@admin.com', '12345678',1),
(2,'Endryo Bittencourt','endryo@email.com', '12345678',1),
(3,'Pamella Rafaeli','pamella@admin.com', '12345678',1),
(4,'Neon Gustavo','neon@email.com', '12345678',2),
(5,'Amanda de Oliveira','amanda@email.com', '12345678',2);

INSERT INTO usuario (pk_usuario, nome_user, email_user, senha_user, data_criacao, senha_temporaria, foto_perfil, perfil) VALUES
(1,'Lúcio Andrade','lucio@email.com', '12345678','2024-03-15',0,'','cliente'),
(2,'Maya Costa','maya@email.com', '12345678','2024-07-02',0,'','cliente'),
(3,'Henrique Vasques','henrique@email.com', '12345678','2024-11-28',0,'','cliente'),
(4,'Júlia Monteiro','julia@email.com', '12345678','2025-01-10',0,'','cliente'),
(5,'Caio Silveira','caio@email.com', '12345678','2025-04-05',0,'','cliente');

select * from usuario;
