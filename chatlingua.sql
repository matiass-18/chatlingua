-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-11-2024 a las 06:41:04
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `chatlingua`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes`
--

CREATE TABLE `mensajes` (
  `id` int(11) NOT NULL,
  `emisor_id` int(11) NOT NULL,
  `receptor_id` int(11) NOT NULL,
  `contenido` text NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `leido` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mensajes`
--

INSERT INTO `mensajes` (`id`, `emisor_id`, `receptor_id`, `contenido`, `fecha`, `leido`) VALUES
(1, 1, 3, 'Hola', '2024-11-12 03:25:04', 0),
(2, 1, 2, 'Hola', '2024-11-12 03:25:08', 0),
(3, 2, 3, 'Hola', '2024-11-12 03:25:22', 0),
(4, 2, 1, 'Puta', '2024-11-12 03:25:28', 1),
(5, 3, 2, 'XD', '2024-11-12 03:25:41', 0),
(6, 3, 1, 'XD', '2024-11-12 03:25:43', 1),
(7, 4, 3, 'hablame', '2024-11-12 04:13:04', 0),
(8, 4, 3, 'hablame', '2024-11-12 04:13:07', 0),
(9, 4, 1, 'hablame', '2024-11-12 04:13:11', 1),
(10, 1, 4, 'dassaddsads', '2024-11-12 04:13:39', 0),
(11, 1, 3, 'a', '2024-11-12 06:46:59', 0),
(12, 5, 4, 'Hola', '2024-11-12 19:19:27', 0),
(13, 5, 1, 'XD', '2024-11-12 19:20:26', 1),
(14, 1, 2, 'Hola', '2024-11-12 19:22:56', 0),
(15, 5, 4, 'Hola', '2024-11-12 19:32:59', 0),
(16, 1, 5, 'Hola', '2024-11-12 19:33:22', 1),
(17, 1, 5, 'Hola', '2024-11-12 19:35:05', 1),
(18, 5, 1, 'Holi', '2024-11-12 19:35:17', 1),
(19, 1, 5, 'gay', '2024-11-13 05:36:39', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `idioma_aprender` varchar(20) NOT NULL,
  `foto_perfil` varchar(255) DEFAULT 'default.jpg',
  `estado` enum('conectado','desconectado') DEFAULT 'desconectado',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `reset_token` varchar(100) DEFAULT NULL,
  `reset_expira` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellido`, `email`, `password`, `idioma_aprender`, `foto_perfil`, `estado`, `fecha_registro`, `reset_token`, `reset_expira`) VALUES
(1, 'juan', 'cano', 'juanpipecano08@gmail.com', '$2y$10$cgX17WM5sPMzD5DsxO4zveZd/c2Lbf7BZ7SzWtX7XPdXrkjn1v.sm', 'espanol', '67328aacd8b78_Blue White Creative Application Presentation (1).png', 'desconectado', '2024-11-11 22:52:28', NULL, NULL),
(2, 'Matias', 'Chahin', 'matias@gmail.com', '$2y$10$swWyrfcEMPgDaYuPknsGPeqY9Y1L4G3HhfuHZEvrzRqsUdfXjQZPq', 'ingles', '67328b52496fd_Blue White Creative Application Presentation (1).png', 'desconectado', '2024-11-11 22:55:14', NULL, NULL),
(3, 'asd', 'asd', 'asd@gmail.com', '$2y$10$URcdcJR3D4eYdhHpLropeekCp93818nyJ6WtoxT0z//X9Fpj4JL7G', 'ingles', '67328e21c9dc5_Blue White Creative Application Presentation (1).png', 'desconectado', '2024-11-11 23:07:13', NULL, NULL),
(4, 'Juan Felipe', 'Canito', 'kayoutgamer@gmail.com', '$2y$10$xDsTLP44XWnEF8uBhZSgMOE.UomTW55OdzpdcGv5BiiTZjwlAFb9G', 'espanol', '6732d5c5a85f8_1.png', 'desconectado', '2024-11-12 04:12:53', NULL, NULL),
(5, 'Matias', 'Chahin', 'matias.chahinto@amigo.edu.co', '$2y$10$6oPuxw9ybXJGQQ.3JJJrOOonwmweV9qhoguVPLeIXORzNVx.dpl3O', 'ingles', '6733aa309383e_image.png', 'desconectado', '2024-11-12 19:19:12', NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_mensajes_emisor` (`emisor_id`),
  ADD KEY `idx_mensajes_receptor` (`receptor_id`),
  ADD KEY `idx_mensajes_fecha` (`fecha`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_usuarios_estado` (`estado`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD CONSTRAINT `mensajes_ibfk_1` FOREIGN KEY (`emisor_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `mensajes_ibfk_2` FOREIGN KEY (`receptor_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
