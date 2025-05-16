create database empresa;
use empresa;

create table cliente(
pk_cli int primary key auto_increment,
cli_nome varchar(50) not null,
cli_endereco varchar(80) not null,
cli_telefone varchar(20) not null,
cli_email varchar(50) not null
);

create table usuario(
nome varchar(50) default null,
usuario varchar(50) default null,
senha varchar(50) default null,
nivel int default null
);

INSERT INTO cliente (cli_nome, cli_endereco, cli_telefone, cli_email) VALUES 
('João Silva', 'Rua das Flores, 123', '(11) 98765-4321', 'joao.silva@email.com'),
('Maria Oliveira', 'Av. Paulista, 456', '(11) 99876-5432', 'maria.oliveira@email.com'),
('Carlos Pereira', 'Rua 7 de Setembro, 789', '(21) 91234-5678', 'carlos.pereira@email.com'),
('Ana Costa', 'Rua Rio Branco, 101', '(21) 92345-6789', 'ana.costa@email.com'),
('Ricardo Souza', 'Av. Rio de Janeiro, 202', '(31) 93456-7890', 'ricardo.souza@email.com'),
('Fernanda Lima', 'Rua dos Três Irmãos, 303', '(41) 98765-4321', 'fernanda.lima@email.com'),
('Lucas Martins', 'Rua das Pedras, 404', '(51) 99876-5432', 'lucas.martins@email.com'),
('Juliana Rocha', 'Av. Ipanema, 505', '(61) 91234-5678', 'juliana.rocha@email.com'),
('Eduardo Almeida', 'Rua São Jorge, 606', '(71) 92345-6789', 'eduardo.almeida@email.com'),
('Patrícia Costa', 'Rua das Palmeiras, 707', '(81) 93456-7890', 'patricia.costa@email.com');

select * from cliente;









