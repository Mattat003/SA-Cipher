create database bd_cypher2;
use bd_cypher2;

/*tabelas principais*/
create table usuario(
pk_usuario int auto_increment primary key,
nome_user varchar(40) not null,
email_user varchar(40) not null,
senha_user varchar(16) not null,
data_criacao datetime,
senha_temporaria BOOLEAN DEFAULT FALSE,
foto_perfil VARCHAR(255) NULL
);

create table cargo(
pk_cargo int auto_increment primary key,
nome_cargo varchar(50) not null,
nivel_cargo int not null
);

create table adm(
pk_adm int auto_increment primary key,
nome_adm varchar(40) not null,
email_adm varchar(40) not null,
senha_user varchar(16) not null,
fk_cargo int not null,
foreign key (fk_cargo) references cargo(pk_cargo)
on delete restrict 
on update cascade
);

create table codigo_game(
pk_codgame int auto_increment primary key,
codigo varchar(29)
);



CREATE TABLE amigos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    amigo_id INT,
    FOREIGN KEY (usuario_id) REFERENCES usuario(pk_usuario),
    FOREIGN KEY (amigo_id) REFERENCES usuario(pk_usuario)
);
CREATE TABLE mensagens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    de_id INT,
    para_id INT,
    mensagem TEXT NOT NULL,
    data_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (de_id) REFERENCES usuario(pk_usuario),
    FOREIGN KEY (para_id) REFERENCES usuario(pk_usuario)
);
	CREATE TABLE pedidos_amizade (
		id INT AUTO_INCREMENT PRIMARY KEY,
		de_id INT NOT NULL,          -- usuário que enviou o pedido
		para_id INT NOT NULL,        -- usuário que receberá o pedido
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


CREATE TABLE jogo (
    pk_jogo INT AUTO_INCREMENT PRIMARY KEY,
    nome_jogo VARCHAR(100) NOT NULL,
    data_lanc DATE NOT NULL,
    fk_codigo INT NOT NULL,
    FOREIGN KEY (fk_codigo) REFERENCES codigo_game(pk_codgame)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,
    desenvolvedora VARCHAR(150)
);

CREATE TABLE biblioteca_usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    nome_jogo VARCHAR(255) NOT NULL,
    imagem_jogo VARCHAR(255),
    url_jogo VARCHAR(255),
    UNIQUE KEY (usuario_id, nome_jogo),
    FOREIGN KEY (usuario_id) REFERENCES usuario(pk_usuario)
);
CREATE TABLE compras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    cartao VARCHAR(20) NOT NULL,      
    data_validade VARCHAR(5) NOT NULL,
    data_compra DATETIME DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE funcionario (
    pk_funcionario INT AUTO_INCREMENT PRIMARY KEY,
    nome_func VARCHAR(40) NOT NULL,
    email_func VARCHAR(40) NOT NULL,
    senha_func VARCHAR(40) NOT NULL,
    fk_cargo INT NOT NULL,
    FOREIGN KEY (fk_cargo) REFERENCES cargo(pk_cargo)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);

/*Tabela de Categorias*/
create table genero(
pk_genero int auto_increment primary key,
nome_gen varchar(50) not null
);

create table tema(
pk_tema int auto_increment primary key,
nome_tema varchar(50) not null
);

create table estilo(
pk_estilo int auto_increment primary key,
nome_estilo varchar(50) not null
);

create table plataforma(
pk_plataforma int auto_increment primary key,
nome_plat varchar(50) not null
);

create table idioma(
pk_idioma int auto_increment primary key,
nome_idioma varchar(50) not null
);

create table modo(
pk_modo int auto_increment primary key,
nome_modo varchar(50) not null
);
/*=================*/

/*ADD Generos*/
insert genero values
(1,'Ação'),
(2,'Aventura'),
(3,'Battle Royale'),
(4,'Cartas/TCG (Trading Card Game)'),
(5,'Corrida'),
(6,'Educacional'),
(7,'Esporte'),
(8,'Estratégia'),
(9,'Furtivo (Stealth)'),
(10,'Hack and Slash'),
(11,'Horror/Terror'),
(12,'Indie'),
(13,'Luta'),
(14,'Metroidvania'),
(15,'MMO'),
(16,'Mundo Aberto'),
(17,'Musical/Ritmo'),
(18,'Plataforma'),
(19,'Puzzle/Quebra-cabeça'),
(20,'RPG'),
(21,'Roguelike/Roguelite'),
(22,'Sandbox'),
(23,'Simulação'),
(24,'Survival'),
(25,'Tiro (FPS/TPS)'),
(26,'Visual Novel'),
(27,'Co-op Local'),
(28,'Co-op Online'),
(29,'LAN'),
(30,'Multijogador Online'),
(31,'PvE (Player vs Enviroment)'),
(32,'PvP (Player vc Player)'),
(33,'Singleplayer');
select * from genero;
/*=================*/

/*ADD Idiomas*/
insert idioma values
(1,'Alemão'),
(2,'Chiês (Simplificado/Tradicional)'),
(3,'Coreano'),
(4,'Espanhol'),
(5,'Francês'),
(6,'Inglês'),
(7,'Italiano'),
(8,'Japonês'),
(9,'Português'),
(10,'Russo');
select * from idioma;
/*=================*/

/*ADD Temas*/
insert tema values
(1,'Apocalipse'),
(2,'Cyberpunk'),
(3,'Fantasia'),
(4,'Faroeste'),
(5,'Horror/Sobrenatural'),
(6,'Magia'),
(7,'Romance'),
(8,'Mundo Pós-apocalíptico'),
(9,'Piratas'),
(10,'Terror Psicológico');
select * from tema;
/*=================*/

