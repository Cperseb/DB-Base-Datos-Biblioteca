-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 13-03-2025 a las 00:41:50
-- Versión del servidor: 8.0.41-0ubuntu0.24.04.1
-- Versión de PHP: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `Biblioteca`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Documentos`
--

CREATE TABLE `Documentos` (
  `Titulo` varchar(50) NOT NULL,
  `ISBN` varchar(20) DEFAULT NULL,
  `ListaAutores` varchar(100) DEFAULT NULL,
  `FechaPublicacion` date DEFAULT NULL,
  `NumEjemplares` int DEFAULT NULL,
  `Descripcion` varchar(100) DEFAULT NULL,
  `Materia` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `Documentos`
--

INSERT INTO `Documentos` (`Titulo`, `ISBN`, `ListaAutores`, `FechaPublicacion`, `NumEjemplares`, `Descripcion`, `Materia`) VALUES
('Crimen y castigo', '978-0486415871', 'Fiódor Dostoievski', '1866-01-01', 5, 'Psicología y redención', 'Filosofía'),
('Don Quijote de la Mancha', '978-8424114537', 'Miguel de Cervantes', '1605-01-16', 6, 'Caballería y locura en la España medieval', 'Clásicos'),
('Drácula', '978-0486411095', 'Bram Stoker', '1897-05-26', 4, 'Vampiros y horror gótico', 'Terror'),
('El Alquimista', '978-0062315007', 'Paulo Coelho', '1988-04-01', 6, 'Filosofía y búsqueda personal', 'Espiritualidad'),
('El código Da Vinci', '978-0307474278', 'Dan Brown', '2003-03-18', 6, 'Thriller y misterio religioso', 'Suspenso'),
('El Gran Gatsby', '978-0743273565', 'F. Scott Fitzgerald', '1925-04-10', 4, 'Crítica al sueño americano', 'Literatura'),
('El retrato de Dorian Gray', '978-1515190998', 'Oscar Wilde', '1890-07-01', 3, 'Belleza, moralidad y corrupción', 'Literatura'),
('El señor de los anillos', '978-0618640157', 'J.R.R. Tolkien', '1954-07-29', 10, 'Fantasía épica', 'Fantasía'),
('Frankestein', '978-0486282114', 'Mary Shelley', '1818-01-01', 5, 'Ciencia y ética', 'Ciencia Ficción'),
('Harry Potter y la piedra filosofal', '978-8478884452', 'J.K. Rowling', '1997-06-26', 9, 'Magia y aventura en Hogwarts', 'Fantasía'),
('La divina comedia', '978-0142437223', 'Dante Alighieri', '1320-01-01', 7, 'Viaje a través del Infierno y el Paraíso', 'Poesía'),
('La Odisea', '978-0140268867', 'Homero', '1605-03-16', 7, 'Viajes y mitología griega', 'Mitología'),
('Los juegos del hambre', '978-0439023481', 'Suzanne Collins', '2008-09-14', 8, 'Distopía y supervivencia', 'Ciencia Ficción'),
('Los miserables', '978-0140444308', 'Victor Hugo', '1862-04-03', 4, 'Injusticia y revolución en Francia', 'Historia'),
('Matar a un ruiseñor', '978-0061120084', 'Harper Lee', '1960-07-11', 8, 'Racismo y justicia en el sur de EE.UU.', 'Literatura'),
('Orgullo y prejuicio', '978-1503290563', 'Jane Austen', '1813-01-28', 6, 'Novela romántica y de costumbres', 'Romance'),
('Sherlock Holmes: Estudio en escarlata', '978-8491050613', 'Arthur Conan Doyle', '1887-11-01', 5, 'Detectives y misterio', 'Misterio');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Ejemplares`
--

