CREATE DATABASE jogos;
USE jogos;

CREATE TABLE historico_jogos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_jogo VARCHAR(100) NOT NULL,
    hora_entrada DATETIME NOT NULL
);