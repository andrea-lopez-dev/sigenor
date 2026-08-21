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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO asignaturas VALUES("5","4","11","LENGUA CULTURA Y COMUNICACION","F","2025-05-28 00:00:00","1");
INSERT INTO asignaturas VALUES("6","4","11","MATEMATICA","F","2025-05-30 00:00:00","1");
INSERT INTO asignaturas VALUES("7","4","11","MEMORIA TERRITORIO Y CIUDADANIA","F","2025-05-30 00:00:00","1");
INSERT INTO asignaturas VALUES("8","4","11","CIENCIAS NATURALES","F","2025-05-30 00:00:00","1");
INSERT INTO asignaturas VALUES("9","4","11","COMPONENTE DE PARTICIPACIÓN E  INTEGRACIÓN COMUNITARIA","F","2025-05-30 00:00:00","1");
INSERT INTO asignaturas VALUES("10","4","11","INGLES","F","2025-05-30 00:00:00","1");
INSERT INTO asignaturas VALUES("11","4","11","CONTABILIDAD BASICA","F","2025-05-30 00:00:00","1");
INSERT INTO asignaturas VALUES("12","4","11","CONTABILIDAD AVANZADA","F","2025-05-30 00:00:00","1");
INSERT INTO asignaturas VALUES("13","4","12","LENGUA CULTURA Y COMUNICACION","EEEEEE","2025-06-04 00:00:00","1");




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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO asistencias VALUES("6","12","5","7","11","2","1","2025-06-07");




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
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO calificaciones VALUES("51","12","5","11","25","3","TRES","E","2","2021");
INSERT INTO calificaciones VALUES("54","12","6","11","25","2","DOS","E","05","2012");
INSERT INTO calificaciones VALUES("55","12","7","11","25","3","TRES","F","06","2012");
INSERT INTO calificaciones VALUES("56","12","8","11","25","1","UNO","R","08","2012");
INSERT INTO calificaciones VALUES("57","12","9","11","25","4","APROBADO","T","09","2012");
INSERT INTO calificaciones VALUES("58","12","10","11","25","5","APROBADO","E","12","2012");
INSERT INTO calificaciones VALUES("59","12","11","11","25","3","APROBADO","E","01","2013");
INSERT INTO calificaciones VALUES("61","12","5","12","27","3","TRES","E","03","2013");
INSERT INTO calificaciones VALUES("62","12","6","12","27","4","CUATRO","T","04","2013");
INSERT INTO calificaciones VALUES("63","12","7","12","27","4","CUATRO","T","05","2013");
INSERT INTO calificaciones VALUES("64","12","8","12","27","4","CUATRO","E","06","2013");
INSERT INTO calificaciones VALUES("65","12","9","12","27","1","UNO","T","07","2013");
INSERT INTO calificaciones VALUES("66","12","12","12","27","3","TRES","T","09","2013");
INSERT INTO calificaciones VALUES("67","13","5","11","25","4","CUATRO","T","05","2013");




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
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO estudiantes VALUES("12","7","29646901","RISKYBRIGHT","MLBB","24","FEMENINO","MARACAIBO","2025-06-02","ZULIA","SU CASA","04246192129","GAMERFORLIVE@GMAIL.COM","291141.jpg","2025-06-07 04:03:01","1");
INSERT INTO estudiantes VALUES("13","7","27603081","RENUAM","CHUKIKIII","25","MASCULINO","MARACAIBO","2025-06-03","ZULIA","SU CASA","04246192129","renuam12@gmail.com","213225.jpg","2025-06-03 01:43:58","1");
INSERT INTO estudiantes VALUES("17","7","27603081","J","VA","44","Masculino","SAN FRANCISCO","2025-06-03","ZULIA","SU CASA","04246192129","jva@gmail.com","user.png","2025-06-03 02:06:00","1");




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
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO estudiantes_planteles VALUES("33","12","25","");
INSERT INTO estudiantes_planteles VALUES("34","12","27","");
INSERT INTO estudiantes_planteles VALUES("35","12","28","");
INSERT INTO estudiantes_planteles VALUES("36","12","29","");
INSERT INTO estudiantes_planteles VALUES("37","13","28","");
INSERT INTO estudiantes_planteles VALUES("39","17","25","");




