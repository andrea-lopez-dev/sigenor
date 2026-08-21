-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-05-2025 a las 01:31:31
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
-- Base de datos: `sigenor`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignaturas`
--

CREATE TABLE `asignaturas` (
  `id_curso` int(11) NOT NULL,
  `id_profesor` int(11) NOT NULL,
  `id_periodo` int(11) NOT NULL,
  `nombre_curso` varchar(100) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `fecha` datetime NOT NULL,
  `estado` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Volcado de datos para la tabla `asignaturas`
--

INSERT INTO `asignaturas` (`id_curso`, `id_profesor`, `id_periodo`, `nombre_curso`, `descripcion`, `fecha`, `estado`) VALUES
(4, 3, 5, 'erearererw', 'xddgxdgdxg', '2025-03-07 00:00:00', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencias`
--

CREATE TABLE `asistencias` (
  `id_asistencia` int(11) NOT NULL,
  `id_estudiante` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL,
  `id_seccion` int(11) NOT NULL,
  `id_periodo` int(11) NOT NULL,
  `asistencias` smallint(6) NOT NULL,
  `inasistencias` smallint(6) NOT NULL,
  `fecha_creacion` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Volcado de datos para la tabla `asistencias`
--

INSERT INTO `asistencias` (`id_asistencia`, `id_estudiante`, `id_curso`, `id_seccion`, `id_periodo`, `asistencias`, `inasistencias`, `fecha_creacion`) VALUES
(1, 5, 4, 6, 5, 454, 454, '2025-03-01 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calificaciones`
--

CREATE TABLE `calificaciones` (
  `id_calificacion` int(11) NOT NULL,
  `id_estudiante` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL,
  `id_periodo` int(11) NOT NULL,
  `id_plantel` int(11) NOT NULL,
  `calificacion` smallint(1) NOT NULL,
  `calificacion_letras` varchar(30) NOT NULL,
  `T-E` varchar(50) NOT NULL,
  `mes` varchar(20) NOT NULL,
  `año` year(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Volcado de datos para la tabla `calificaciones`
--

INSERT INTO `calificaciones` (`id_calificacion`, `id_estudiante`, `id_curso`, `id_periodo`, `id_plantel`, `calificacion`, `calificacion_letras`, `T-E`, `mes`, `año`) VALUES
(3, 5, 4, 5, 20, 5, 'cinco', 'no', 'febrero', '2025');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes`
--

CREATE TABLE `estudiantes` (
  `id_estudiante` int(11) NOT NULL,
  `id_seccion` int(11) NOT NULL,
  `cedula` varchar(12) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(50) NOT NULL,
  `edad` varchar(20) NOT NULL,
  `sexo` varchar(30) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `tlf_estudiante` varchar(30) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `estado` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Volcado de datos para la tabla `estudiantes`
--

INSERT INTO `estudiantes` (`id_estudiante`, `id_seccion`, `cedula`, `nombres`, `apellidos`, `edad`, `sexo`, `fecha_nacimiento`, `direccion`, `tlf_estudiante`, `correo`, `foto`, `fecha`, `estado`) VALUES
(5, 6, '2312321', '3dfdfdf', 'fdfdsfdfsdf', '23', 'Femenino', '2025-03-14', 'dgsdgdfgsg', '2424242342', 'sdadas@sdad.com', '120838.jpg', '2025-04-04 18:52:36', '1'),
(6, 6, '23412342', 'joy', 'fdfdsfdfsdf', '32', 'Femenino', '2025-03-23', 'sdas', '21312312312312', 'safdafas@gmail.com', '120838.jpg', '2025-03-23 05:54:38', '1'),
(7, 6, '123123123', 'asdaswe', 'qweqweqwe', '32', 'Masculino', '2025-04-06', 'sadasdas', '21312312312', 'wqeqweqw@gmail.com', '429252.jpg', '2025-04-06 19:17:51', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes_planteles`
--

CREATE TABLE `estudiantes_planteles` (
  `id_historial` int(11) NOT NULL,
  `id_estudiante` int(11) NOT NULL,
  `id_plantel` int(11) NOT NULL,
  `numero_plantel` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Volcado de datos para la tabla `estudiantes_planteles`
--

INSERT INTO `estudiantes_planteles` (`id_historial`, `id_estudiante`, `id_plantel`, `numero_plantel`) VALUES
(13, 5, 20, '2'),
(14, 6, 20, ''),
(15, 6, 21, ''),
(16, 7, 21, ''),
(17, 7, 20, ''),
(18, 7, 22, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `periodos`
--

CREATE TABLE `periodos` (
  `id_periodo` int(11) NOT NULL,
  `id_plantel` int(11) NOT NULL,
  `numero_periodo` varchar(100) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `nombre_periodo` varchar(100) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `estado` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Volcado de datos para la tabla `periodos`
--

INSERT INTO `periodos` (`id_periodo`, `id_plantel`, `numero_periodo`, `fecha_inicio`, `fecha_fin`, `nombre_periodo`, `fecha`, `estado`) VALUES
(5, 20, '1', '2025-03-09', '0000-00-00', 'esdasdasd', '0000-00-00 00:00:00', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `planteles`
--

CREATE TABLE `planteles` (
  `id_plantel` int(11) NOT NULL,
  `codigo` varchar(50) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `direccion_plantel` varchar(255) NOT NULL,
  `telefono` varchar(30) NOT NULL,
  `municipio` varchar(100) NOT NULL,
  `entidad_federal` varchar(100) NOT NULL,
  `zona_educativa` varchar(100) NOT NULL,
  `director` varchar(20) NOT NULL,
  `cedula_director` varchar(12) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Volcado de datos para la tabla `planteles`
--

INSERT INTO `planteles` (`id_plantel`, `codigo`, `nombre`, `direccion_plantel`, `telefono`, `municipio`, `entidad_federal`, `zona_educativa`, `director`, `cedula_director`, `fecha`) VALUES
(20, '4545454', 'fey', 'fzsfdfsdf', 'fvdzgd', '54457457', 'gdgdg', 'gdgdgd', 'gdg', 'gsg', '2025-04-05 04:00:00'),
(21, '1223123123', 'pedro', 'sadasdas', '123123123', 'asdasdas', 'dasdasdas', 'asdasdasd', 'asdasdasd', '123123123', '2025-03-23 04:00:00'),
(22, '231231231', 'cesar', 'sdfasdasd', '213123123', 'sadasdas', 'sdadasd', 'sdasdasd', 'asdasdasd', '13123123123', '2025-04-06 04:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesores`
--

CREATE TABLE `profesores` (
  `id_profesor` int(11) NOT NULL,
  `nombre_apellido` varchar(120) NOT NULL,
  `cedula_profesor` varchar(15) NOT NULL,
  `sexo` varchar(20) NOT NULL,
  `correo_profesor` varchar(30) NOT NULL,
  `telefono_profesor` varchar(20) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `fecha` datetime NOT NULL,
  `estado` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Volcado de datos para la tabla `profesores`
--

INSERT INTO `profesores` (`id_profesor`, `nombre_apellido`, `cedula_profesor`, `sexo`, `correo_profesor`, `telefono_profesor`, `foto`, `fecha`, `estado`) VALUES
(3, 'fgfgfg', '34242244', 'Femenino', 'warestse@dd.com', '5235325325', '665197.jpg', '0000-00-00 00:00:00', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seccion`
--

CREATE TABLE `seccion` (
  `id_seccion` int(11) NOT NULL,
  `id_periodo` int(11) NOT NULL,
  `nombre_seccion` varchar(10) NOT NULL,
  `capacidad` smallint(100) NOT NULL,
  `fecha` datetime NOT NULL,
  `estado` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Volcado de datos para la tabla `seccion`
--

INSERT INTO `seccion` (`id_seccion`, `id_periodo`, `nombre_seccion`, `capacidad`, `fecha`, `estado`) VALUES
(6, 5, 'A', 21, '2025-03-23 22:20:00', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre_usuario` varchar(20) NOT NULL,
  `nombre_completo` varchar(120) NOT NULL,
  `correo` varchar(30) NOT NULL,
  `clave` varchar(15) NOT NULL,
  `rol` char(1) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `fecha` datetime NOT NULL,
  `estado` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre_usuario`, `nombre_completo`, `correo`, `clave`, `rol`, `foto`, `fecha`, `estado`) VALUES
(1, 'admin', 'Andrea y Jose', 'admin@gmail.com', '192401', '1', '405407.jpg', '2025-02-24 23:11:01', '1');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asignaturas`
--
ALTER TABLE `asignaturas`
  ADD PRIMARY KEY (`id_curso`),
  ADD KEY `id_profesor` (`id_profesor`),
  ADD KEY `id_periodo` (`id_periodo`);

--
-- Indices de la tabla `asistencias`
--
ALTER TABLE `asistencias`
  ADD PRIMARY KEY (`id_asistencia`),
  ADD KEY `id_estudiante` (`id_estudiante`),
  ADD KEY `id_curso` (`id_curso`),
  ADD KEY `id_seccion` (`id_seccion`),
  ADD KEY `id_periodo` (`id_periodo`);

--
-- Indices de la tabla `calificaciones`
--
ALTER TABLE `calificaciones`
  ADD PRIMARY KEY (`id_calificacion`),
  ADD KEY `id_estudiante` (`id_estudiante`),
  ADD KEY `id_curso` (`id_curso`),
  ADD KEY `id_periodo` (`id_periodo`),
  ADD KEY `id_plantel` (`id_plantel`);

--
-- Indices de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD PRIMARY KEY (`id_estudiante`),
  ADD KEY `id_seccion` (`id_seccion`);

--
-- Indices de la tabla `estudiantes_planteles`
--
ALTER TABLE `estudiantes_planteles`
  ADD PRIMARY KEY (`id_historial`),
  ADD KEY `id_estudiante` (`id_estudiante`),
  ADD KEY `id_plantel` (`id_plantel`);

--
-- Indices de la tabla `periodos`
--
ALTER TABLE `periodos`
  ADD PRIMARY KEY (`id_periodo`),
  ADD KEY `id_plantel` (`id_plantel`);

--
-- Indices de la tabla `planteles`
--
ALTER TABLE `planteles`
  ADD PRIMARY KEY (`id_plantel`);

--
-- Indices de la tabla `profesores`
--
ALTER TABLE `profesores`
  ADD PRIMARY KEY (`id_profesor`);

--
-- Indices de la tabla `seccion`
--
ALTER TABLE `seccion`
  ADD PRIMARY KEY (`id_seccion`),
  ADD KEY `id_periodo` (`id_periodo`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asignaturas`
--
ALTER TABLE `asignaturas`
  MODIFY `id_curso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `asistencias`
--
ALTER TABLE `asistencias`
  MODIFY `id_asistencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `calificaciones`
--
ALTER TABLE `calificaciones`
  MODIFY `id_calificacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  MODIFY `id_estudiante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `estudiantes_planteles`
--
ALTER TABLE `estudiantes_planteles`
  MODIFY `id_historial` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `periodos`
--
ALTER TABLE `periodos`
  MODIFY `id_periodo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `planteles`
--
ALTER TABLE `planteles`
  MODIFY `id_plantel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `profesores`
--
ALTER TABLE `profesores`
  MODIFY `id_profesor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `seccion`
--
ALTER TABLE `seccion`
  MODIFY `id_seccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asignaturas`
--
ALTER TABLE `asignaturas`
  ADD CONSTRAINT `asignaturas_ibfk_1` FOREIGN KEY (`id_periodo`) REFERENCES `periodos` (`id_periodo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `asignaturas_ibfk_2` FOREIGN KEY (`id_profesor`) REFERENCES `profesores` (`id_profesor`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `asistencias`
--
ALTER TABLE `asistencias`
  ADD CONSTRAINT `asistencias_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `asistencias_ibfk_2` FOREIGN KEY (`id_curso`) REFERENCES `asignaturas` (`id_curso`),
  ADD CONSTRAINT `asistencias_ibfk_3` FOREIGN KEY (`id_seccion`) REFERENCES `seccion` (`id_seccion`),
  ADD CONSTRAINT `asistencias_ibfk_4` FOREIGN KEY (`id_periodo`) REFERENCES `periodos` (`id_periodo`);

--
-- Filtros para la tabla `calificaciones`
--
ALTER TABLE `calificaciones`
  ADD CONSTRAINT `calificaciones_ibfk_1` FOREIGN KEY (`id_plantel`) REFERENCES `planteles` (`id_plantel`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `calificaciones_ibfk_2` FOREIGN KEY (`id_periodo`) REFERENCES `periodos` (`id_periodo`),
  ADD CONSTRAINT `calificaciones_ibfk_3` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`),
  ADD CONSTRAINT `calificaciones_ibfk_4` FOREIGN KEY (`id_curso`) REFERENCES `asignaturas` (`id_curso`);

--
-- Filtros para la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD CONSTRAINT `estudiantes_ibfk_2` FOREIGN KEY (`id_seccion`) REFERENCES `seccion` (`id_seccion`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `estudiantes_planteles`
--
ALTER TABLE `estudiantes_planteles`
  ADD CONSTRAINT `estudiantes_planteles_ibfk_1` FOREIGN KEY (`id_plantel`) REFERENCES `planteles` (`id_plantel`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `estudiantes_planteles_ibfk_2` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `periodos`
--
ALTER TABLE `periodos`
  ADD CONSTRAINT `periodos_ibfk_1` FOREIGN KEY (`id_plantel`) REFERENCES `planteles` (`id_plantel`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `seccion`
--
ALTER TABLE `seccion`
  ADD CONSTRAINT `seccion_ibfk_1` FOREIGN KEY (`id_periodo`) REFERENCES `periodos` (`id_periodo`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
