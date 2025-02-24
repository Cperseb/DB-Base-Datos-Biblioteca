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

CREATE TABLE Ejemplares (
    IdEjemplar INT AUTO_INCREMENT PRIMARY KEY,
    Titulo VARCHAR(50),
    Localizacion VARCHAR(50),
    Prestado BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (Titulo) REFERENCES Documentos(Titulo) ON DELETE CASCADE
);


CREATE TABLE Revistas (
    ISBN varchar(20)unique,
    frecuencia ENUM('diario', 'semanal', 'mensual', 'anual') NOT NULL,
    Titulo VARCHAR(50) PRIMARY KEY,
    FOREIGN KEY (Titulo) REFERENCES Documentos(Titulo) ON DELETE CASCADE
);


CREATE TABLE Multimedia (
    Titulo VARCHAR(50) PRIMARY KEY,
    Soporte VARCHAR(50),
    FOREIGN KEY (Titulo) REFERENCES Documentos(Titulo) ON DELETE CASCADE
);

INSERT INTO Usuarios (Nombre, Direccion, Telefono, Curso, Email, Clave, Admin) VALUES
('Lucía Fernández', 'Calle 45, Madrid', '612345678', 1, 'lucia.fernandez@email.com', 'lucia123', FALSE),
('David Martínez', 'Avenida Libertad 12, Barcelona', '623456789', 2, 'david.martinez@email.com', 'david456', FALSE),
('Sofía Gómez', 'Plaza Mayor 5, Valencia', '634567890', 3, 'sofia.gomez@email.com', 'sofia789', FALSE),
('Javier Ramírez', 'Calle Sol 23, Sevilla', '645678901', 1, 'javier.ramirez@email.com', 'javier10', FALSE),
('Elena Torres', 'Paseo del Río 7, Bilbao', '656789012', 2, 'elena.torres@email.com', 'elena202', FALSE),
('Admin 1', 'N/A', 'N/A', 0, 'admin1@email.com', 'admin1', TRUE),
('Admin 2', 'N/A', 'N/A', 0, 'admin2@email.com', 'admin2', TRUE);


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
