CREATE DATABASE IF NOT EXISTS Biblioteca;
USE Biblioteca;

-- TABLA USUARIOS

CREATE TABLE Usuarios (
    IdUsuario INT AUTO_INCREMENT PRIMARY KEY,
    Nombre VARCHAR(50) NOT NULL,
    Direccion VARCHAR(50),
    Telefono VARCHAR(9),
    Curso INT(1)not null,
    Email VARCHAR(50) UNIQUE,
    Clave VARCHAR(8) NOT NULL,
    Admin BOOLEAN DEFAULT FALSE
);

-- TABLA DOCUMENTOS

CREATE TABLE Documentos (
    Titulo VARCHAR(50) PRIMARY KEY,
    ISBN VARCHAR(20) UNIQUE,
    ListaAutores VARCHAR(100),
    FechaPublicacion DATE,
    NumEjemplares INT(2),
    Descripcion VARCHAR(100),
    Materia VARCHAR(25)
);

-- TABLA LIBROS

CREATE TABLE Libros (
    ISBN varchar(20)unique,
    numeroPagina int(5),
    Titulo VARCHAR(50) PRIMARY KEY,
    FOREIGN KEY (Titulo) REFERENCES Documentos(Titulo) ON DELETE CASCADE
);

-- TABLA REVISTAS

CREATE TABLE Revistas (
    ISBN varchar(20)unique,
    frecuencia ENUM('diario', 'semanal', 'mensual', 'anual') NOT NULL,
    Titulo VARCHAR(50) PRIMARY KEY,
    FOREIGN KEY (Titulo) REFERENCES Documentos(Titulo) ON DELETE CASCADE
);


-- TABLA MULTIMEDIA

CREATE TABLE Multimedia (
    Titulo VARCHAR(50) PRIMARY KEY,
    Soporte VARCHAR(50),
    FOREIGN KEY (Titulo) REFERENCES Documentos(Titulo) ON DELETE CASCADE
);

-- TABLA EJEMPLARES

CREATE TABLE Ejemplares (
    IdEjemplar INT AUTO_INCREMENT PRIMARY KEY,
    Titulo VARCHAR(50),
    Localizacion VARCHAR(50),
    Prestado BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (Titulo) REFERENCES Documentos(Titulo) ON DELETE CASCADE
);

-- TABLA PRESTAMOS

CREATE TABLE Prestamos (
    IdPrestamo INT AUTO_INCREMENT PRIMARY KEY,
    IdUsuario INT,
    IdEjemplar INT,
    FechaInicio DATE,
    FechaFin DATE,
    Observacion TEXT,
    estado boolean default false,
    FOREIGN KEY (IdUsuario) REFERENCES Usuarios(IdUsuario),
    FOREIGN KEY (IdEjemplar) REFERENCES Ejemplares(IdEjemplar)
);

-- INSERCCIÓN DE DATOS

INSERT INTO Usuarios (Nombre, Direccion, Telefono, Curso, Email, Clave, Admin) VALUES
('Lucía Fernández', 'Calle 45, Madrid', '612345678', 1, 'lucia.fernandez@email.com', 'lucia123', FALSE),
('David Martínez', 'Avenida Libertad 12, Barcelona', '623456789', 2, 'david.martinez@email.com', 'david456', FALSE),
('Sofía Gómez', 'Plaza Mayor 5, Valencia', '634567890', 3, 'sofia.gomez@email.com', 'sofia789', FALSE),
('Javier Ramírez', 'Calle Sol 23, Sevilla', '645678901', 1, 'javier.ramirez@email.com', 'javier10', FALSE),
('Elena Torres', 'Paseo del Río 7, Bilbao', '656789012', 2, 'elena.torres@email.com', 'elena202', FALSE),
('Admin 1', 'N/A', 'N/A', 0, 'admin1@email.com', 'admin1', TRUE),
('Admin 2', 'N/A', 'N/A', 0, 'admin2@email.com', 'admin2', TRUE);

