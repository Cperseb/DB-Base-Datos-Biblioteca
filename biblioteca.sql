CREATE DATABASE IF NOT EXISTS BIblioteca;

USE BIblioteca;

CREATE TABLE Documentos (
    ISBN VARCHAR(9) PRIMARY KEY,
    Titulo VARCHAR(50) NOT NULL,
    ListaAutores VARCHAR(100),
    FechaPublicacion DATE,
    NumPaginas INT(5),
    NumEjemplares INT(2),
    Descripcion VARCHAR(100),
    Materia VARCHAR(25)
);
CREATE TABLE Prestamos (
    IdPrestamo INT PRIMARY KEY AUTO_INCREMENT,
    IdUsuario INT,
    IdEjemplar VARCHAR(4),
    FechaP DATE,
    FechaD DATE,
    Observacion TEXT,
    FOREIGN KEY (IdUsuario) REFERENCES Usuarios(IdUsuario),
    FOREIGN KEY (IdEjemplar) REFERENCES Ejemplares(IdEjemplar)
);
CREATE TABLE Libro(
ISBN VARCHAR(9) PRIMARY KEY,
Titulo VARCHAR(50) NOT NULL,
FOREIGN KEY (ISBN) REFERENCES Documentos(ISBN)
);

CREATE TABLE Usuario(
IdUsuario VARCHAR(10) AUTO_INCREMENT ,
Contraseña VARCHAR(20),
Admin BOOLEAN
);

INSERT INTO Usuario (IdUsuario, Contraseña, Admin) VALUES
('U001', 'password123', FALSE),
('U002', 'admin456', TRUE),
('U003', 'user789', FALSE),
('U004', 'securePass1', TRUE),
('U005', 'test1234', FALSE);


INSERT INTO Documentos (ISBN, Titulo, ListaAutores, FechaPublicacion, NumPaginas, NumEjemplares, Descripcion, Materia) VALUES
('123456789', 'El Quijote', 'Miguel de Cervantes', '1605-01-01', 863, 5, 'Novela clásica', 'Literatura'),
('987654321', 'Cien años de soledad', 'Gabriel García Márquez', '1967-06-05', 471, 3, 'Realismo mágico', 'Literatura'),
('112233445', '1984', 'George Orwell', '1949-06-08', 328, 4, 'Distopía política', 'Ciencia Ficción'),
('556677889', 'Moby Dick', 'Herman Melville', '1851-10-18', 635, 2, 'Aventura marítima', 'Clásicos'),
('998877665', 'Don Juan Tenorio', 'José Zorrilla', '1844-03-28', 220, 3, 'Teatro romántico', 'Dramaturgia');

INSERT INTO Ejemplares (IdEjemplar, ISBN, Localizacion, Prestado) VALUES
('E001', '123456789', 'Estante A3', FALSE),
('E002', '987654321', 'Estante B1', TRUE),
('E003', '112233445', 'Estante C4', FALSE),
('E004', '556677889', 'Estante D2', FALSE),
('E005', '998877665', 'Estante E5', TRUE);

INSERT INTO Prestamos (IdUsuario, IdEjemplar, FechaP, FechaD, Estado, Observacion) VALUES
(1, 'E001', '2024-02-01', '2024-02-15', FALSE, 'En buen estado'),
(2, 'E002', '2024-01-20', '2024-02-10', TRUE, 'Leves marcas de uso'),
(3, 'E003', '2024-02-05', '2024-02-19', FALSE, 'Nuevo préstamo'),
(4, 'E004', '2024-02-07', '2024-02-21', FALSE, 'Ligero desgaste en tapa'),
(5, 'E005', '2024-01-30', '2024-02-13', TRUE, 'Regresado en buen estado');

INSERT INTO Libro (ISBN, Titulo) VALUES
('123456789', 'El Quijote'),
('987654321', 'Cien años de soledad'),
('112233445', '1984'),
('556677889', 'Moby Dick'),
('998877665', 'Don Juan Tenorio');
