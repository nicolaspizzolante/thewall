-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-04-2020 a las 00:32:44
-- Versión del servidor: 10.1.30-MariaDB
-- Versión de PHP: 7.2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `trabajo`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensaje`
--

CREATE TABLE `mensaje` (
  `id` int(11) NOT NULL,
  `texto` varchar(140) NOT NULL,
  `imagen_contenido` longblob,
  `imagen_tipo` varchar(4) DEFAULT NULL,
  `usuarios_id` int(11) NOT NULL,
  `fechayhora` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `me_gusta`
--

CREATE TABLE `me_gusta` (
  `id` int(11) NOT NULL,
  `usuarios_id` int(11) NOT NULL,
  `mensaje_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuesta_mensaje`
--

CREATE TABLE `respuesta_mensaje` (
  `id` int(11) NOT NULL,
  `texto` varchar(140) NOT NULL,
  `usuarios_id` int(11) NOT NULL,
  `fechayhora` datetime NOT NULL,
  `mensaje_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `siguiendo`
--

CREATE TABLE `siguiendo` (
  `id` int(11) NOT NULL,
  `usuarios_id` int(11) NOT NULL,
  `usuarioseguido_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `apellido` varchar(45) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `nombreusuario` varchar(45) NOT NULL,
  `contrasenia` varchar(45) NOT NULL,
  `foto_contenido` longblob NOT NULL,
  `foto_tipo` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `mensaje`
--
ALTER TABLE `mensaje`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_mensaje_usuarios_idx` (`usuarios_id`);

--
-- Indices de la tabla `me_gusta`
--
ALTER TABLE `me_gusta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_gusta_usuarios1_idx` (`usuarios_id`),
  ADD KEY `fk_gusta_mensaje1_idx` (`mensaje_id`);

--
-- Indices de la tabla `respuesta_mensaje`
--
ALTER TABLE `respuesta_mensaje`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_mensaje_usuarios_idx` (`usuarios_id`),
  ADD KEY `mensaje_id` (`mensaje_id`);

--
-- Indices de la tabla `siguiendo`
--
ALTER TABLE `siguiendo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_siguiendo_usuarios1_idx` (`usuarios_id`),
  ADD KEY `fk_siguiendo_usuarios2_idx` (`usuarioseguido_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `mensaje`
--
ALTER TABLE `mensaje`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=162;

--
-- AUTO_INCREMENT de la tabla `me_gusta`
--
ALTER TABLE `me_gusta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `respuesta_mensaje`
--
ALTER TABLE `respuesta_mensaje`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `siguiendo`
--
ALTER TABLE `siguiendo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `mensaje`
--
ALTER TABLE `mensaje`
  ADD CONSTRAINT `fk_mensaje_usuarios` FOREIGN KEY (`usuarios_id`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `me_gusta`
--
ALTER TABLE `me_gusta`
  ADD CONSTRAINT `fk_gusta_mensaje1` FOREIGN KEY (`mensaje_id`) REFERENCES `mensaje` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_gusta_usuarios1` FOREIGN KEY (`usuarios_id`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `respuesta_mensaje`
--
ALTER TABLE `respuesta_mensaje`
  ADD CONSTRAINT `respuesta_mensaje_ibfk_1` FOREIGN KEY (`mensaje_id`) REFERENCES `mensaje` (`id`);

--
-- Filtros para la tabla `siguiendo`
--
ALTER TABLE `siguiendo`
  ADD CONSTRAINT `fk_siguiendo_usuarios1` FOREIGN KEY (`usuarios_id`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_siguiendo_usuarios2` FOREIGN KEY (`usuarioseguido_id`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