CREATE TABLE `Ejemplares` (
  `IdEjemplar` int NOT NULL,
  `Titulo` varchar(50) DEFAULT NULL,
  `Localizacion` varchar(50) DEFAULT NULL,
  `Prestado` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `Ejemplares`
--

INSERT INTO `Ejemplares` (`IdEjemplar`, `Titulo`, `Localizacion`, `Prestado`) VALUES
(13, 'El Gran Gatsby', 'Estante A3', 0),
(14, 'Orgullo y prejuicio', 'Estante A4', 0),
(15, 'Matar a un ruiseñor', 'Estante A5', 1),
(16, 'Crimen y castigo', 'Estante B1', 0),
(17, 'El retrato de Dorian Gray', 'Estante B2', 1),
(18, 'Los miserables', 'Estante B3', 1),
(19, 'Don Quijote de la Mancha', 'Estante B4', 1),
(20, 'La Odisea', 'Estante B5', 1),
(21, 'El señor de los anillos', 'Estante C1', 1),
(22, 'Harry Potter y la piedra filosofal', 'Estante C2', 1),
(23, 'El código Da Vinci', 'Estante C3', 1),
(24, 'Los juegos del hambre', 'Estante C4', 0),
(25, 'Sherlock Holmes: Estudio en escarlata', 'Estante C5', 1),
(26, 'La divina comedia', 'Estante D1', 0),
(27, 'El Alquimista', 'Estante D2', 0),
(28, 'Drácula', 'Estante D3', 1),
(29, 'Frankestein', 'Estante D4', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Libros`
--

CREATE TABLE `Libros` (
  `ISBN` varchar(20) DEFAULT NULL,
  `numeroPagina` int DEFAULT NULL,
  `Titulo` varchar(50) NOT NULL,
  `CASA` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `Libros`
--

INSERT INTO `Libros` (`ISBN`, `numeroPagina`, `Titulo`, `CASA`) VALUES
('978-0486415871', 671, 'Crimen y castigo', 0),
('978-8424114537', 1072, 'Don Quijote de la Mancha', 0),
('978-0486411095', 418, 'Drácula', 0),
('978-0062315007', 208, 'El Alquimista', 0),
('978-0307474278', 454, 'El código Da Vinci', 0),
('978-0743273565', 180, 'El Gran Gatsby', 0),
('978-1515190998', 254, 'El retrato de Dorian Gray', 0),
('978-0618640157', 1216, 'El señor de los anillos', 0),
('978-0486282114', 280, 'Frankestein', 0),
('978-8478884452', 223, 'Harry Potter y la piedra filosofal', 0),
('978-0142437223', 798, 'La divina comedia', 0),
('978-0140268867', 541, 'La Odisea', 0),
('978-0439023481', 374, 'Los juegos del hambre', 0),
('978-0140444308', 1232, 'Los miserables', 0),
('978-0061120084', 281, 'Matar a un ruiseñor', 0),
('978-1503290563', 279, 'Orgullo y prejuicio', 0),
('978-8491050613', 224, 'Sherlock Holmes: Estudio en escarlata', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Multimedia`
--

CREATE TABLE `Multimedia` (
  `Titulo` varchar(50) NOT NULL,
  `Soporte` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Prestamos`
--

CREATE TABLE `Prestamos` (
  `IdPrestamo` int NOT NULL,
  `IdUsuario` int DEFAULT NULL,
  `IdEjemplar` int DEFAULT NULL,
  `FechaInicio` date DEFAULT NULL,
  `FechaFin` date DEFAULT NULL,
  `Observacion` text,
  `estado` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `Prestamos`
--

INSERT INTO `Prestamos` (`IdPrestamo`, `IdUsuario`, `IdEjemplar`, `FechaInicio`, `FechaFin`, `Observacion`, `estado`) VALUES
(1, 1, 14, '2025-03-13', '2025-03-27', NULL, 0),
(2, 1, 13, '2025-03-13', '2025-03-27', NULL, 0),
(3, 1, 17, '2025-03-13', '2025-03-27', NULL, 1),
(4, 1, 19, '2025-03-13', '2025-03-27', NULL, 1),
(5, 1, 21, '2025-03-13', '2025-03-27', NULL, 1),
(6, 1, 23, '2025-03-13', '2025-03-27', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Revistas`
--

CREATE TABLE `Revistas` (
  `ISBN` varchar(20) DEFAULT NULL,
  `frecuencia` enum('diario','semanal','mensual','anual') NOT NULL,
  `Titulo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Usuarios`
--

CREATE TABLE `Usuarios` (
  `IdUsuario` int NOT NULL,
  `Nombre` varchar(50) NOT NULL,
  `Direccion` varchar(50) DEFAULT NULL,
  `Telefono` varchar(9) DEFAULT NULL,
  `Curso` int NOT NULL,
  `Email` varchar(50) DEFAULT NULL,
  `Clave` varchar(8) NOT NULL,
  `Admin` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `Usuarios`
--

INSERT INTO `Usuarios` (`IdUsuario`, `Nombre`, `Direccion`, `Telefono`, `Curso`, `Email`, `Clave`, `Admin`) VALUES
(1, 'Lucia', 'Calle 45, Madrid', '612345678', 2, 'lucia.fernandez@email.com', 'lucia123', 0),
(2, 'David', 'Avenida Libertad 12, Barcelona', '623456789', 2, 'david.martinez@email.com', 'david456', 0),
(3, 'Sofia', 'Plaza Mayor 5, Valencia', '634567890', 3, 'sofia.gomez@email.com', 'sofia789', 0),
(4, 'Javier', 'Calle Sol 23, Sevilla', '645678901', 1, 'javier.ramirez@email.com', 'javier10', 0),
(5, 'Elena', 'Paseo del Río 7, Bilbao', '656789012', 2, 'elena.torres@email.com', 'elena202', 0),
(6, 'Admin1', 'N/A', 'N/A', 0, 'admin1@email.com', 'admin1', 1),
(7, 'Admin2', 'N/A', 'N/A', 0, 'admin2@email.com', 'admin2', 1),
(8, 'cperseb', 'Roma', '242463', 2, 'carlos@gmail.com', 'c1', 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `Documentos`
--
ALTER TABLE `Documentos`
  ADD PRIMARY KEY (`Titulo`),
  ADD UNIQUE KEY `ISBN` (`ISBN`);

--
-- Indices de la tabla `Ejemplares`
--
ALTER TABLE `Ejemplares`
  ADD PRIMARY KEY (`IdEjemplar`),
  ADD KEY `Titulo` (`Titulo`);

--
-- Indices de la tabla `Libros`
--
ALTER TABLE `Libros`
  ADD PRIMARY KEY (`Titulo`),
  ADD UNIQUE KEY `ISBN` (`ISBN`);

--
-- Indices de la tabla `Multimedia`
--
ALTER TABLE `Multimedia`
  ADD PRIMARY KEY (`Titulo`);

--
-- Indices de la tabla `Prestamos`
--
ALTER TABLE `Prestamos`
  ADD PRIMARY KEY (`IdPrestamo`),
  ADD KEY `IdUsuario` (`IdUsuario`),
  ADD KEY `IdEjemplar` (`IdEjemplar`);

--
-- Indices de la tabla `Revistas`
--
ALTER TABLE `Revistas`
  ADD PRIMARY KEY (`Titulo`),
  ADD UNIQUE KEY `ISBN` (`ISBN`);

--
-- Indices de la tabla `Usuarios`
--
ALTER TABLE `Usuarios`
  ADD PRIMARY KEY (`IdUsuario`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `Ejemplares`
--
ALTER TABLE `Ejemplares`
  MODIFY `IdEjemplar` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `Prestamos`
--
ALTER TABLE `Prestamos`
  MODIFY `IdPrestamo` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `Usuarios`
--
ALTER TABLE `Usuarios`
  MODIFY `IdUsuario` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `Ejemplares`
--
ALTER TABLE `Ejemplares`
  ADD CONSTRAINT `Ejemplares_ibfk_1` FOREIGN KEY (`Titulo`) REFERENCES `Documentos` (`Titulo`) ON DELETE CASCADE;

--
-- Filtros para la tabla `Libros`
--
ALTER TABLE `Libros`
  ADD CONSTRAINT `Libros_ibfk_1` FOREIGN KEY (`Titulo`) REFERENCES `Documentos` (`Titulo`) ON DELETE CASCADE;

--
-- Filtros para la tabla `Multimedia`
--
ALTER TABLE `Multimedia`
  ADD CONSTRAINT `Multimedia_ibfk_1` FOREIGN KEY (`Titulo`) REFERENCES `Documentos` (`Titulo`) ON DELETE CASCADE;

--
-- Filtros para la tabla `Prestamos`
--
ALTER TABLE `Prestamos`
  ADD CONSTRAINT `Prestamos_ibfk_1` FOREIGN KEY (`IdUsuario`) REFERENCES `Usuarios` (`IdUsuario`),
  ADD CONSTRAINT `Prestamos_ibfk_2` FOREIGN KEY (`IdEjemplar`) REFERENCES `Ejemplares` (`IdEjemplar`);

--
-- Filtros para la tabla `Revistas`
--
ALTER TABLE `Revistas`
  ADD CONSTRAINT `Revistas_ibfk_1` FOREIGN KEY (`Titulo`) REFERENCES `Documentos` (`Titulo`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
