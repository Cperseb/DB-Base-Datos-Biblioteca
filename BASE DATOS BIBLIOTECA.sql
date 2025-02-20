CREATE DATABASE IF NOT EXISTS Biblioteca;
USE Biblioteca;

-- TABLA USUARIOS

CREATE TABLE Usuarios (
    IdUsuario INT AUTO_INCREMENT PRIMARY KEY,
    Nombre VARCHAR(50) NOT NULL,
    Direccion VARCHAR(50),
    Telefono VARCHAR(9),
    Curso INT(1),
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
    NumPaginas INT(5),
    NumEjemplares INT(2),
    Descripcion VARCHAR(100),
    Materia VARCHAR(25)
);

-- TABLA LIBROS

CREATE TABLE Libros (
    Titulo VARCHAR(50) PRIMARY KEY,
    FOREIGN KEY (Titulo) REFERENCES Documentos(Titulo) ON DELETE CASCADE
);

-- TABLA REVISTAS

CREATE TABLE Revistas (
    Titulo VARCHAR(50) PRIMARY KEY,
    Frecuencia VARCHAR(50),
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
    FechaP DATE,
    FechaD DATE,
    Observacion TEXT,
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
('Admin 1', 'N/A', 'N/A', NULL, 'admin1@email.com', 'admin1', TRUE),
('Admin 2', 'N/A', 'N/A', NULL, 'admin2@email.com', 'admin2', TRUE);

INSERT INTO Documentos (Titulo, ISBN, ListaAutores, FechaPublicacion, NumPaginas, NumEjemplares, Descripcion, Materia) VALUES
('El Quijote', '123456789', 'Miguel de Cervantes', '1605-01-01', 863, 5, 'Novela clásica', 'Literatura'),
('Cien años de soledad', '987654321', 'Gabriel García Márquez', '1967-06-05', 471, 3, 'Realismo mágico', 'Literatura'),
('1984', '112233445', 'George Orwell', '1949-06-08', 328, 4, 'Distopía política', 'Ciencia Ficción'),
('Moby Dick', '556677889', 'Herman Melville', '1851-10-18', 635, 2, 'Aventura marítima', 'Clásicos'),
('Don Juan Tenorio', '998877665', 'José Zorrilla', '1844-03-28', 220, 3, 'Teatro romántico', 'Dramaturgia'),
('National Geographic', '978-3-16-148410-0', 'Varios', '2023-01-01', 100, 10, 'Revista de divulgación', 'Ciencia'),
('Time', '978-1-40-289462-3', 'Varios', '2023-01-15', 80, 5, 'Revista de noticias', 'Actualidad'),
('Science', '978-0-12-345678-9', 'Varios', '2023-02-01', 120, 8, 'Revista científica', 'Ciencia'),
('Hola', '978-9-87-654321-0', 'Varios', '2023-02-15', 60, 12, 'Revista del corazón', 'Entretenimiento'),
('The Economist', '978-0-19-852663-6', 'Varios', '2023-03-01', 90, 7, 'Revista de economía', 'Economía');

INSERT INTO Libros (Titulo) VALUES
('El Quijote'),
('Cien años de soledad'),
('1984'),
('Moby Dick'),
('Don Juan Tenorio');

INSERT INTO Revistas (Titulo, Frecuencia) VALUES
('National Geographic', 'Semanal'),
('Time', 'Mensual'),
('Science', 'Bimestral'),
('Hola', 'Trimestral'),
('The Economist', 'Anual');

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

INSERT INTO Prestamos (IdUsuario, IdEjemplar, FechaP, FechaD, Observacion) VALUES
(1, 1, '2024-02-01', '2024-02-15', 'En buen estado'),
(2, 2, '2024-01-20', '2024-02-10', 'Leves marcas de uso'),
(3, 3, '2024-02-05', '2024-02-19', 'Nuevo préstamo'),
(4, 4, '2024-02-07', '2024-02-21', 'Ligero desgaste en tapa'),
(5, 5, '2024-01-30', '2024-02-13', 'Regresado en buen estado');

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


