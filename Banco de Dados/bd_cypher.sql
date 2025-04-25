create database bd_cypher;
use bd_cypher;

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
nome_cargo varchar(50) not null
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
codigo varchar(15),
descricao_cod varchar(100)
);

create table jogo(
pk_jogo int auto_increment primary key,
nome_jogo varchar(100) not null,
descricao varchar(150) not null,
data_lanc date not null,
dev varchar(100) not null,
pub varchar(100) not null,
fk_codigo int not null,
foreign key (fk_codigo) references codigo_game(pk_codgame)
on delete restrict 
on update cascade
);
/*=================*/


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
nome_dev varchar(50) not null,
descricao_dev varchar(100)
);

create table publicadora(
pk_publi int auto_increment primary key,
nome_publi varchar(50) not null,
descricao_publi varchar(100)
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
(22,'Sandbox');
select * from genero;


