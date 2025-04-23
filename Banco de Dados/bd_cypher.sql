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
senha_user varchar(16) not null
fk_cargo int not null,
foreign key (fk_cargo) references cargo(pk_cargo)
on delete restrict 
on update cascade
);

create table jogo(
pk_jogo int auto_increment primary key,
nome_jogo varchar(100) not null,
descricao varchar(150) not null,
data_lanc date not null,
dev varchar(100) not null,
pub varchar(100) not null
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
/*=================*/








