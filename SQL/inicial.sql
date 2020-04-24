-- Atualizado em 23/04/2020

CREATE DATABASE simcona;
USE simcona;

CREATE TABLE Medidor (
id INT AUTO_INCREMENT PRIMARY KEY ,
nome VARCHAR(255),
topico VARCHAR(255),
descricao VARCHAR(512),
fator DECIMAL(4,3)
);

CREATE TABLE Registro (
id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
horario DATETIME(0),
valor DECIMAL(10,3 ),
idMedidor INT(128),
FOREIGN KEY(idMedidor) REFERENCES Medidor (id)
);

CREATE TABLE TipoAnotacao (
idTipo INT PRIMARY KEY,
nome VARCHAR(255)
);

CREATE TABLE Anotacao (
id BIGINT AUTO_INCREMENT PRIMARY KEY,
horaInicio DATETIME,
horaFim DATETIME,
tipoAnotacao INT,
idMedidor INT(128),
FOREIGN KEY(tipoAnotacao) REFERENCES TipoAnotacao (idTipo),
FOREIGN KEY(idMedidor) REFERENCES Medidor (id)
);

CREATE TABLE ModeloAnotacao (
id BIGINT AUTO_INCREMENT PRIMARY KEY,
idAnotacao BIGINT,
diaSemana CHAR(1),
segHora INT(128),
intpt DECIMAL(10,3 ), 
coef DECIMAL(10,3 ),
FOREIGN KEY(idAnotacao) REFERENCES Anotacao (id),

);

CREATE TABLE Usuario (
login VARCHAR(32) PRIMARY KEY,
descricao VARCHAR(255),
senha VARCHAR(128)
);

CREATE TABLE Configuracao (
id INT NOT NULL PRIMARY KEY ,
usuarioBroker VARCHAR(128),
senhaBroker VARCHAR(128),
enderecoBroker VARCHAR(255)
);

CREATE TABLE Alerta (
id BIGINT AUTO_INCREMENT PRIMARY KEY,
textoDescricao VARCHAR(255),
horario DATETIME,
idAnotacao BIGINT,
peso INT(128),
FOREIGN KEY(idAnotacao) REFERENCES Anotacao (id)
);

INSERT INTO Medidor (nome,topico,descricao,fator) VALUES ("Medidor01", "medidor/esp32a", "Predio 01", 1.0);
INSERT INTO Medidor (nome,topico,descricao,fator) VALUES ("Medidor02", "medidor/esp32b", "Predio 02", 1.0);
INSERT INTO Medidor (nome,topico,descricao,fator) VALUES ("Medidor03", "medidor/esp32c", "Predio 03", 1.0);
INSERT INTO Medidor (nome,topico,descricao,fator) VALUES ("Medidor04", "medidor/esp32d", "Predio 04", 1.0);
INSERT INTO Usuario (login, senha) VALUES ("user", "password");
INSERT INTO Configuracao (id, usuarioBroker,senhaBroker, enderecoBroker) VALUES (0, "agua", "1368","127.0.0.1");
INSERT INTO TipoAnotacao (idTipo, nome) VALUES (0, "Vazamento");
INSERT INTO TipoAnotacao (idTipo, nome) VALUES (1, "Baixo Consumo");
 
 
CREATE USER 'aguasql'@'localhost' IDENTIFIED BY 'pass1368';
GRANT SELECT,INSERT,DELETE,UPDATE on simcona.Medidor TO 'aguasql'@'localhost';
GRANT INSERT,SELECT on simcona.Registro TO 'aguasql'@'localhost';
GRANT UPDATE,SELECT on simcona.Configuracao TO 'aguasql'@'localhost';
GRANT SELECT,INSERT,DELETE,UPDATE on simcona.Anotacao TO 'aguasql'@'localhost';
GRANT SELECT,INSERT,DELETE,UPDATE on simcona.TipoAnotacao TO 'aguasql'@'localhost';
GRANT UPDATE,SELECT on simcona.Usuario TO 'aguasql'@'localhost';
GRANT INSERT,SELECT, UPDATE on simcona.Alerta TO 'aguasql'@'localhost';
