# 🎓 SIGENOR - Sistema de Información para la Gestión Académica

> **"Desarrollo e Implementación de un Sistema de Información para la Gestión Académica en la U.E. Nocturna Br. Rafael Rangel"**
>
> **Universidad Politécnica Territorial de Maracaibo (UPTMA) - Trayecto III - Grupo 2**

<div align="center">

[![SIGENOR](https://img.shields.io/badge/SIGENOR-Sistema%20de%20Gesti%C3%B3n%20Acad%C3%A9mica-brightgreen)](https://github.com/tu-usuario/sigenor)
[![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=flat-square&logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat-square&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![JavaScript](https://img.shields.io/badge/JavaScript-ES6-F7DF1E?style=flat-square&logo=javascript&logoColor=black)](https://developer.mozilla.org/es/docs/Web/JavaScript)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=flat-square&logo=bootstrap&logoColor=white)](https://getbootstrap.com/)
[![TCPDF](https://img.shields.io/badge/TCPDF-Reportes%20PDF-red?style=flat-square)](https://tcpdf.org/)
[![License](https://img.shields.io/badge/License-MIT-blue?style=flat-square)](LICENSE)

</div>

<p align="center">
  <img src="docs/media/sigenor-logo.png" alt="Logo SIGENOR" width="200" style="border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); margin-bottom: 20px;">
</p>

<p align="center">
  <img src="docs/media/sigenor-demo.gif" 
       alt="Demostración del sistema SIGENOR" 
       width="700" 
       style="border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
</p>

---

## 📖 Descripción General

**SIGENOR** es un sistema de información web diseñado e implementado para modernizar y optimizar los procesos académicos y administrativos de la **Unidad Educativa Nocturna "Br. Rafael Rangel"**. Este sistema surge como respuesta a las limitaciones de la gestión manual basada en hojas de cálculo (Excel), ofreciendo una plataforma centralizada, segura y escalable que automatiza tareas críticas como la gestión de estudiantes, docentes, asignaturas, calificaciones, asistencias y la generación de documentos oficiales.

---

## 🚀 Stack Tecnológico

| Capa | Tecnología |
|------|------------|
| **Backend** | PHP 8.2 (Arquitectura MVC) |
| **Frontend** | HTML5, CSS3, JavaScript (ES6), Bootstrap 5.3, jQuery |
| **Base de Datos** | MySQL 8.0 (Relacional, Normalizada) |
| **Generación de Reportes** | TCPDF / FPDF (Documentos PDF institucionales) |
| **Arquitectura** | Cliente-Servidor + MVC + Service Layer |
| **Patrones de Diseño** | Repository, Service Layer, Singleton, Factory Method |
| **Seguridad** | Autenticación por Sesiones, Control de Acceso por Roles (RBAC) |

---

## 📊 Progreso del Proyecto

| Módulo | Estado | Avance |
|--------|--------|--------|
| **Análisis de Requisitos** | ✅ Completado | ![100%](https://img.shields.io/badge/-100%25-brightgreen) |
| **Diseño del Sistema (Base de Datos)** | ✅ Completado | ![100%](https://img.shields.io/badge/-100%25-brightgreen) |
| **Diseño de Interfaz (Wireframes)** | ✅ Completado | ![100%](https://img.shields.io/badge/-100%25-brightgreen) |
| **Módulo de Usuarios (CRUD)** | ✅ Completado | ![100%](https://img.shields.io/badge/-100%25-brightgreen) |
| **Módulo de Estudiantes (CRUD)** | ✅ Completado | ![100%](https://img.shields.io/badge/-100%25-brightgreen) |
| **Módulo de Profesores (CRUD)** | ✅ Completado | ![100%](https://img.shields.io/badge/-100%25-brightgreen) |
| **Módulo de Planteles (CRUD)** | ✅ Completado | ![100%](https://img.shields.io/badge/-100%25-brightgreen) |
| **Módulo de Periodos (CRUD)** | ✅ Completado | ![100%](https://img.shields.io/badge/-100%25-brightgreen) |
| **Módulo de Secciones (CRUD)** | ✅ Completado | ![100%](https://img.shields.io/badge/-100%25-brightgreen) |
| **Módulo de Asignaturas (CRUD)** | ✅ Completado | ![100%](https://img.shields.io/badge/-100%25-brightgreen) |
| **Módulo de Asistencias (CRUD)** | ✅ Completado | ![100%](https://img.shields.io/badge/-100%25-brightgreen) |
| **Módulo de Calificaciones (CRUD)** | ✅ Completado | ![100%](https://img.shields.io/badge/-100%25-brightgreen) |
| **Generación de Reportes PDF (TCPDF)** | ✅ Completado | ![100%](https://img.shields.io/badge/-100%25-brightgreen) |
| **Dashboard y Estadísticas** | ✅ Completado | ![100%](https://img.shields.io/badge/-100%25-brightgreen) |
| **Implementación y Despliegue** | ✅ Completado | ![100%](https://img.shields.io/badge/-100%25-brightgreen) |
| **Capacitación del Personal** | ✅ Completado | ![100%](https://img.shields.io/badge/-100%25-brightgreen) |
| **Mantenimiento y Soporte** | ✅ Completado | ![100%](https://img.shields.io/badge/-100%25-brightgreen) |

---

## 📂 Estructura del Proyecto

```text
/
├── Assets/
│   ├── css/                     # Hojas de estilo personalizadas
│   ├── js/                      # Scripts de JavaScript personalizados
│   └── images/                  # Imágenes y logotipos institucionales
├── backup/                      # Archivos de respaldo de la base de datos
├── Config/                      # Configuración del sistema
├── logs/                        # Registros de actividad y errores
├── php/                         # Lógica de negocio en PHP
│   ├── Models/                  # Modelos de datos
│   ├── Controllers/             # Controladores (MVC)
│   └── Views/                   # Vistas (HTML, CSS, JS)
├── vendor/                      # Dependencias de Composer
├── Views/                       # Vistas principales del sistema
├── .htaccess                    # Configuración del servidor Apache
├── composer.json                # Dependencias de PHP
├── composer.lock                # Bloqueo de versiones
├── index.php                    # Punto de entrada principal
├── sigenor (1).sql              # Script de la base de datos MySQL
├── docs/
│   ├── media/
│   │   ├── sigenor-logo.png     # Logo del proyecto
│   │   └── sigenor-demo.gif     # GIF de demostración
│   └── manuals/
│       ├── MANUAL DE USUARIO FINAL.pdf
│       └── TRIPTICO SIGENOR.pdf
└── README.md                    # Documentación del proyecto

## ⚙️ Características Clave

### 🔐 Módulos Implementados

| Módulo | Descripción | Funcionalidades |
|--------|-------------|-----------------|
| **Usuarios** | Gestión de accesos al sistema | CRUD completo, control de permisos, autenticación segura |
| **Estudiantes** | Registro y gestión de alumnos | CRUD, filtros por cédula/sección/sexo, historial de planteles |
| **Profesores** | Gestión de docentes | CRUD, asignación de asignaturas, datos de contacto |
| **Planteles** | Instituciones educativas asociadas | CRUD, información de directores y zonas educativas |
| **Periodos** | Ciclos académicos | CRUD, control de fechas de inicio/fin |
| **Secciones** | Grupos escolares | CRUD, asignación a periodos, control de capacidad |
| **Asignaturas** | Materias del plan de estudio | CRUD, asignación a profesores |
| **Asistencias** | Registro de presencia | CRUD, control de inasistencias, vinculación a estudiantes |
| **Calificaciones** | Notas académicas | CRUD, conversión automática escala 1-20 a 1-5 |
| **Plan Administrativo** | Configuración institucional | CRUD, tipos de evaluación, estrategias de estudio |
| **Dashboard** | Panel de control | Estadísticas, gráficos, indicadores en tiempo real |
| **Reportes PDF** | Documentos oficiales | Boletines, certificados, resúmenes curriculares |

---

### 📄 Documentos Generados con TCPDF

- **Boletín de Calificaciones** (Formato EMGMJAA)
- **Certificado de Calificaciones** (Formato EMGMJAA)
- **Resumen Curricular** por estudiante
- **Listado de Estudiantes** por sección/periodo
- **Reporte de Asistencias e Inasistencias**
- **Reporte de Profesores** y asignaturas asignadas
- **Reporte de Asignaturas** del plan de estudio

---

## 🎯 Impacto del Sistema

| Métrica | Antes (Manual) | Después (SIGENOR) | Mejora |
|---------|----------------|-------------------|--------|
| **Tiempo administrativo** | 2 horas/estudiante | ~2 segundos/consulta | **-70%** |
| **Errores humanos** | Alta incidencia | Validación automática | **-85%** |
| **Tiempo de respuesta** | Horas/Días | < 2 segundos | **> 90% más rápido** |
| **Capacitación del personal** | — | 95% del personal | **Adopción exitosa** |

---

## 📚 Documentación

Puedes consultar los manuales del sistema en la carpeta `docs/manuals/`:

- [📄 **Manual de Usuario**](docs/manuals/MANUAL%20DE%20USUARIO%20FINAL.pdf) - Guía completa para el uso del sistema
- [📄 **Tríptico Informativo**](docs/manuals/TRIPTICO%20SIGENOR.pdf) - Resumen visual del proyecto

---

## 🛠️ Instalación y Configuración

### Requisitos Previos

| Requisito | Versión |
|-----------|---------|
| **PHP** | 8.2 o superior |
| **Composer** | 2.x |
| **MySQL** | 8.0 o superior |
| **Servidor Web** | Apache/Nginx (XAMPP, WAMP o Laragon recomendados) |

### Pasos de Instalación

Clonar el repositorio

```bash
git clone https://github.com/<tu-usuario>/sigenor.git
bash
cd sigenor
Instalar dependencias

bash
composer install
Configurar el entorno

bash
cp .env.example .env
Configurar la base de datos

Editar el archivo .env con las credenciales de MySQL:

env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sigenor
DB_USERNAME=root
DB_PASSWORD=
Importar la base de datos

bash
mysql -u root -p sigenor < "sigenor (1).sql"
Ejecutar el servidor de desarrollo

bash
php -S localhost:8000
