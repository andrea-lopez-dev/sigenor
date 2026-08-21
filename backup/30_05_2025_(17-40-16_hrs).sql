SET FOREIGN_KEY_CHECKS=0;

CREATE DATABASE IF NOT EXISTS sigenor;

USE sigenor;



CREATE TABLE `asignaturas` (
  `id_curso` int(11) NOT NULL AUTO_INCREMENT,
  `id_profesor` int(11) NOT NULL,
  `id_periodo` int(11) NOT NULL,
  `nombre_curso` varchar(100) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `fecha` datetime NOT NULL,
  `estado` char(1) NOT NULL,
  PRIMARY KEY (`id_curso`),
  KEY `id_profesor` (`id_profesor`),
  KEY `id_periodo` (`id_periodo`),
  CONSTRAINT `asignaturas_ibfk_1` FOREIGN KEY (`id_periodo`) REFERENCES `periodos` (`id_periodo`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `asignaturas_ibfk_2` FOREIGN KEY (`id_profesor`) REFERENCES `profesores` (`id_profesor`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO asignaturas VALUES("5","4","11","LENGUA CULTURA Y COMUNICACION","HAY TE DOY WEBO","2025-05-28 00:00:00","1");




CREATE TABLE `asistencias` (
  `id_asistencia` int(11) NOT NULL AUTO_INCREMENT,
  `id_estudiante` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL,
  `id_seccion` int(11) NOT NULL,
  `id_periodo` int(11) NOT NULL,
  `asistencias` smallint(6) NOT NULL,
  `inasistencias` smallint(6) NOT NULL,
  `fecha_creacion` date NOT NULL,
  PRIMARY KEY (`id_asistencia`),
  KEY `id_estudiante` (`id_estudiante`),
  KEY `id_curso` (`id_curso`),
  KEY `id_seccion` (`id_seccion`),
  KEY `id_periodo` (`id_periodo`),
  CONSTRAINT `asistencias_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `asistencias_ibfk_2` FOREIGN KEY (`id_curso`) REFERENCES `asignaturas` (`id_curso`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `asistencias_ibfk_3` FOREIGN KEY (`id_seccion`) REFERENCES `seccion` (`id_seccion`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `asistencias_ibfk_4` FOREIGN KEY (`id_periodo`) REFERENCES `periodos` (`id_periodo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;





CREATE TABLE `calificaciones` (
  `id_calificacion` int(11) NOT NULL AUTO_INCREMENT,
  `id_estudiante` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL,
  `id_periodo` int(11) NOT NULL,
  `id_plantel` int(11) NOT NULL,
  `calificacion` smallint(1) NOT NULL,
  `calificacion_letras` varchar(30) NOT NULL,
  `T-E` varchar(50) NOT NULL,
  `mes` varchar(20) NOT NULL,
  `año` year(4) NOT NULL,
  PRIMARY KEY (`id_calificacion`),
  KEY `id_estudiante` (`id_estudiante`),
  KEY `id_curso` (`id_curso`),
  KEY `id_periodo` (`id_periodo`),
  KEY `id_plantel` (`id_plantel`),
  CONSTRAINT `calificaciones_ibfk_1` FOREIGN KEY (`id_plantel`) REFERENCES `planteles` (`id_plantel`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `calificaciones_ibfk_2` FOREIGN KEY (`id_periodo`) REFERENCES `periodos` (`id_periodo`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `calificaciones_ibfk_3` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `calificaciones_ibfk_4` FOREIGN KEY (`id_curso`) REFERENCES `asignaturas` (`id_curso`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;





CREATE TABLE `estudiantes` (
  `id_estudiante` int(11) NOT NULL AUTO_INCREMENT,
  `id_seccion` int(11) NOT NULL,
  `cedula` varchar(12) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(50) NOT NULL,
  `edad` varchar(20) NOT NULL,
  `sexo` varchar(30) NOT NULL,
  `lugar_nacimiento` varchar(255) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `entidad_federal` varchar(255) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `tlf_estudiante` varchar(30) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `estado` char(1) NOT NULL,
  PRIMARY KEY (`id_estudiante`),
  KEY `id_seccion` (`id_seccion`),
  CONSTRAINT `estudiantes_ibfk_2` FOREIGN KEY (`id_seccion`) REFERENCES `seccion` (`id_seccion`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO estudiantes VALUES("11","7","29646901","ANDREA VIRGINIA","LOPEZ SANMARTIN","24","Femenino","MARACAIBO","2025-05-07","ZULIA","BALMIRO LEON ETAPA I","04246192129","andrea@gmail.com","845381.jpeg","2025-05-28 00:56:43","1");




CREATE TABLE `estudiantes_planteles` (
  `id_historial` int(11) NOT NULL AUTO_INCREMENT,
  `id_estudiante` int(11) NOT NULL,
  `id_plantel` int(11) NOT NULL,
  `numero_plantel` varchar(1) NOT NULL,
  PRIMARY KEY (`id_historial`),
  KEY `id_estudiante` (`id_estudiante`),
  KEY `id_plantel` (`id_plantel`),
  CONSTRAINT `estudiantes_planteles_ibfk_1` FOREIGN KEY (`id_plantel`) REFERENCES `planteles` (`id_plantel`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `estudiantes_planteles_ibfk_2` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO estudiantes_planteles VALUES("26","11","25","");




CREATE TABLE `periodos` (
  `id_periodo` int(11) NOT NULL AUTO_INCREMENT,
  `id_plantel` int(11) NOT NULL,
  `numero_periodo` varchar(100) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `nombre_periodo` varchar(100) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `estado` char(1) NOT NULL,
  PRIMARY KEY (`id_periodo`),
  KEY `id_plantel` (`id_plantel`),
  CONSTRAINT `periodos_ibfk_1` FOREIGN KEY (`id_plantel`) REFERENCES `planteles` (`id_plantel`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO periodos VALUES("11","25","1","2025-05-27","2025-05-28","UNO","2025-05-28 00:00:00","1");
INSERT INTO periodos VALUES("12","25","2","2025-05-28","2025-05-28","DOS","2025-05-28 00:00:00","1");
INSERT INTO periodos VALUES("13","25","3","2025-05-28","2025-05-28","TRES","2025-05-28 00:00:00","1");
INSERT INTO periodos VALUES("14","25","4","2025-05-28","2025-05-28","CUATRO","2025-05-28 00:00:00","1");




CREATE TABLE `planteles` (
  `id_plantel` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(50) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `direccion_plantel` varchar(255) NOT NULL,
  `telefono` varchar(30) NOT NULL,
  `municipio` varchar(100) NOT NULL,
  `entidad_federal` varchar(100) NOT NULL,
  `zona_educativa` varchar(100) NOT NULL,
  `localidad` varchar(255) NOT NULL,
  `director` varchar(20) NOT NULL,
  `cedula_director` varchar(12) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_plantel`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO planteles VALUES("25","1546786","U.E DON FELICIANO PALACIOS Y SOJO","LA VICTORIA","MARACAIBO","04156552512","ZULIA","ZULIA","MARACAIBO","ELMRK","14254123","2006-01-20 00:00:00");




CREATE TABLE `profesores` (
  `id_profesor` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_apellido` varchar(120) NOT NULL,
  `cedula_profesor` varchar(15) NOT NULL,
  `sexo` varchar(20) NOT NULL,
  `correo_profesor` varchar(30) NOT NULL,
  `telefono_profesor` varchar(20) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `fecha` datetime NOT NULL,
  `estado` char(1) NOT NULL,
  PRIMARY KEY (`id_profesor`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO profesores VALUES("4","JOSE VIVAS","27603081","Masculino","ELTUKITO23@GMAIL.COM","04246192129","642795.jpg","0000-00-00 00:00:00","1");




CREATE TABLE `seccion` (
  `id_seccion` int(11) NOT NULL AUTO_INCREMENT,
  `id_periodo` int(11) NOT NULL,
  `nombre_seccion` varchar(10) NOT NULL,
  `capacidad` smallint(100) NOT NULL,
  `fecha` datetime NOT NULL,
  `estado` char(1) NOT NULL,
  PRIMARY KEY (`id_seccion`),
  KEY `id_periodo` (`id_periodo`),
  CONSTRAINT `seccion_ibfk_1` FOREIGN KEY (`id_periodo`) REFERENCES `periodos` (`id_periodo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO seccion VALUES("7","11","A","32","2025-05-28 01:18:00","1");




CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_usuario` varchar(20) NOT NULL,
  `nombre_completo` varchar(120) NOT NULL,
  `correo` varchar(30) NOT NULL,
  `clave` varchar(15) NOT NULL,
  `rol` char(1) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `fecha` datetime NOT NULL,
  `estado` char(1) NOT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO usuarios VALUES("1","admin","Andrea y Jose","admin@gmail.com","192401","1","405407.jpg","2025-02-24 23:11:01","1");


SET FOREIGN_KEY_CHECKS=1;