# 🎓 SIGENOR - Sistema de Información de Gestión Académica (SGA)
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
  <img src="docs/media/logo-sistem.png" alt="Logo SIGENOR" width="200" style="border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); margin-bottom: 20px;">
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
```

<!-- ⚙️ Características Clave -->
<h2>⚙️ Características Clave</h2>

<h3>🔐 Módulos Implementados</h3>

<table align="center">
  <thead>
    <tr>
      <th>Módulo</th>
      <th>Descripción</th>
      <th>Funcionalidades</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><strong>Usuarios</strong></td>
      <td>Gestión de accesos al sistema</td>
      <td>CRUD completo, control de permisos, autenticación segura</td>
    </tr>
    <tr>
      <td><strong>Estudiantes</strong></td>
      <td>Registro y gestión de alumnos</td>
      <td>CRUD, filtros por cédula/sección/sexo, historial de planteles</td>
    </tr>
    <tr>
      <td><strong>Profesores</strong></td>
      <td>Gestión de docentes</td>
      <td>CRUD, asignación de asignaturas, datos de contacto</td>
    </tr>
    <tr>
      <td><strong>Planteles</strong></td>
      <td>Instituciones educativas asociadas</td>
      <td>CRUD, información de directores y zonas educativas</td>
    </tr>
    <tr>
      <td><strong>Periodos</strong></td>
      <td>Ciclos académicos</td>
      <td>CRUD, control de fechas de inicio/fin</td>
    </tr>
    <tr>
      <td><strong>Secciones</strong></td>
      <td>Grupos escolares</td>
      <td>CRUD, asignación a periodos, control de capacidad</td>
    </tr>
    <tr>
      <td><strong>Asignaturas</strong></td>
      <td>Materias del plan de estudio</td>
      <td>CRUD, asignación a profesores</td>
    </tr>
    <tr>
      <td><strong>Asistencias</strong></td>
      <td>Registro de presencia</td>
      <td>CRUD, control de inasistencias, vinculación a estudiantes</td>
    </tr>
    <tr>
      <td><strong>Calificaciones</strong></td>
      <td>Notas académicas</td>
      <td>CRUD, conversión automática escala 1-20 a 1-5</td>
    </tr>
    <tr>
      <td><strong>Plan Administrativo</strong></td>
      <td>Configuración institucional</td>
      <td>CRUD, tipos de evaluación, estrategias de estudio</td>
    </tr>
    <tr>
      <td><strong>Dashboard</strong></td>
      <td>Panel de control</td>
      <td>Estadísticas, gráficos, indicadores en tiempo real</td>
    </tr>
    <tr>
      <td><strong>Reportes PDF</strong></td>
      <td>Documentos oficiales</td>
      <td>Boletines, certificados, resúmenes curriculares</td>
    </tr>
  </tbody>
</table>

<!-- 📄 Documentos Generados con TCPDF -->
<h3>📄 Documentos Generados con TCPDF</h3>

<ul>
  <li><strong>Boletín de Calificaciones</strong> (Formato EMGMJAA)</li>
  <li><strong>Certificado de Calificaciones</strong> (Formato EMGMJAA)</li>
  <li><strong>Resumen Curricular</strong> por estudiante</li>
  <li><strong>Listado de Estudiantes</strong> por sección/periodo</li>
  <li><strong>Reporte de Asistencias e Inasistencias</strong></li>
  <li><strong>Reporte de Profesores</strong> y asignaturas asignadas</li>
  <li><strong>Reporte de Asignaturas</strong> del plan de estudio</li>
</ul>

<!-- 🎯 Impacto del Sistema -->
<h2>🎯 Impacto del Sistema</h2>

<table align="center">
  <thead>
    <tr>
      <th>Métrica</th>
      <th>Antes (Manual)</th>
      <th>Después (SIGENOR)</th>
      <th>Mejora</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><strong>Tiempo administrativo</strong></td>
      <td>2 horas/estudiante</td>
      <td>~2 segundos/consulta</td>
      <td><strong>-70%</strong></td>
    </tr>
    <tr>
      <td><strong>Errores humanos</strong></td>
      <td>Alta incidencia</td>
      <td>Validación automática</td>
      <td><strong>-85%</strong></td>
    </tr>
    <tr>
      <td><strong>Tiempo de respuesta</strong></td>
      <td>Horas/Días</td>
      <td>&lt; 2 segundos</td>
      <td><strong>&gt; 90% más rápido</strong></td>
    </tr>
    <tr>
      <td><strong>Capacitación del personal</strong></td>
      <td>—</td>
      <td>95% del personal</td>
      <td><strong>Adopción exitosa</strong></td>
    </tr>
  </tbody>
</table>

<!-- 📚 Documentación -->
<h2>📚 Documentación</h2>

<p>Puedes consultar los manuales del sistema en la carpeta <code>docs/manuals/</code>:</p>

<ul>
  <li>📄 <a href="docs/manuals/MANUAL_SIGENOR.pdf"><strong>Manual de Usuario</strong></a> - Guía completa para el uso del sistema</li>
  <li>📄 <a href="docs/manuals/TRIPTICOS_SIGENOR.pdf"><strong>Tríptico Informativo</strong></a> - Resumen visual del proyecto</li>
</ul>

<!-- 🛠️ Instalación y Configuración -->
<h2>🛠️ Instalación y Configuración</h2>

<h3>Requisitos Previos</h3>

<table align="center">
  <thead>
    <tr>
      <th>Requisito</th>
      <th>Versión</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><strong>PHP</strong></td>
      <td>8.2 o superior</td>
    </tr>
    <tr>
      <td><strong>Composer</strong></td>
      <td>2.x</td>
    </tr>
    <tr>
      <td><strong>MySQL</strong></td>
      <td>8.0 o superior</td>
    </tr>
    <tr>
      <td><strong>Servidor Web</strong></td>
      <td>Apache/Nginx (XAMPP, WAMP o Laragon recomendados)</td>
    </tr>
  </tbody>
</table>

<h3>Pasos de Instalación</h3>

<p><strong>Clonar el repositorio</strong></p>

<pre><code>git clone https://github.com/&lt;tu-usuario&gt;/sigenor.git
cd sigenor
</code></pre>

<p><strong>Instalar dependencias</strong></p>

<pre><code>composer install
</code></pre>

<p><strong>Configurar el entorno</strong></p>

<pre><code>cp .env.example .env
</code></pre>

<p><strong>Configurar la base de datos</strong></p>

<p>Editar el archivo <code>.env</code> con las credenciales de MySQL:</p>

<pre><code>DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sigenor
DB_USERNAME=root
DB_PASSWORD=
</code></pre>

<p><strong>Importar la base de datos</strong></p>

<pre><code>mysql -u root -p sigenor &lt; "sigenor (1).sql"
</code></pre>

<p><strong>Ejecutar el servidor de desarrollo</strong></p>

<pre><code>php -S localhost:8000
</code></pre>

<!-- Licencia -->
<h2>📜 Licencia</h2>

<p>Este proyecto está bajo la licencia <strong>MIT</strong>. Ver archivo <a href="LICENSE">LICENSE</a> para más detalles.</p>
