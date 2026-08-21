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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO asignaturas VALUES("5","4","11","LENGUA CULTURA Y COMUNICACION","F","2025-05-28 00:00:00","1");
INSERT INTO asignaturas VALUES("6","4","11","MATEMATICA","F","2025-05-30 00:00:00","1");
INSERT INTO asignaturas VALUES("7","4","11","MEMORIA TERRITORIO Y CIUDADANIA","F","2025-05-30 00:00:00","1");
INSERT INTO asignaturas VALUES("8","4","11","CIENCIAS NATURALES","F","2025-05-30 00:00:00","1");
INSERT INTO asignaturas VALUES("9","4","11","COMPONENTE DE PARTICIPACIÓN E  INTEGRACIÓN COMUNITARIA","F","2025-05-30 00:00:00","1");
INSERT INTO asignaturas VALUES("10","4","11","INGLES","F","2025-05-30 00:00:00","1");
INSERT INTO asignaturas VALUES("11","4","11","CONTABILIDAD BASICA","F","2025-05-30 00:00:00","1");
INSERT INTO asignaturas VALUES("12","4","11","CONTABILIDAD AVANZADA","F","2025-05-30 00:00:00","1");




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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO asistencias VALUES("5","11","5","7","11","5","1","2025-06-03");




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
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO calificaciones VALUES("5","11","5","11","25","5","CINCO","E","06","2007");
INSERT INTO calificaciones VALUES("6","11","6","11","25","4","CUATRO","F","06","2001");
INSERT INTO calificaciones VALUES("7","11","7","11","25","4","CUATRO","F","06","2001");
INSERT INTO calificaciones VALUES("8","11","8","11","25","5","CINCO","F","06","2001");
INSERT INTO calificaciones VALUES("9","11","9","11","25","4","APROBADO","F","06","2001");
INSERT INTO calificaciones VALUES("13","11","5","12","25","2","DOS","G","05","2000");
INSERT INTO calificaciones VALUES("20","11","6","12","25","3","N","E","04","2012");
INSERT INTO calificaciones VALUES("21","11","7","12","25","1","UNO","E","04","2014");
INSERT INTO calificaciones VALUES("22","11","8","12","25","3","n","T","04","2014");
INSERT INTO calificaciones VALUES("23","11","9","12","25","5","APROBADO","E","04","2014");
INSERT INTO calificaciones VALUES("24","11","5","13","27","5","CINCO","T","07","2015");
INSERT INTO calificaciones VALUES("27","11","6","13","27","4","CUATRO","E","08","2015");
INSERT INTO calificaciones VALUES("28","11","7","13","27","5","CINCO","E","08","2015");
INSERT INTO calificaciones VALUES("29","11","8","13","27","2","DOS","E","08","2015");
INSERT INTO calificaciones VALUES("30","11","9","13","27","5","APROBADO","T","08","2018");
INSERT INTO calificaciones VALUES("33","11","5","14","28","2","DOS","E","06","2016");
INSERT INTO calificaciones VALUES("34","11","6","14","28","3","N","E","06","2016");
INSERT INTO calificaciones VALUES("35","11","7","14","28","5","CINCO","T","08","2017");
INSERT INTO calificaciones VALUES("36","11","8","14","28","2","DOS","T","08","2016");
INSERT INTO calificaciones VALUES("37","11","9","14","28","4","APROBADO","E","10","2016");
INSERT INTO calificaciones VALUES("38","11","5","15","29","2","DOS","T","08","2017");
INSERT INTO calificaciones VALUES("39","11","6","15","29","3","N","T","08","2018");
INSERT INTO calificaciones VALUES("40","11","7","15","29","1","UNO","T","02","2019");
INSERT INTO calificaciones VALUES("41","11","8","15","29","4","CUATRO","T","03","2019");
INSERT INTO calificaciones VALUES("42","11","9","15","29","5","APROBADO","E","05","2019");
INSERT INTO calificaciones VALUES("43","11","10","15","29","5","APROBADO","E","07","2019");
INSERT INTO calificaciones VALUES("44","11","11","15","29","5","APROBADO","T","09","2019");
INSERT INTO calificaciones VALUES("45","11","5","16","29","3","TRES","E","01","2020");
INSERT INTO calificaciones VALUES("46","11","6","16","29","5","CINCO","E","02","2020");
INSERT INTO calificaciones VALUES("47","11","7","16","29","4","CUATRO","E","04","2020");
INSERT INTO calificaciones VALUES("48","11","8","16","29","5","CINCO","T","05","2020");
INSERT INTO calificaciones VALUES("49","11","9","16","29","5","APROBADO","T","06","2020");
INSERT INTO calificaciones VALUES("50","11","12","16","29","1","REPROBADO","E","08","2020");




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

INSERT INTO estudiantes VALUES("11","7","V-29646901","ANDREA VIRGINIA","LOPEZ SANMARTIN","24","FEMENINO","MARACAIBO","2025-05-07","ZULIA","BALMIRO LEON ETAPA I","04246192129","andrea@gmail.com","845381.jpeg","2025-06-03 00:28:32","1");
INSERT INTO estudiantes VALUES("12","7","29646901","RISKYBRIGHT","MLBB","24","FEMENINO","MARACAIBO","2025-06-02","ZULIA","SU CASA","04246192129","GAMERFORLIVE@GMAIL.COM","291141.jpg","2025-06-03 00:28:41","1");
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

INSERT INTO estudiantes_planteles VALUES("29","11","25","");
INSERT INTO estudiantes_planteles VALUES("30","11","27","");
INSERT INTO estudiantes_planteles VALUES("31","11","28","");
INSERT INTO estudiantes_planteles VALUES("32","11","29","");
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

INSERT INTO plan_administrativo VALUES("683","aaaaaaaaaaaaaaaaaaaa","31058","ooooooooooooo","PRIMERA","NOSE","2025-06-03");




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