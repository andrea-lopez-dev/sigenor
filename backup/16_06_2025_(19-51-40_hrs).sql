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
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO asignaturas VALUES("25","4","17","LENGUA CULTURA Y COMUNICACION","T","2025-06-08 00:00:00","1");
INSERT INTO asignaturas VALUES("26","4","17","MATEMATICA","T","2025-06-08 00:00:00","1");
INSERT INTO asignaturas VALUES("27","4","17","MEMORIA TERRITORIO Y CIUDADANIA","E","2025-06-08 00:00:00","1");
INSERT INTO asignaturas VALUES("28","4","17","CIENCIAS NATURALES","E","2025-06-08 00:00:00","1");
INSERT INTO asignaturas VALUES("29","4","17","COMPONENTE DE PARTICIPACION E INTEGRACION COMUNITARIA","E","2025-06-08 00:00:00","1");
INSERT INTO asignaturas VALUES("30","4","17","INGLES","E","2025-06-08 00:00:00","1");
INSERT INTO asignaturas VALUES("31","4","18","LENGUA CULTURA Y COMUNICACION","E","2025-06-08 00:00:00","1");
INSERT INTO asignaturas VALUES("32","4","18","MATEMATICA","E","2025-06-08 00:00:00","1");
INSERT INTO asignaturas VALUES("33","4","18","MEMORIA TERRITORIO Y CIUDADANIA","E","2025-06-08 00:00:00","1");
INSERT INTO asignaturas VALUES("34","4","18","CIENCIAS NATURALES","E","2025-06-08 00:00:00","1");
INSERT INTO asignaturas VALUES("35","4","18","COMPONENTE DE PARTICIPACION E INTEGRACION COMUNITARIA","E","2025-06-08 00:00:00","1");
INSERT INTO asignaturas VALUES("36","4","17","CONTABILIDAD BASICA","E","2025-06-08 00:00:00","1");
INSERT INTO asignaturas VALUES("37","4","18","CONTABILIDAD AVANZADA","E","2025-06-08 00:00:00","1");




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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO asistencias VALUES("7","18","25","23","17","21","14","2025-06-08");




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
) ENGINE=InnoDB AUTO_INCREMENT=108 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO calificaciones VALUES("95","18","25","17","28","2","DOS","E","01","2011");
INSERT INTO calificaciones VALUES("96","18","26","17","28","4","CUATRO","E","02","2011");
INSERT INTO calificaciones VALUES("97","18","27","17","28","3","TRES","E","03","2011");
INSERT INTO calificaciones VALUES("98","18","28","17","28","5","CINCO","E","04","2011");
INSERT INTO calificaciones VALUES("99","18","29","17","28","4","APROBADO","E","05","2011");
INSERT INTO calificaciones VALUES("100","18","30","17","28","5","APROBADO","E","06","2011");
INSERT INTO calificaciones VALUES("101","18","31","18","31","2","DOS","E","02","2012");
INSERT INTO calificaciones VALUES("102","18","32","18","31","4","CUATRO","E","03","2012");
INSERT INTO calificaciones VALUES("103","18","33","18","31","5","CINCO","E","04","2012");
INSERT INTO calificaciones VALUES("104","18","34","18","31","2","DOS","E","06","2012");
INSERT INTO calificaciones VALUES("105","18","35","18","31","1","NO APROBADO","E","07","2012");
INSERT INTO calificaciones VALUES("106","18","36","17","28","5","APROBADO","E","10","2011");
INSERT INTO calificaciones VALUES("107","18","37","18","31","5","APROBADO","E","05","2013");




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
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO estudiantes VALUES("18","23","29646901","ANDREA VIRGINIA","LOPEZ SANMARTIN","24","Femenino","MARACAIBO","2001-02-19","ZULIA","BALMIRO LEON ETAPA I","04129998384","andrea@gmail.com","963306.png","2025-06-08 17:42:43","1");




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
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO estudiantes_planteles VALUES("46","18","28","");
INSERT INTO estudiantes_planteles VALUES("47","18","31","");




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
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO periodos VALUES("17","28","1","2024","2025","UNO","2025-06-08 00:00:00","1");
INSERT INTO periodos VALUES("18","31","2","2024","2025","DOS","2025-06-08 00:00:00","1");




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
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO planteles VALUES("28","3","232556625","U.E EVELIA A. DE PIMENTEL","LOS MANGOS","0425254562","MARACAIBO","ZULIA","ZULIA","ZULIA","LISVE","5478123","2006-01-20 00:00:00");
INSERT INTO planteles VALUES("31","2","3234534534","CESAR ANDRADE","BARRIO PANAMERICANO","04156552512","MARACAIBO","ZULIA","ZULIA","MARACAIBO","NOSE","12234657","2025-06-08 00:00:00");




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
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO seccion VALUES("23","17","A","30","2025-06-08 14:33:00","1");
INSERT INTO seccion VALUES("24","18","B","20","2025-06-08 15:50:00","1");




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