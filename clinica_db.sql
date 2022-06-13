-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 13-06-2022 a las 00:05:54
-- Versión del servidor: 10.5.12-MariaDB
-- Versión de PHP: 7.3.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `id18947451_db_clinicamedica`
--
CREATE DATABASE IF NOT EXISTS `id18947451_db_clinicamedica` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `id18947451_db_clinicamedica`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `farmacias`
--

CREATE TABLE `farmacias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `direccion` varchar(100) DEFAULT NULL,
  `telefono` varchar(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `farmacias`
--

INSERT INTO `farmacias` (`id`, `nombre`, `direccion`, `telefono`) VALUES
(1, 'Farmacia La Única', 'San Salvador,  19 Avenida Norte', '22202220'),
(2, 'Farmacia La Bendición', 'San Salvador, Calle Los Sisimiles', '22230012'),
(3, 'Farmacia La Luz', 'La Libertad', '22212020'),
(4, 'Farmacia La Maestra', 'San Salvador', '22605668'),
(6, 'Farmacia Azul y blanco', 'San Salvador', '22068962'),
(7, 'Farmacia La luz', 'San salvador', '22659836');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `farmacias_medicinas`
--

CREATE TABLE `farmacias_medicinas` (
  `id` int(11) NOT NULL,
  `farmacia_id` int(11) NOT NULL,
  `medicina_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `medicinas`
--

CREATE TABLE `medicinas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `laboratorio` varchar(50) NOT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `cantidad` int(10) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT 0.00,
  `imagen` varchar(2083) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `medicinas`
--

INSERT INTO `medicinas` (`id`, `nombre`, `laboratorio`, `descripcion`, `cantidad`, `precio`, `imagen`) VALUES
(1, 'Acetaminofen', 'MK', '500mg', 30, 7.60, NULL),
(2, 'Ibuprofeno', 'MK', '400mg', 30, 9.14, NULL),
(3, 'Loratadina', 'Ecomed', '100mg', 30, 30.00, NULL),
(4, 'Virogrip', 'Vijosa', 'Antigripal', 30, 5.00, NULL),
(5, 'Vitamina D3', 'Vijosa', 'Vitaminas x50 capsulas', 30, 15.00, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `medicos`
--

CREATE TABLE `medicos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `dui` varchar(9) NOT NULL,
  `telefono` varchar(8) DEFAULT NULL,
  `especialidad` varchar(20) DEFAULT NULL,
  `farmacia_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `medicos`
--

INSERT INTO `medicos` (`id`, `nombre`, `apellido`, `dui`, `telefono`, `especialidad`, `farmacia_id`) VALUES
(1, 'Roberto', 'Alas', '123456789', '22045540', 'Cirugía', NULL),
(2, 'Jhon', 'Juarez', '987654321', '22045555', 'Cardiólogo', NULL),
(3, 'Alejandra', 'Cortéz', '012345678', '77354488', 'Psicóloga', NULL),
(4, 'Luciano', 'Vasquez', '028668956', '63176068', 'Medicina General', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pacientes`
--

CREATE TABLE `pacientes` (
  `id` int(11) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `dui` varchar(9) DEFAULT NULL,
  `telefono` varchar(8) DEFAULT NULL,
  `ubicacion` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `pacientes`
--

INSERT INTO `pacientes` (`id`, `nombres`, `apellidos`, `dui`, `telefono`, `ubicacion`) VALUES
(1, 'Christian', 'Palacios', '728239393', '02923892', 'Ilopango'),
(2, 'Roberto', 'Castillo', '056565945', '63559888', 'Santa tecla'),
(3, 'Josue', 'Ardon', '035642345', '68986369', 'Ilopango');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `farmacias`
--
ALTER TABLE `farmacias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `farmacias_medicinas`
--
ALTER TABLE `farmacias_medicinas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_medicinas_farmacias` (`medicina_id`),
  ADD KEY `FK_farmacias_medicinas` (`farmacia_id`);

--
-- Indices de la tabla `medicinas`
--
ALTER TABLE `medicinas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `medicos`
--
ALTER TABLE `medicos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_farmacias_medicos` (`farmacia_id`);

--
-- Indices de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `farmacias`
--
ALTER TABLE `farmacias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `farmacias_medicinas`
--
ALTER TABLE `farmacias_medicinas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `medicinas`
--
ALTER TABLE `medicinas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `medicos`
--
ALTER TABLE `medicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `farmacias_medicinas`
--
ALTER TABLE `farmacias_medicinas`
  ADD CONSTRAINT `FK_farmacias_medicinas` FOREIGN KEY (`farmacia_id`) REFERENCES `farmacias` (`id`),
  ADD CONSTRAINT `FK_medicinas_farmacias` FOREIGN KEY (`medicina_id`) REFERENCES `medicinas` (`id`);

--
-- Filtros para la tabla `medicos`
--
ALTER TABLE `medicos`
  ADD CONSTRAINT `FK_farmacias_medicos` FOREIGN KEY (`farmacia_id`) REFERENCES `farmacias` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
