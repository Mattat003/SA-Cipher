CREATE DATABASE Bd_Cipher;
USE Bd_Cipher;

CREATE TABLE cargo (
    pk_cargo INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    nivel_cargo VARCHAR(20) NOT NULL
);

CREATE TABLE adm (
    pk_adm INT AUTO_INCREMENT PRIMARY KEY,
    nome_adm VARCHAR(100) NOT NULL,
    senha VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    pk_cargo INT NOT NULL,
    FOREIGN KEY (pk_cargo) REFERENCES cargo(pk_cargo)
);

CREATE TABLE genero (
    pk_genero INT AUTO_INCREMENT PRIMARY KEY,
    nome_gen VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE usuario (
    pk_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(100) NOT NULL,
    nickname VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE codigo_jogo (
    pk_codigo INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE jogo (
    pk_jogo INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    data_lanc DATE NOT NULL,
    desenvolvedora VARCHAR(100) NOT NULL,
    publicadora VARCHAR(100) NOT NULL,
    informa TEXT,
    plataforma VARCHAR(50) NOT NULL,
    tema VARCHAR(50),
    estilo VARCHAR(50),
    idgenero INT NOT NULL,
    idUsuario INT NOT NULL,
    idCoding_Jogo INT NOT NULL,
    FOREIGN KEY (idgenero) REFERENCES genero(pk_genero),
    FOREIGN KEY (idUsuario) REFERENCES usuario(pk_usuario),
    FOREIGN KEY (idCoding_Jogo) REFERENCES codigo_jogo(pk_codigo)
);

INSERT INTO cargo (nome, nivel_cargo) VALUES ('Administrador Geral', 'Alto');
INSERT INTO cargo (nome, nivel_cargo) VALUES ('Moderador', 'Médio');
INSERT INTO cargo (nome, nivel_cargo) VALUES ('Suporte Técnico', 'Médio');
INSERT INTO cargo (nome, nivel_cargo) VALUES ('Desenvolvedor', 'Alto');
INSERT INTO cargo (nome, nivel_cargo) VALUES ('Analista de Sistemas', 'Alto');
INSERT INTO cargo (nome, nivel_cargo) VALUES ('Designer', 'Médio');
INSERT INTO cargo (nome, nivel_cargo) VALUES ('Gerente de Comunidade', 'Médio');
INSERT INTO cargo (nome, nivel_cargo) VALUES ('Tester', 'Baixo');
INSERT INTO cargo (nome, nivel_cargo) VALUES ('Analista de Dados', 'Médio');
INSERT INTO cargo (nome, nivel_cargo) VALUES ('Especialista em Segurança', 'Alto');
INSERT INTO cargo (nome, nivel_cargo) VALUES ('Assistente Administrativo', 'Baixo');
INSERT INTO cargo (nome, nivel_cargo) VALUES ('Coordenador de Projetos', 'Alto');
INSERT INTO cargo (nome, nivel_cargo) VALUES ('Redator', 'Baixo');
INSERT INTO cargo (nome, nivel_cargo) VALUES ('Tradutor', 'Baixo');
INSERT INTO cargo (nome, nivel_cargo) VALUES ('Especialista em Marketing', 'Médio');
INSERT INTO cargo (nome, nivel_cargo) VALUES ('Analista Financeiro', 'Médio');
INSERT INTO cargo (nome, nivel_cargo) VALUES ('Recrutador', 'Médio');
INSERT INTO cargo (nome, nivel_cargo) VALUES ('Especialista em UX', 'Médio');
INSERT INTO cargo (nome, nivel_cargo) VALUES ('Arquiteto de Software', 'Alto');
INSERT INTO cargo (nome, nivel_cargo) VALUES ('Consultor', 'Alto');

INSERT INTO genero (nome_gen) VALUES ('Ação');
INSERT INTO genero (nome_gen) VALUES ('Aventura');
INSERT INTO genero (nome_gen) VALUES ('RPG');
INSERT INTO genero (nome_gen) VALUES ('Estratégia');
INSERT INTO genero (nome_gen) VALUES ('Simulação');
INSERT INTO genero (nome_gen) VALUES ('Esportes');
INSERT INTO genero (nome_gen) VALUES ('Corrida');
INSERT INTO genero (nome_gen) VALUES ('Luta');
INSERT INTO genero (nome_gen) VALUES ('Tiro em Primeira Pessoa');
INSERT INTO genero (nome_gen) VALUES ('Tiro em Terceira Pessoa');
INSERT INTO genero (nome_gen) VALUES ('MMORPG');
INSERT INTO genero (nome_gen) VALUES ('MOBA');
INSERT INTO genero (nome_gen) VALUES ('Puzzle');
INSERT INTO genero (nome_gen) VALUES ('Horror');
INSERT INTO genero (nome_gen) VALUES ('Sobrevivência');
INSERT INTO genero (nome_gen) VALUES ('Sandbox');
INSERT INTO genero (nome_gen) VALUES ('Plataforma');
INSERT INTO genero (nome_gen) VALUES ('Roguelike');
INSERT INTO genero (nome_gen) VALUES ('Rítmico');
INSERT INTO genero (nome_gen) VALUES ('Educacional');

INSERT INTO usuario (nome, email, senha, nickname) VALUES ('João Silva', 'joao.silva@email.com', 'senha123', 'JSilva');
INSERT INTO usuario (nome, email, senha, nickname) VALUES ('Maria Oliveira', 'maria.oliveira@email.com', 'mariA123', 'MariO');
INSERT INTO usuario (nome, email, senha, nickname) VALUES ('Carlos Pereira', 'carlos.pereira@email.com', 'carlosP', 'CPereira');
INSERT INTO usuario (nome, email, senha, nickname) VALUES ('Ana Santos', 'ana.santos@email.com', 'anaS456', 'Aninha');
INSERT INTO usuario (nome, email, senha, nickname) VALUES ('Pedro Costa', 'pedro.costa@email.com', 'pedroC', 'PCosta');
INSERT INTO usuario (nome, email, senha, nickname) VALUES ('Luiza Fernandes', 'luiza.fernandes@email.com', 'luizaF', 'LuFerns');
INSERT INTO usuario (nome, email, senha, nickname) VALUES ('Rafael Souza', 'rafael.souza@email.com', 'rafaS', 'RafaSouza');
INSERT INTO usuario (nome, email, senha, nickname) VALUES ('Fernanda Lima', 'fernanda.lima@email.com', 'ferLima', 'Fefa');
INSERT INTO usuario (nome, email, senha, nickname) VALUES ('Lucas Almeida', 'lucas.almeida@email.com', 'lucasA', 'LukAlmeida');
INSERT INTO usuario (nome, email, senha, nickname) VALUES ('Juliana Rocha', 'juliana.rocha@email.com', 'jujuR', 'JuhRocha');
INSERT INTO usuario (nome, email, senha, nickname) VALUES ('Marcos Vieira', 'marcos.vieira@email.com', 'marcosV', 'MVieira');
INSERT INTO usuario (nome, email, senha, nickname) VALUES ('Patrícia Gomes', 'patricia.gomes@email.com', 'patiG', 'PatGomes');
INSERT INTO usuario (nome, email, senha, nickname) VALUES ('Gustavo Martins', 'gustavo.martins@email.com', 'gustavoM', 'GusMartins');
INSERT INTO usuario (nome, email, senha, nickname) VALUES ('Camila Ribeiro', 'camila.ribeiro@email.com', 'camiR', 'CamiRibeiro');
INSERT INTO usuario (nome, email, senha, nickname) VALUES ('Bruno Carvalho', 'bruno.carvalho@email.com', 'brunoC', 'BrunoCarv');
INSERT INTO usuario (nome, email, senha, nickname) VALUES ('Amanda Ferreira', 'amanda.ferreira@email.com', 'amandaF', 'Mandinha');
INSERT INTO usuario (nome, email, senha, nickname) VALUES ('Diego Cardoso', 'diego.cardoso@email.com', 'diegoC', 'DiegoCard');
INSERT INTO usuario (nome, email, senha, nickname) VALUES ('Tatiane Nunes', 'tatiane.nunes@email.com', 'tatiN', 'TatiNunes');
INSERT INTO usuario (nome, email, senha, nickname) VALUES ('Roberto Mendes', 'roberto.mendes@email.com', 'robertoM', 'Betinho');
INSERT INTO usuario (nome, email, senha, nickname) VALUES ('Vanessa Castro', 'vanessa.castro@email.com', 'vanessaC', 'VaneCastro');

INSERT INTO codigo_jogo (codigo) VALUES ('JOGO-001-ABCD');
INSERT INTO codigo_jogo (codigo) VALUES ('JOGO-002-EFGH');
INSERT INTO codigo_jogo (codigo) VALUES ('JOGO-003-IJKL');
INSERT INTO codigo_jogo (codigo) VALUES ('JOGO-004-MNOP');
INSERT INTO codigo_jogo (codigo) VALUES ('JOGO-005-QRST');
INSERT INTO codigo_jogo (codigo) VALUES ('JOGO-006-UVWX');
INSERT INTO codigo_jogo (codigo) VALUES ('JOGO-007-YZAB');
INSERT INTO codigo_jogo (codigo) VALUES ('JOGO-008-CDEF');
INSERT INTO codigo_jogo (codigo) VALUES ('JOGO-009-GHIJ');
INSERT INTO codigo_jogo (codigo) VALUES ('JOGO-010-KLMN');
INSERT INTO codigo_jogo (codigo) VALUES ('JOGO-011-OPQR');
INSERT INTO codigo_jogo (codigo) VALUES ('JOGO-012-STUV');
INSERT INTO codigo_jogo (codigo) VALUES ('JOGO-013-WXYZ');
INSERT INTO codigo_jogo (codigo) VALUES ('JOGO-014-1234');
INSERT INTO codigo_jogo (codigo) VALUES ('JOGO-015-5678');
INSERT INTO codigo_jogo (codigo) VALUES ('JOGO-016-9012');
INSERT INTO codigo_jogo (codigo) VALUES ('JOGO-017-3456');
INSERT INTO codigo_jogo (codigo) VALUES ('JOGO-018-7890');
INSERT INTO codigo_jogo (codigo) VALUES ('JOGO-019-ABCD');
INSERT INTO codigo_jogo (codigo) VALUES ('JOGO-020-EFGH');

INSERT INTO adm (nome_adm, senha, email, pk_cargo) VALUES ('Admin Master', 'admin123', 'admin@system.com', 1);
INSERT INTO adm (nome_adm, senha, email, pk_cargo) VALUES ('Moderador Principal', 'mod123', 'moderador@system.com', 2);
INSERT INTO adm (nome_adm, senha, email, pk_cargo) VALUES ('Suporte Técnico 1', 'sup123', 'suporte1@system.com', 3);
INSERT INTO adm (nome_adm, senha, email, pk_cargo) VALUES ('Dev Backend', 'dev123', 'devbackend@system.com', 4);
INSERT INTO adm (nome_adm, senha, email, pk_cargo) VALUES ('Analista Chefe', 'ana123', 'analista@system.com', 5);
INSERT INTO adm (nome_adm, senha, email, pk_cargo) VALUES ('Designer UI', 'des123', 'designer@system.com', 6);
INSERT INTO adm (nome_adm, senha, email, pk_cargo) VALUES ('Gerente Comunitário', 'ger123', 'gerente@system.com', 7);
INSERT INTO adm (nome_adm, senha, email, pk_cargo) VALUES ('Tester Principal', 'test123', 'tester@system.com', 8);
INSERT INTO adm (nome_adm, senha, email, pk_cargo) VALUES ('Analista de Dados', 'dados123', 'dados@system.com', 9);
INSERT INTO adm (nome_adm, senha, email, pk_cargo) VALUES ('Especialista em Segurança', 'seg123', 'seguranca@system.com', 10);
INSERT INTO adm (nome_adm, senha, email, pk_cargo) VALUES ('Assistente Admin', 'ass123', 'assistente@system.com', 11);
INSERT INTO adm (nome_adm, senha, email, pk_cargo) VALUES ('Coordenador TI', 'coord123', 'coordenador@system.com', 12);
INSERT INTO adm (nome_adm, senha, email, pk_cargo) VALUES ('Redator Chefe', 'red123', 'redator@system.com', 13);
INSERT INTO adm (nome_adm, senha, email, pk_cargo) VALUES ('Tradutor Global', 'trad123', 'tradutor@system.com', 14);
INSERT INTO adm (nome_adm, senha, email, pk_cargo) VALUES ('Marketing Digital', 'mark123', 'marketing@system.com', 15);
INSERT INTO adm (nome_adm, senha, email, pk_cargo) VALUES ('Financeiro', 'fin123', 'financeiro@system.com', 16);
INSERT INTO adm (nome_adm, senha, email, pk_cargo) VALUES ('Recrutador TI', 'rec123', 'recrutador@system.com', 17);
INSERT INTO adm (nome_adm, senha, email, pk_cargo) VALUES ('UX Designer', 'ux123', 'ux@system.com', 18);
INSERT INTO adm (nome_adm, senha, email, pk_cargo) VALUES ('Arquiteto Cloud', 'arq123', 'arquiteto@system.com', 19);
INSERT INTO adm (nome_adm, senha, email, pk_cargo) VALUES ('Consultor Sênior', 'cons123', 'consultor@system.com', 20);

INSERT INTO jogo (nome, data_lanc, desenvolvedora, publicadora, informa, plataforma, tema, estilo, idgenero, idUsuario, idCoding_Jogo) VALUES ('Aventura Espacial', '2023-01-15', 'SpaceDev', 'GalaxyPub', 'Jogo de aventura no espaço', 'PC', 'Ficção Científica', 'Ação-Aventura', 2, 1, 1);
INSERT INTO jogo (nome, data_lanc, desenvolvedora, publicadora, informa, plataforma, tema, estilo, idgenero, idUsuario, idCoding_Jogo) VALUES ('Herói Medieval', '2023-03-20', 'RPG Studios', 'FantasyPub', 'RPG de mundo aberto medieval', 'PlayStation 5', 'Fantasia', 'RPG', 3, 1, 2);
INSERT INTO jogo (nome, data_lanc, desenvolvedora, publicadora, informa, plataforma, tema, estilo, idgenero, idUsuario, idCoding_Jogo) VALUES ('Corrida Extrema', '2023-05-10', 'SpeedDev', 'RacingPub', 'Jogo de corrida com gráficos realistas', 'Xbox Series X', 'Corrida', 'Simulação', 7, 1, 3);
INSERT INTO jogo (nome, data_lanc, desenvolvedora, publicadora, informa, plataforma, tema, estilo, idgenero, idUsuario, idCoding_Jogo) VALUES ('Mistério na Mansão', '2023-02-28', 'HorrorDev', 'ScaryPub', 'Jogo de terror psicológico', 'PC', 'Horror', 'Sobrevivência', 14, 2, 4);
INSERT INTO jogo (nome, data_lanc, desenvolvedora, publicadora, informa, plataforma, tema, estilo, idgenero, idUsuario, idCoding_Jogo) VALUES ('Futebol Mundial', '2023-04-15', 'SportsDev', 'GlobalPub', 'Simulador de futebol realista', 'PlayStation 5', 'Esportes', 'Simulação', 6, 2, 5);
INSERT INTO jogo (nome, data_lanc, desenvolvedora, publicadora, informa, plataforma, tema, estilo, idgenero, idUsuario, idCoding_Jogo) VALUES ('Batalha Épica', '2023-06-01', 'StrategyDev', 'TacticsPub', 'Jogo de estratégia em tempo real', 'PC', 'Guerra', 'Estratégia', 4, 3, 6);
INSERT INTO jogo (nome, data_lanc, desenvolvedora, publicadora, informa, plataforma, tema, estilo, idgenero, idUsuario, idCoding_Jogo) VALUES ('Arena de Luta', '2023-07-15', 'FightDev', 'CombatPub', 'Jogo de luta com diversos personagens', 'Xbox Series X', 'Luta', 'Competitivo', 8, 3, 7);
INSERT INTO jogo (nome, data_lanc, desenvolvedora, publicadora, informa, plataforma, tema, estilo, idgenero, idUsuario, idCoding_Jogo) VALUES ('Mundo de Areia', '2023-08-20', 'SandboxDev', 'CreativePub', 'Jogo sandbox com construção livre', 'Nintendo Switch', 'Criativo', 'Sandbox', 16, 3, 8);
INSERT INTO jogo (nome, data_lanc, desenvolvedora, publicadora, informa, plataforma, tema, estilo, idgenero, idUsuario, idCoding_Jogo) VALUES ('Ritmo Dançante', '2023-09-10', 'MusicDev', 'RhythmPub', 'Jogo de ritmo com diversas músicas', 'PlayStation 5', 'Música', 'Rítmico', 19, 4, 9);
INSERT INTO jogo (nome, data_lanc, desenvolvedora, publicadora, informa, plataforma, tema, estilo, idgenero, idUsuario, idCoding_Jogo) VALUES ('Aprendizado Divertido', '2023-10-05', 'EduDev', 'SchoolPub', 'Jogo educativo para crianças', 'Nintendo Switch', 'Educação', 'Educacional', 20, 4, 10);
INSERT INTO jogo (nome, data_lanc, desenvolvedora, publicadora, informa, plataforma, tema, estilo, idgenero, idUsuario, idCoding_Jogo) VALUES ('Sobrevivência Zumbi', '2023-11-15', 'HorrorDev', 'ApocalypsePub', 'Sobreviva ao apocalipse zumbi', 'PC', 'Horror', 'Sobrevivência', 14, 5, 11);
INSERT INTO jogo (nome, data_lanc, desenvolvedora, publicadora, informa, plataforma, tema, estilo, idgenero, idUsuario, idCoding_Jogo) VALUES ('FPS Tático', '2023-12-01', 'ShooterDev', 'MilitaryPub', 'Jogo de tiro tático em equipe', 'Xbox Series X', 'Militar', 'Competitivo', 9, 5, 12);
INSERT INTO jogo (nome, data_lanc, desenvolvedora, publicadora, informa, plataforma, tema, estilo, idgenero, idUsuario, idCoding_Jogo) VALUES ('MMO Fantasia', '2024-01-10', 'MMODev', 'OnlinePub', 'MMORPG com mundo vasto', 'PC', 'Fantasia', 'MMORPG', 11, 5, 13);
INSERT INTO jogo (nome, data_lanc, desenvolvedora, publicadora, informa, plataforma, tema, estilo, idgenero, idUsuario, idCoding_Jogo) VALUES ('Quebra-Cabeça Mágico', '2024-02-15', 'PuzzleDev', 'BrainPub', 'Jogo de puzzle com elementos mágicos', 'Nintendo Switch', 'Fantasia', 'Puzzle', 13, 6, 14);
INSERT INTO jogo (nome, data_lanc, desenvolvedora, publicadora, informa, plataforma, tema, estilo, idgenero, idUsuario, idCoding_Jogo) VALUES ('MOBA Estratégico', '2024-03-01', 'MOBADev', 'EsportsPub', 'Jogo MOBA com diversos heróis', 'PC', 'Fantasia', 'Competitivo', 12, 6, 15);
INSERT INTO jogo (nome, data_lanc, desenvolvedora, publicadora, informa, plataforma, tema, estilo, idgenero, idUsuario, idCoding_Jogo) VALUES ('Simulador de Vida', '2024-04-10', 'LifeDev', 'SimulationPub', 'Simule sua vida virtual', 'PC', 'Simulação', 'Sandbox', 5, 7, 16);
INSERT INTO jogo (nome, data_lanc, desenvolvedora, publicadora, informa, plataforma, tema, estilo, idgenero, idUsuario, idCoding_Jogo) VALUES ('Plataforma Retrô', '2024-05-15', 'RetroDev', 'ClassicPub', 'Jogo de plataforma com estilo retrô', 'Nintendo Switch', 'Retrô', 'Plataforma', 17, 7, 17);
INSERT INTO jogo (nome, data_lanc, desenvolvedora, publicadora, informa, plataforma, tema, estilo, idgenero, idUsuario, idCoding_Jogo) VALUES ('Roguelike Desafiador', '2024-06-01', 'RogueDev', 'HardcorePub', 'Roguelike com alta dificuldade', 'PC', 'Fantasia', 'Roguelike', 18, 7, 18);
INSERT INTO jogo (nome, data_lanc, desenvolvedora, publicadora, informa, plataforma, tema, estilo, idgenero, idUsuario, idCoding_Jogo) VALUES ('Aventura Submarina', '2024-07-10', 'OceanDev', 'DeepPub', 'Explore o fundo do oceano', 'PlayStation 5', 'Aventura', 'Exploração', 2, 8, 19);
INSERT INTO jogo (nome, data_lanc, desenvolvedora, publicadora, informa, plataforma, tema, estilo, idgenero, idUsuario, idCoding_Jogo) VALUES ('Tiro em Terceira Pessoa', '2024-08-15', 'TPSDev', 'ActionPub', 'Jogo de tiro em terceira pessoa', 'Xbox Series X', 'Ação', 'Tiro', 10, 8, 20);

INSERT INTO jogo (nome, data_lanc, desenvolvedora, publicadora, informa, plataforma, tema, estilo, idgenero, idUsuario, idCoding_Jogo) VALUES ('Aventura Espacial', '2023-01-15', 'SpaceDev', 'GalaxyPub', 'Jogo de aventura no espaço', 'PC', 'Ficção Científica', 'Ação-Aventura', 2, 1, 1);
INSERT INTO jogo (nome, data_lanc, desenvolvedora, publicadora, informa, plataforma, tema, estilo, idgenero, idUsuario, idCoding_Jogo) VALUES ('Herói Medieval', '2023-03-20', 'RPG Studios', 'FantasyPub', 'RPG de mundo aberto medieval', 'PlayStation 5', 'Fantasia', 'RPG', 3, 1, 2);
INSERT INTO jogo (nome, data_lanc, desenvolvedora, publicadora, informa, plataforma, tema, estilo, idgenero, idUsuario, idCoding_Jogo) VALUES ('Corrida Extrema', '2023-05-10', 'SpeedDev', 'RacingPub', 'Jogo de corrida com gráficos realistas', 'Xbox Series X', 'Corrida', 'Simulação', 7, 1, 3);
INSERT INTO jogo (nome, data_lanc, desenvolvedora, publicadora, informa, plataforma, tema, estilo, idgenero, idUsuario, idCoding_Jogo) VALUES ('Mistério na Mansão', '2023-02-28', 'HorrorDev', 'ScaryPub', 'Jogo de terror psicológico', 'PC', 'Horror', 'Sobrevivência', 14, 2, 4);
INSERT INTO jogo (nome, data_lanc, desenvolvedora, publicadora, informa, plataforma, tema, estilo, idgenero, idUsuario, idCoding_Jogo) VALUES ('Futebol Mundial', '2023-04-15', 'SportsDev', 'GlobalPub', 'Simulador de futebol realista', 'PlayStation 5', 'Esportes', 'Simulação', 6, 2, 5);
INSERT INTO jogo (nome, data_lanc, desenvolvedora, publicadora, informa, plataforma, tema, estilo, idgenero, idUsuario, idCoding_Jogo) VALUES ('Batalha Épica', '2023-06-01', 'StrategyDev', 'TacticsPub', 'Jogo de estratégia em tempo real', 'PC', 'Guerra', 'Estratégia', 4, 3, 6);
INSERT INTO jogo (nome, data_lanc, desenvolvedora, publicadora, informa, plataforma, tema, estilo, idgenero, idUsuario, idCoding_Jogo) VALUES ('Arena de Luta', '2023-07-15', 'FightDev', 'CombatPub', 'Jogo de luta com diversos personagens', 'Xbox Series X', 'Luta', 'Competitivo', 8, 3, 7);
INSERT INTO jogo (nome, data_lanc, desenvolvedora, publicadora, informa, plataforma, tema, estilo, idgenero, idUsuario, idCoding_Jogo) VALUES ('Mundo de Areia', '2023-08-20', 'SandboxDev', 'CreativePub', 'Jogo sandbox com construção livre', 'Nintendo Switch', 'Criativo', 'Sandbox', 16, 3, 8);
INSERT INTO jogo (nome, data_lanc, desenvolvedora, publicadora, informa, plataforma, tema, estilo, idgenero, idUsuario, idCoding_Jogo) VALUES ('Ritmo Dançante', '2023-09-10', 'MusicDev', 'RhythmPub', 'Jogo de ritmo com diversas músicas', 'PlayStation 5', 'Música', 'Rítmico', 19, 4, 9);
INSERT INTO jogo (nome, data_lanc, desenvolvedora, publicadora, informa, plataforma, tema, estilo, idgenero, idUsuario, idCoding_Jogo) VALUES ('Aprendizado Divertido', '2023-10-05', 'EduDev', 'SchoolPub', 'Jogo educativo para crianças', 'Nintendo Switch', 'Educação', 'Educacional', 20, 4, 10);
INSERT INTO jogo (nome, data_lanc, desenvolvedora, publicadora, informa, plataforma, tema, estilo, idgenero, idUsuario, idCoding_Jogo) VALUES ('Sobrevivência Zumbi', '2023-11-15', 'HorrorDev', 'ApocalypsePub', 'Sobreviva ao apocalipse zumbi', 'PC', 'Horror', 'Sobrevivência', 14, 5, 11);
INSERT INTO jogo (nome, data_lanc, desenvolvedora, publicadora, informa, plataforma, tema, estilo, idgenero, idUsuario, idCoding_Jogo) VALUES ('FPS Tático', '2023-12-01', 'ShooterDev', 'MilitaryPub', 'Jogo de tiro tático em equipe', 'Xbox Series X', 'Militar', 'Competitivo', 9, 5, 12);
INSERT INTO jogo (nome, data_lanc, desenvolvedora, publicadora, informa, plataforma, tema, estilo, idgenero, idUsuario, idCoding_Jogo) VALUES ('MMO Fantasia', '2024-01-10', 'MMODev', 'OnlinePub', 'MMORPG com mundo vasto', 'PC', 'Fantasia', 'MMORPG', 11, 5, 13);
INSERT INTO jogo (nome, data_lanc, desenvolvedora, publicadora, informa, plataforma, tema, estilo, idgenero, idUsuario, idCoding_Jogo) VALUES ('Quebra-Cabeça Mágico', '2024-02-15', 'PuzzleDev', 'BrainPub', 'Jogo de puzzle com elementos mágicos', 'Nintendo Switch', 'Fantasia', 'Puzzle', 13, 6, 14);
INSERT INTO jogo (nome, data_lanc, desenvolvedora, publicadora, informa, plataforma, tema, estilo, idgenero, idUsuario, idCoding_Jogo) VALUES ('MOBA Estratégico', '2024-03-01', 'MOBADev', 'EsportsPub', 'Jogo MOBA com diversos heróis', 'PC', 'Fantasia', 'Competitivo', 12, 6, 15);
INSERT INTO jogo (nome, data_lanc, desenvolvedora, publicadora, informa, plataforma, tema, estilo, idgenero, idUsuario, idCoding_Jogo) VALUES ('Simulador de Vida', '2024-04-10', 'LifeDev', 'SimulationPub', 'Simule sua vida virtual', 'PC', 'Simulação', 'Sandbox', 5, 7, 16);
INSERT INTO jogo (nome, data_lanc, desenvolvedora, publicadora, informa, plataforma, tema, estilo, idgenero, idUsuario, idCoding_Jogo) VALUES ('Plataforma Retrô', '2024-05-15', 'RetroDev', 'ClassicPub', 'Jogo de plataforma com estilo retrô', 'Nintendo Switch', 'Retrô', 'Plataforma', 17, 7, 17);
INSERT INTO jogo (nome, data_lanc, desenvolvedora, publicadora, informa, plataforma, tema, estilo, idgenero, idUsuario, idCoding_Jogo) VALUES ('Roguelike Desafiador', '2024-06-01', 'RogueDev', 'HardcorePub', 'Roguelike com alta dificuldade', 'PC', 'Fantasia', 'Roguelike', 18, 7, 18);
INSERT INTO jogo (nome, data_lanc, desenvolvedora, publicadora, informa, plataforma, tema, estilo, idgenero, idUsuario, idCoding_Jogo) VALUES ('Aventura Submarina', '2024-07-10', 'OceanDev', 'DeepPub', 'Explore o fundo do oceano', 'PlayStation 5', 'Aventura', 'Exploração', 2, 8, 19);
INSERT INTO jogo (nome, data_lanc, desenvolvedora, publicadora, informa, plataforma, tema, estilo, idgenero, idUsuario, idCoding_Jogo) VALUES ('Tiro em Terceira Pessoa', '2024-08-15', 'TPSDev', 'ActionPub', 'Jogo de tiro em terceira pessoa', 'Xbox Series X', 'Ação', 'Tiro', 10, 8, 20);

select * from adm;

select * from cargo;

select * from codigo_jogo;

select * from genero;

select * from jogo;

select * from usuario;

