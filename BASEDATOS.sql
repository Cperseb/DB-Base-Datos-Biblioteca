CREATE TABLE Usuario (
    IdUsuario VARCHAR(6) auto_increment PRIMARY KEY,
    Nombre VARCHAR(50) NOT NULL,
    Dirección VARCHAR(50),
    Teléfono VARCHAR(9),
    Curso INT(1),
    Email VARCHAR(50) unique,
    Clave VARCHAR(8) NOT NULL
);


CREATE TABLE Ejemplar (
    IDEjemplar VARCHAR(4) auto_increment PRIMARY KEY,
    Localización VARCHAR(50),
    Prestado BOOLEAN DEFAULT FALSE
);


CREATE TABLE Revista (
    ISBN VARCHAR(20) NOT NULL PRIMARY KEY,
    Frecuencia VARCHAR(50),
    FOREIGN KEY (ISBN) REFERENCES Documento(ISBN) ON DELETE CASCADE
);


CREATE TABLE Multimedia (
    Soporte VARCHAR(50)
);

INSERT INTO Usuario (Nombre, Dirección, Teléfono, Curso, Email, Clave) VALUES
('Lucía Fernández', 'Calle 45, Madrid', '612345678', 1, 'lucia.fernandez@email.com', 'lucia123'),
('David Martínez', 'Avenida Libertad 12, Barcelona', '623456789', 2, 'david.martinez@email.com', 'david456'),
('Sofía Gómez', 'Plaza Mayor 5, Valencia', '634567890', 3, 'sofia.gomez@email.com', 'sofia789'),
('Javier Ramírez', 'Calle Sol 23, Sevilla', '645678901', 1, 'javier.ramirez@email.com', 'javier10'),
('Elena Torres', 'Paseo del Río 7, Bilbao', '656789012', 2, 'elena.torres@email.com', 'elena202');

INSERT INTO Ejemplar (Localización, Prestado) VALUES
('Biblioteca Central', FALSE),
('Sala de Lectura', TRUE),
('Depósito 1', FALSE),
('Biblioteca Sur', TRUE),
('Sala de Investigación', FALSE);

INSERT INTO Revista (ISBN, Frecuencia) VALUES
('978-3-16-148410-0', 'Semanal'),
('978-1-40-289462-3', 'Mensual'),
('978-0-12-345678-9', 'Bimestral'),
('978-9-87-654321-0', 'Trimestral'),
('978-0-19-852663-6', 'Anual');

INSERT INTO Multimedia (Soporte) VALUES
('CD-ROM'),
('DVD'),
('Blu-ray'),
('USB'),
('Streaming');