INSERT INTO Documentos (Titulo, ISBN, ListaAutores, FechaPublicacion, NumEjemplares, Descripcion, Materia) VALUES
('El Quijote', '123456789', 'Miguel de Cervantes', '1605-01-01', 5, 'Novela clásica', 'Literatura'),-- 📚 Libros
('Cien años de soledad', '987654321', 'Gabriel García Márquez', '1967-06-05', 3, 'Realismo mágico', 'Literatura'),
('1984', '112233445', 'George Orwell', '1949-06-08', 4, 'Distopía política', 'Ciencia Ficción'),
('Moby Dick', '556677889', 'Herman Melville', '1851-10-18', 2, 'Aventura marítima', 'Clásicos'),
('Don Juan Tenorio', '998877665', 'José Zorrilla', '1844-03-28', 3, 'Teatro romántico', 'Dramaturgia'),
('National Geographic', '978-3-16-148410-0', 'Varios', '2023-01-01', 10, 'Revista de divulgación', 'Ciencia'),-- 📰 Revistas
('Time', '978-1-40-289462-3', 'Varios', '2023-01-15', 5, 'Revista de noticias', 'Actualidad'),
('Science', '978-0-12-345678-9', 'Varios', '2023-02-01', 8, 'Revista científica', 'Ciencia'),
('Hola', '978-9-87-654321-0', 'Varios', '2023-02-15', 12, 'Revista del corazón', 'Entretenimiento'),
('The Economist', '978-0-19-852663-6', 'Varios', '2023-03-01', 7, 'Revista de economía', 'Economía'),
('El Señor de los Anillos', NULL, 'J.R.R. Tolkien', '1954-07-29', 10, 'Aventura épica', 'Fantasía'),-- 🎥 Multimedia (antes estaban ausentes)
('Interestelar', NULL, 'Christopher Nolan', '2014-11-07', 15, 'Ciencia ficción', 'Ciencia Ficción'),
('Origen', NULL, 'Christopher Nolan', '2010-07-16', 12, 'Ciencia ficción', 'Ciencia Ficción'),
('La La Land', NULL, 'Damien Chazelle', '2016-12-09', 10, 'Musical', 'Musical'),
('Coco', NULL, 'Lee Unkrich', '2017-11-22', 15, 'Animación', 'Animación'),
('Los Vengadores', NULL, 'Joss Whedon', '2012-05-04', 20, 'Superhéroes', 'Acción'),
('El Padrino', NULL, 'Francis Ford Coppola', '1972-03-24', 8, 'Drama criminal', 'Drama'),
('Casablanca', NULL, 'Michael Curtiz', '1942-11-26', 5, 'Drama romántico', 'Drama'),
('Ciudadano Kane', NULL, 'Orson Welles', '1941-09-05', 7, 'Drama', 'Drama'),
('Cantando bajo la lluvia', NULL, 'Stanley Donen', '1952-04-11', 9, 'Musical', 'Musical');


INSERT INTO Libros (Titulo, ISBN, numeroPagina) VALUES
('El Quijote', '123456789', 863),
('Cien años de soledad', '987654321', 471),
('1984', '112233445', 328),
('Moby Dick', '556677889', 635),
('Don Juan Tenorio', '998877665', 220);

INSERT INTO Revistas (Titulo, ISBN, Frecuencia) VALUES
('National Geographic', '978-3-16-148410-0', 'semanal'),
('Time', '978-1-40-289462-3', 'mensual'),
('Science', '978-0-12-345678-9', 'mensual'),
('Hola', '978-9-87-654321-0', 'mensual'),
('The Economist', '978-0-19-852663-6', 'anual');

INSERT INTO Multimedia (Titulo, Soporte) VALUES
('El Señor de los Anillos', 'DVD'),
('Interestelar', 'Blu-ray'),
('Origen', 'DVD'),
('La La Land', 'Blu-ray'),
('Coco', 'DVD'),
('Los Vengadores', 'Blu-ray'),
('El Padrino', 'DVD'),
('Casablanca', 'DVD'),
('Ciudadano Kane', 'Blu-ray'),
('Cantando bajo la lluvia', 'DVD');


INSERT INTO Ejemplares (Titulo, Localizacion, Prestado) VALUES
('El Quijote', 'Estante A3', FALSE),
('Cien años de soledad', 'Estante B1', TRUE),
('1984', 'Estante C4', FALSE),
('Moby Dick', 'Estante D2', FALSE),
('Don Juan Tenorio', 'Estante E5', TRUE),
('National Geographic', 'Estante R1', FALSE),
('Time', 'Estante R2', TRUE),
('Science', 'Estante R3', FALSE),
('Hola', 'Estante R4', FALSE),
('The Economist', 'Estante R5', TRUE);

INSERT INTO Prestamos (IdUsuario, IdEjemplar, FechaInicio, FechaFin, Observacion, estado) VALUES
(1, 1, '2024-02-10', '2024-02-24', 'Leves marcas de uso', TRUE), 
(2, 2, '2024-02-15', '2024-03-01', 'En buen estado', TRUE), 
(3, 3, '2024-02-20', '2024-03-10', 'Cuidado con la tapa', TRUE), 
(4, 4, '2024-01-25', '2024-02-10', 'Nuevo préstamo', FALSE), 
(5, 5, '2024-01-30', '2024-02-13', 'Regresado en buen estado', FALSE), 
(1, 6, '2024-02-05', '2024-02-20', 'Sin anotaciones', FALSE), 
(2, 7, '2024-01-15', '2024-02-01', 'Desgaste en esquinas', FALSE), 
(3, 8, '2024-01-10', '2024-01-25', 'Leve rasguño en portada', FALSE);