/*ADD Plataformas*/
insert plataforma values
(1,'Windows'),
(2,'Linux'),
(3,'MacOS'),
(4,'PlayStation 4|5'),
(5,'Xbox Series X|S'),
(6,'Nintendo Switch');
select * from plataforma;
/*=================*/

/*ADD Modos*/
insert modo values
(1,'Co-op Local'),
(2,'Co-op Online'),
(3,'LAN'),
(4,'Multijogador Online'),
(5,'PvE (Player vs Enviroment)'),
(6,'PvP (Player vc Player)'),
(7,'Singleplayer');
select * from modo;
/*=================*/

/*ADD Estilos*/
insert estilo values
(1,'Mundo Aberto'),
(2,'Point and Click'),
(3,'Roguelike'),
(4,'Sandbox'),
(5,'Crafting');
select * from estilo;
/*=================*/

/*ADD Cargos*/
insert cargo values
(1,'Administrador Geral', 5),
(2,'funcionário', 1);
select * from cargo;
/*=================*/

/*ADD Cód. de Jogo*/
insert codigo_game values
(1,'ABCD1-EFGH2-IJKL3-MNOP4-QRST5'),
(2,'ZXCV6-ASDF7-QWER8-TYUI9-GHJK0'),
(3,'LMNO1-PQRS2-TUVW3-XYZA4-BCDE5'),
(4,'F1G2H-3J4K5-L6M7N-8O9P0-Q1R2S'),
(5,'1234A-5678B-9CDEF-GHIJK-LMNOP'),
(6,'QAZ12-WSX34-EDC56-RFV78-TGB90');
select * from codigo_game;
/*=================*/

/*ADD Jogos*/
INSERT INTO jogo (pk_jogo, nome_jogo, data_lanc, fk_codigo)
VALUES
(1,'The Last of Us Part II','2020-06-19',1),
(2,'Elden Ring','2022-02-25',2),
(3,'Mario Odyssey','2017-10-27',3),
(4,'Enigma do Medo','2024-11-28',4),
(5,'Blue Prince','2025-04-10',5),
(6,'Assassin''s Creed Valhalla','2020-11-10',6);
/*=================*/

/*ADD ADMs*/
INSERT INTO adm VALUES
(1,'Matheus Leal','matheus@email.com','12345678',1),
(2,'Endryo Bittencourt','endryo@email.com','12345678',1),
(3,'Pamella Rafaeli','pamella@email.com','12345678',2),
(4,'Neon Gustavo','neon@email.com','12345678',2),
(5,'Amanda de Oliveira','amanda@email.com','12345678',2);


/*=================*/

/*ADD Usuários*/
insert usuario values
(1,'Lúcio Andrade','lucio@email.com','12345678','2024-03-15',0,''),
(2,'Maya Costa','maya@email.com','12345678','2024-07-02',0,''),
(3,'Henrique Vasques','henrique@email.com','12345678','2024-11-28',0,''),
(4,'Júlia Monteiro','julia@email.com','12345678','2025-01-10',0,''),
(5,'Caio Silveira','caio@email.com','12345678','2025-04-05',0,'');
select * from usuario;

ALTER TABLE usuario MODIFY senha_user VARCHAR(255) NOT NULL;
ALTER TABLE adm MODIFY senha_user VARCHAR(255) NOT NULL;
INSERT INTO usuario (nome_user, email_user, senha_user, data_criacao) VALUES ('Pamella Rafaeli', 'pamella@email.com', '123', NOW());
ALTER TABLE usuario ADD COLUMN senha_temporaria BOOLEAN DEFAULT FALSE;
DESCRIBE usuario;

-- Verifique as colunas da tabela historico_jogos
DESCRIBE historico_jogos;

ALTER TABLE usuario ADD COLUMN foto_perfil VARCHAR(255) NULL AFTER senha_temporaria;
ALTER TABLE funcionario MODIFY senha_func VARCHAR(255) NOT NULL;

SELECT * FROM adm WHERE email_adm = 'pamella_rafaeli@estudante.sesisenai.org.';
UPDATE adm SET email_adm = 'matheus@admin.com' WHERE pk_adm = 1;
UPDATE adm SET email_adm = 'pamella@admin.com' WHERE pk_adm = 3;
UPDATE adm SET fk_cargo = 1 WHERE pk_adm = 3;
-- Corrija os campos de senha para aceitar hash
ALTER TABLE usuario MODIFY senha_user VARCHAR(255) NOT NULL;
ALTER TABLE funcionario MODIFY senha_func VARCHAR(255) NOT NULL;
ALTER TABLE adm MODIFY senha_user VARCHAR(255) NOT NULL;
ALTER TABLE usuario
ADD COLUMN perfil ENUM('adm', 'funcionario', 'cliente') NOT NULL DEFAULT 'cliente';
ALTER TABLE usuario MODIFY perfil ENUM('adm', 'funcionario') NOT NULL DEFAULT 'funcionario';
ALTER TABLE compras ADD COLUMN jogo_id INT NOT NULL;
ALTER TABLE compras ADD CONSTRAINT fk_jogo_id FOREIGN KEY (jogo_id) REFERENCES jogo(pk_jogo)
    ON DELETE RESTRICT
    ON UPDATE CASCADE;

/*=================*/
DESCRIBE funcionario;

/*Ver TUDO*/
select * from biblioteca_usuario;
select * from compras;
select * from funcionario;
select * from cargo;
select * from estilo;
select * from genero;
select * from idioma;
select * from jogo;
describe jogo;
select * from modo;
select * from plataforma;
select * from publicadora;
select * from tema;
select * from usuario;
select * from mensagens;
select * from historico_jogos;
/*=================*