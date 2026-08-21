use Illuminate\Support\Facades\Auth;
use App\Models\User;

if (!Auth::check()) {
    return redirect()->route('home');
}

$id_usuario = Auth::id();
$usuario = User::find($id_usuario);

// Obtener la foto del perfil o una imagen por defecto
$foto_perfil = $usuario->foto ?? asset('ruta/a/imagen_por_defecto.jpg');

// Obtener la foto del usuario en la sesión
if (!Auth::user()->rol || !Auth::user()->foto || Auth::user()->rol != 1) {
    return redirect()->route('home');
} else {
    $foto = asset('Assets/img/subidas/user.jpg');
}

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>SIGENOR</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('Assets/css/bootstrap-1.min.css') }}">
    <link rel="stylesheet" href="{{ asset('Assets/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('Assets/css/card.css') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('Assets/img/logo.png') }}">

    <!-- Google Fonts & Material Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">
    <link href="{{ asset('Assets/DataTables/css/datatables.css') }}" rel="stylesheet">

    <style>
        .custom-alert {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1050;
            background-color: white;
            border: 1px solid #ddd;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            padding: 20px;
            border-radius: 8px;
            display: none;
            max-width: 500px;
            width: 80%;
        }
        .custom-alert .close-btn {
            position: absolute;
            top: 10px;
            right: 15px;
            cursor: pointer;
            color: red;
            font-size: 20px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <!-- Loader -->
    <div id="loader" class="spinnerContainer">
        <div class="spinner"></div>
        <div class="loader">
            <p>loading</p>
            <div class="words">
                @foreach(['Periodos', 'Secciones', 'Estudiantes', 'Planteles', 'Asignaturas', 'Calificaciones', 'Asistencias', 'Plan administrativo'] as $word)
                    <span class="word">{{ $word }}</span>
                @endforeach
            </div>
        </div>
    </div>

    <div id="contenido">
        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Contenido principal -->
        <div id="content">
            <div class="top-navbar">
                <div class="xp-topbar">
                    <div class="row">
                        <div class="col-md-5 col-lg-3">
                            <div class="xp-searchbar">
                                <form id="searchForm">
                                    <div class="input-group">
                                        <input type="search" class="form-control" id="searchInput" placeholder="Buscar">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button" id="searchButton">Buscar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="custom-alert" id="customAlert">
                        <span class="close-btn" id="closeAlert">×</span>
                        <h5 class="text-center">Resultados</h5>
                        <div id="alertBody"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const alertElement = document.getElementById('customAlert');
            const closeAlertButton = document.getElementById('closeAlert');
            const searchButton = document.getElementById('searchButton');
            const alertTitle = document.getElementById('alertTitle');
            const alertBody = document.getElementById('alertBody');

            function showAlert(title, message, duration = 5000) {
                alertTitle.innerHTML = title;
                alertBody.innerHTML = message;
                alertElement.style.display = 'block';

                setTimeout(() => {
                    hideAlert();
                }, duration);
            }

            function hideAlert() {
                alertElement.style.display = 'none';
            }

            closeAlertButton.addEventListener('click', hideAlert);

            searchButton.addEventListener('click', function () {
                const searchTerm = document.getElementById('searchInput').value.trim();

                if (!searchTerm) {
                    showAlert('Error', '<p>Por favor, ingresa un término para buscar.</p>');
                    return;
                }

                fetch('/search', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ search: searchTerm }),
                })
                .then(response => response.text())
                .then(data => {
                    showAlert('RESULTADO DE LA BÚSQUEDA', data);
                })
                .catch(error => {
                    showAlert('Error', '<p>Ocurrió un error: ' + error.message + '</p>');
                });
            });
        });
    </script>
<div class="col-10 col-md-6 col-lg-8 order-1 order-md-3">
    <div class="xp-profilebar text-right">
        <nav class="navbar p-0">
            <ul class="nav navbar-nav flex-row ml-auto">   
                <!-- Notificaciones -->
                <li class="dropdown nav-item active">
                    <a href="#" class="nav-link" data-toggle="dropdown">
                        <span class="material-icons">notifications</span>
                        <span class="notification">4</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Tienes 5 nuevos mensajes</a></li>
                        <li><a href="#">Ahora eres amigo de Mike</a></li>
                        <li><a href="#">Felicita a Mary en su cumpleaños</a></li>
                        <li><a href="#">5 advertencias en la consola del servidor</a></li>
                    </ul>
                </li>
                
                <!-- Perfil de usuario -->
                <li class="nav-item dropdown">
                    <a class="nav-link" href="#" data-toggle="dropdown">
                        <img src="{{ asset('Views/profile/' . ucfirst(Auth::user()->foto)) }}" 
                             style="width:40px; height:40px; border-radius:50%;" />
                        <span class="xp-user-live"></span>
                    </a>
                    <ul class="dropdown-menu small-menu">
                        <li>
                            <a href="{{ route('profile.mostrar') }}">
                                <span class="material-icons">person_outline</span> Perfil
                            </a>
                        </li>
                        <li>
                            <a href="#"><span class="material-icons">settings</span> Configuración</a>
                        </li>
                        <li>
                            <a href="{{ route('logout') }}"><span class="material-icons">logout</span> Cerrar sesión</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</div>