CREATE TABLE `periodos` (
  `id_periodo` int(11) NOT NULL AUTO_INCREMENT,
  `id_plantel` int(11) NOT NULL,
  `numero_periodo` varchar(100) NOT NULL,
  `fecha_inicio` year(4) NOT NULL,
  `fecha_fin` year(4) NOT NULL,
  `nombre_periodo` varchar(100) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `estado` char(1) NOT NULL,
  PRIMARY KEY (`id_periodo`),
  KEY `id_plantel` (`id_plantel`),
  CONSTRAINT `periodos_ibfk_1` FOREIGN KEY (`id_plantel`) REFERENCES `planteles` (`id_plantel`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO periodos VALUES("11","25","1","2025","2025","UNO","2025-05-28 00:00:00","1");
INSERT INTO periodos VALUES("12","25","2","2025","2025","DOS","2025-05-28 00:00:00","1");
INSERT INTO periodos VALUES("13","25","3","2025","2025","TRES","2025-05-28 00:00:00","1");
INSERT INTO periodos VALUES("14","25","4","2025","2025","CUATRO","2025-05-28 00:00:00","1");
INSERT INTO periodos VALUES("15","27","5","2025","2025","CINCO","2025-06-20 00:00:00","1");
INSERT INTO periodos VALUES("16","27","6","2025","2025","SEIS","2025-05-31 00:00:00","1");




CREATE TABLE `plan_administrativo` (
  `id_plan_est` int(11) NOT NULL AUTO_INCREMENT,
  `plan_estudio` varchar(255) NOT NULL,
  `codigo_estudio` varchar(255) NOT NULL,
  `estrategia_estudio` varchar(255) NOT NULL,
  `tipo_evaluacion` varchar(255) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `fecha_estudio` date NOT NULL,
  PRIMARY KEY (`id_plan_est`)
) ENGINE=InnoDB AUTO_INCREMENT=684 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO plan_administrativo VALUES("683","EDUCACIÓN MEDIA GENERAL DE JOVENES, ADULTOS Y ADULTAS","31058","+++++++++++","PRIMERA","“La motivación es lo que te pone en marcha el hábito es lo que hace que sigas”","2025-06-03");




CREATE TABLE `planteles` (
  `id_plantel` int(11) NOT NULL AUTO_INCREMENT,
  `numero_plantel` int(1) NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO planteles VALUES("25","1","1546786","U.E DON FELICIANO PALACIOS Y SOJO","LA VICTORIA","04156552512","MARACAIBO","ZULIA","ZULIA","MARACAIBO","ELMRK","14254123","2025-05-30 21:11:42");
INSERT INTO planteles VALUES("27","2","2131132","U.E GRAL PEDRO BRICEÑO MENDEZ","BARRIO PANAMERICANO","MARACAIBO","04156552512","ZULIA","ZULIA","ZULIA","LISBE","5478123","2006-01-20 00:00:00");
INSERT INTO planteles VALUES("28","3","232556625","U.E EVELIA A. DE PIMENTEL","LOS MANGOS","0425254562","MARACAIBO","ZULIA","ZULIA","ZULIA","LISVE","5478123","2006-01-20 00:00:00");
INSERT INTO planteles VALUES("29","4","3234534534","U.E JOSE FELIX RIVAS","BARRIO PANAMERICANO","04156552512","MARACAIBO","ZULIA","ZULIA","ZULIA","LISVE","5478123","2006-01-20 00:00:00");




CREATE TABLE `profesores` (
  `id_profesor` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_apellido` varchar(120) NOT NULL,
  `cedula_profesor` varchar(255) NOT NULL,
  `sexo` varchar(20) NOT NULL,
  `correo_profesor` varchar(30) NOT NULL,
  `telefono_profesor` varchar(20) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `fecha` datetime NOT NULL,
  `estado` char(1) NOT NULL,
  PRIMARY KEY (`id_profesor`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO profesores VALUES("4","JOSE VIVAS","V-27603081","Masculino","ELTUKITO23@GMAIL.COM","04246192129","642795.jpg","0000-00-00 00:00:00","1");




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
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO seccion VALUES("7","11","A","32","2025-05-28 01:18:00","1");
INSERT INTO seccion VALUES("19","16","A","20","2025-06-06 22:30:00","1");




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