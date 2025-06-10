create database bd_cypher2;
use bd_cypher2;

/*tabelas principais*/
create table usuario(
pk_usuario int auto_increment primary key,
nome_user varchar(40) not null,
email_user varchar(40) not null,
senha_user varchar(16) not null,
data_criacao datetime
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
create table jogo(
pk_jogo int auto_increment primary key,
nome_jogo varchar(100) not null,
data_lanc date not null,
fk_codigo int not null,
foreign key (fk_codigo) references codigo_game(pk_codgame)
on delete restrict 
on update cascade,
fk_dev int not null,
foreign key (fk_dev) references desenvolvedora(pk_dev)
on delete restrict 
on update cascade,
fk_pub int not null,
foreign key (fk_pub) references publicadora(pk_publi)
on delete restrict 
on update cascade
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

create table desenvolvedora(
pk_dev int auto_increment primary key,
nome_dev varchar(50) not null
);

create table publicadora(
pk_publi int auto_increment primary key,
nome_publi varchar(50) not null
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

/*ADD Desenvolvedoras*/
insert desenvolvedora values
(1,'Nintedo'),
(2,'Rockstar Games'),
(3,'Valve'),
(4,'Ubisoft'),
(5,'Sony'),
(6,'Naughty Dog'),
(7,'FromSoftware'),
(8,'Dogubomb'),
(9,'ConcernedApe'),
(10,'Dumativa');
select * from desenvolvedora;
/*=================*/

/*ADD Publicadoras*/
insert publicadora values
(1,'Nintedo'),
(2,'Rockstar Games'),
(3,'Valve'),
(4,'Ubisoft'),
(5,'Sony'),
(6,'Eletronic Arts'),
(7,'Bandai Namco'),
(8,'Annapurna Interactive'),
(9,'Raw Fury'),
(10,'Nuuvem');
select * from publicadora;
/*=================*/

/*ADD Cargos*/
insert cargo values
(1,'Administrador Geral',5),
(2,'Gerenciador de Conteúdo',4),
(3,'Moderador',3),
(4,'Analista de Dados',4),
(5,'Suporte Técnico',2);
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
insert jogo values
(1,'The Last of Us Part II','2020-06-19',1,6,5),
(2,'Elden Ring','2022-02-25',2,7,7),
(3,'Mario Odyssey','2017-10-27',3,1,1),
(4,'Enigma do Medo','2024-11-28',4,10,10),
(5,'Blue Prince','2025-04-10',5,8,9),
(6,'Assassin''s Creed Valhalla','2020-11-10',6,4,4);
select * from jogo;
/*=================*/

/*ADD ADMs*/
insert adm values
(1,'Matheus Leal','matheus_fm_leal@estudante.sesisenai.org.br','23553#NG',5),
(2,'Endryo Bittencourt','endryo_bittencourt@estudante.sesisenai.org.br','342#@134',3),
(3,'Pamella Rafaeli','pamella_rafaeli@estudante.sesisenai.org.br','123#3F37',4),
(4,'Neon Gustavo','neon_bruehmueller@estudante.sesisenai.org.br','8538#5342',3),
(5,'Amanda de Oliveira','amanda_oliveira22@estudante.sesisenai.org.br','894375AM',2);
select * from adm;

/*=================*/

/*ADD Usuários*/
insert usuario values
(1,'Lúcio Andrade','lucio.andrade@hotmail.com','L@ndrade2024!','2024-03-15'),
(2,'Maya Costa','maya.costa@gmail.com','Maya#C0st@!','2024-07-02'),
(3,'Henrique Vasques','henrique.vasques@gmail.com','HenV@1234#','2024-11-28'),
(4,'Júlia Monteiro','julia.monteiro@gmail.com','JMonteiro!22','2025-01-10'),
(5,'Caio Silveira','caio.silveira@gmail.com','Caio_S1lv#','2025-04-05');
select * from usuario;

ALTER TABLE usuario MODIFY senha_user VARCHAR(255) NOT NULL;
ALTER TABLE adm MODIFY senha_user VARCHAR(255) NOT NULL;
INSERT INTO usuario (nome_user, email_user, senha_user, data_criacao) VALUES ('Pamella Rafaeli', 'pamella@email.com', '123', NOW());
ALTER TABLE usuario ADD COLUMN senha_temporaria BOOLEAN DEFAULT FALSE;
DESCRIBE usuario;

-- Verifique as colunas da tabela historico_jogos
DESCRIBE historico_jogos;

ALTER TABLE usuario ADD COLUMN foto_perfil VARCHAR(255) NULL AFTER senha_temporaria;

SELECT * FROM adm WHERE email_adm = 'pamella_rafaeli@estudante.sesisenai.org.';
UPDATE adm SET email_adm = 'matheus_fm_leal@estudante.sesisenai.org' WHERE pk_adm = 1;
UPDATE adm SET email_adm = 'pamella_rafaeli@estudante.sesisenai.org' WHERE pk_adm = 3;
UPDATE adm SET fk_cargo = 1 WHERE pk_adm = 3;





/*=================*/

/*Ver TUDO*/
select * from adm;
select * from cargo;
select * from codigo_game;
select * from desenvolvedora;
select * from estilo;
select * from genero;
select * from idioma;
select * from jogo;
select * from modo;
select * from plataforma;
select * from publicadora;
select * from tema;
select * from usuario;
select * from mensagens;
select * from historico_jogos;
/*=================*