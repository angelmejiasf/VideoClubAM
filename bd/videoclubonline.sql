-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-12-2023 a las 19:28:42
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `videoclubonline`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actores`
--

CREATE TABLE `actores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `fotografia` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `actores`
--

INSERT INTO `actores` (`id`, `nombre`, `apellidos`, `fotografia`) VALUES
(1, 'Elijah', 'Wood', 'elijah_wood.jpg'),
(2, 'Kate', 'Winslet', 'kate_winslet.jpg'),
(3, 'John', 'Travolta', 'john_travolta.jpg'),
(4, 'Ryan', 'Gosling', 'ryan_gosling.jpg'),
(5, 'Marlon', 'Brando', 'marlon_brando.jpg'),
(6, 'Scarlett', 'Johansson', 'scarlett_johansson.jpg'),
(7, 'Brad', 'Pitt', 'brad_pitt.jpg'),
(8, 'Jennifer', 'Lawrence', 'jennifer_lawrence.jpg'),
(9, 'Leonardo', 'DiCaprio', 'leonardo_dicaprio.jpg'),
(10, 'Emma', 'Stone', 'emma_stone.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actuan`
--

CREATE TABLE `actuan` (
  `idPelicula` int(11) NOT NULL,
  `idActor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `actuan`
--

INSERT INTO `actuan` (`idPelicula`, `idActor`) VALUES
(1, 1),
(1, 6),
(1, 7),
(1, 8),
(2, 2),
(2, 6),
(2, 7),
(2, 9),
(2, 10),
(3, 3),
(3, 8),
(3, 10),
(4, 4),
(4, 9),
(5, 5),
(5, 7),
(5, 8),
(5, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `peliculas`
--

CREATE TABLE `peliculas` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `genero` varchar(50) DEFAULT NULL,
  `pais` varchar(50) DEFAULT NULL,
  `anyo` int(11) DEFAULT NULL,
  `cartel` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `peliculas`
--

INSERT INTO `peliculas` (`id`, `titulo`, `genero`, `pais`, `anyo`, `cartel`) VALUES
(1, 'El señor de los anillos', 'Fantasia', 'Estados Unidos', 2001, 'el_senor_de_los_aneles.jpg'),
(2, 'Titanic', 'Romance', 'Estados Unidos', 1997, 'titanic.jpg'),
(3, 'Pulp Fiction', 'Crimen', 'Estados Unidos', 1994, 'pulp_fiction.jpg'),
(4, 'La La Land', 'Musical', 'Estados Unidos', 2016, 'la_la_land.jpg'),
(5, 'El Padrino', 'Crimen', 'Estados Unidos', 1972, 'el_padrino.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `username`, `password`, `rol`) VALUES
(6, 'angel', '1234', 1),
(7, 'mejias', '5678', 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actores`
--
ALTER TABLE `actores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `actuan`
--
ALTER TABLE `actuan`
  ADD PRIMARY KEY (`idPelicula`,`idActor`),
  ADD KEY `idActor` (`idActor`);

--
-- Indices de la tabla `peliculas`
--
ALTER TABLE `peliculas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_username` (`username`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `actores`
--
ALTER TABLE `actores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `peliculas`
--
ALTER TABLE `peliculas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `actuan`
--
ALTER TABLE `actuan`
  ADD CONSTRAINT `actuan_ibfk_1` FOREIGN KEY (`idPelicula`) REFERENCES `peliculas` (`id`),
  ADD CONSTRAINT `actuan_ibfk_2` FOREIGN KEY (`idActor`) REFERENCES `actores` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