<!-- Barra de bienvenida -->
<div class="xp-breadcrumbbar text-center">
    <h4 class="page-title">Bienvenido&nbsp;{{ ucfirst(Auth::user()->nombre_usuario) }}</h4>  
    <ol class="breadcrumb">
        <li class="breadcrumb-item">{{ ucfirst(Auth::user()->nombre_completo) }}</li>
    </ol>                
</div>

<div class="main-content">
    <div class="container">
        <div class="row">
            <!-- Estudiantes -->
            <div class="col-md-3">
                <div class="card-counter primary">
                    <i class="material-icons">sentiment_very_satisfied</i>
                    @php
                        $totalEstudiantes = \App\Models\Estudiante::count();
                    @endphp
                    <span class="count-numbers">{{ $totalEstudiantes }}</span>
                    <span class="count-name">Estudiantes</span>
                </div>
            </div>

            <!-- Profesores -->
            <div class="col-md-3">
                <div class="card-counter danger">
                    <i class="material-icons">psychology</i>
                    @php
                        $totalProfesores = \App\Models\Profesor::count();
                    @endphp
                    <span class="count-numbers">{{ $totalProfesores }}</span>
                    <span class="count-name">Profesores</span>
                </div>
            </div>

            <!-- Planteles -->
            <div class="col-md-3">
                <div class="card-counter success">
                    <i class="material-icons">supervisor_account</i>
                    @php
                        $totalPlanteles = \App\Models\Plantel::count();
                    @endphp
                    <span class="count-numbers">{{ $totalPlanteles }}</span>
                    <span class="count-name">Planteles</span>
                </div>
            </div>

            <!-- Usuarios -->
            <div class="col-md-3">
                <div class="card-counter info">
                    <i class="material-icons">person_outline</i>
                    @php
                        $totalUsuarios = \App\Models\User::count();
                    @endphp
                    <span class="count-numbers">{{ $totalUsuarios }}</span>
                    <span class="count-name">Usuarios</span>
                </div>
            </div>
        </div>

        <!-- Tarjeta de información del usuario -->
        <div class="row">
            <div class="col-sm-6 mb-3 mb-md-0">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ ucfirst(Auth::user()->nombre_completo) }}</h5>
                        <p class="card-text">Nombre de usuario: {{ ucfirst(Auth::user()->nombre_usuario) }}</p>
                        <p class="card-text">Correo: {{ Auth::user()->correo }}</p>
                        <p class="card-text">Rol: ADMINISTRADOR</p>
                        <a href="{{ route('profile.mostrar') }}" class="btn btn-primary">Configuración de la cuenta</a>
                        <a href="{{ route('logout') }}" class="btn btn-primary">Cerrar Sesión</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-sm-6">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Estudiantes recientes
                <a href="{{ route('students.mostrar') }}" class="btn btn-success btn-sm">Ver todos</a>
            </h5>

            @php
                $estudiantes = \App\Models\Estudiante::orderBy('cedula', 'ASC')->limit(3)->get();
            @endphp

            <table class="table table-striped table-hover">
                <thead class="thead-dark">
                    <tr>   
                        <th>Apellidos/Nombres</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($estudiantes as $estudiante)
                        <tr>
                            <td>{{ $estudiante->nombres }}</td>
                            <td>
                                @if($estudiante->estado == 1)
                                    <span class="badge badge-success">Estudiando</span>
                                @else
                                    <span class="badge badge-danger">No estudia</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="col-sm-6">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Profesores recientes
                <a href="{{ route('teachers.mostrar') }}" class="btn btn-success btn-sm">Ver todos</a>
            </h5>

            @php
                $profesores = \App\Models\Profesor::orderBy('cedula_profesor', 'ASC')->limit(3)->get();
            @endphp

            <table class="table table-striped table-hover">
                <thead class="thead-dark">
                    <tr>   
                        <th>Apellidos/Nombres</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($profesores as $profesor)
                        <tr>
                            <td>{{ $profesor->nombre_apellido }}</td>
                            <td>
                                @if($profesor->estado == 1)
                                    <span class="badge badge-success">Participa</span>
                                @else
                                    <span class="badge badge-danger">No participa</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Estudiantes recientes -->
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Estudiantes recientes
                            <a href="{{ route('students.mostrar') }}" class="btn btn-success btn-sm">Ver todos</a>
                        </h5>

                        @php
                            $estudiantes = \App\Models\Estudiante::orderBy('cedula', 'ASC')->get();
                        @endphp

                        @foreach($estudiantes as $estudiante)
                            @php
                                $hoy = date("m");
                                $fecha_cumple = $estudiante->fecha_nacimiento;
                                $nomstu = $estudiante->nombres;
                                
                                $dianac = date('d', strtotime($fecha_cumple));
                                $mes_cumple = date('m', strtotime($fecha_cumple));
                                $aniohoy = date('Y');
                                $nfecha = date($aniohoy.'-'.$mes_cumple.'-'.$dianac);
                                $diasfaltantes = (strtotime($nfecha) - strtotime(date("Y-m-d")));
                                $dfalta = floor($diasfaltantes / (60 * 60 * 24));
                            @endphp

                            @if ($hoy == $mes_cumple)
                                <span class="badge badge-success">
                                    <strong>Feliz</strong> Cumpleaños al Estudiante {{ $nomstu }}
                                </span>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('profileImage');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        setTimeout(function() {
            document.getElementById('loader').style.display = 'none';
            document.getElementById('contenido').style.display = 'block';
        }, 8000);
    </script>
</body>
</html>
