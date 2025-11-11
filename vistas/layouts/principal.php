<?php
$tituloPagina = $titulo ?? 'Panel';
$usuarioNombre = $_SESSION['usuario_nombre'] ?? 'Usuario';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($tituloPagina) ?> | Gestión de Cuentas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KYhE8Zt6Y4Z4Yk0s5tF5c5JcO0l6+h7xX6c6+7k4TqX9W0E6V0F4jZ6w==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet" href="/adminlte/dist/css/adminlte.min.css">
</head>
<body class="layout-fixed sidebar-mini">
<div class="wrapper">
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-user"></i> <?= htmlspecialchars($usuarioNombre) ?>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="/perfil" class="dropdown-item"><i class="fas fa-id-card mr-2"></i> Mi perfil</a>
                    <div class="dropdown-divider"></div>
                    <a href="/logout" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt mr-2"></i> Cerrar sesión</a>
                </div>
            </li>
        </ul>
    </nav>

    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="/panel" class="brand-link">
            <span class="brand-text font-weight-light">Cuentas &amp; Cotizaciones</span>
        </a>
        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" role="menu">
                    <li class="nav-item"><a href="/panel" class="nav-link"><i class="nav-icon fas fa-tachometer-alt"></i><p>Panel Principal</p></a></li>
                    <li class="nav-item"><a href="/clientes" class="nav-link"><i class="nav-icon fas fa-users"></i><p>Clientes</p></a></li>
                    <li class="nav-item"><a href="/cotizaciones" class="nav-link"><i class="nav-icon fas fa-file-alt"></i><p>Cotizaciones</p></a></li>
                    <li class="nav-item"><a href="/cuentas" class="nav-link"><i class="nav-icon fas fa-file-invoice"></i><p>Cuentas de Cobro</p></a></li>
                    <li class="nav-item"><a href="/perfil" class="nav-link"><i class="nav-icon fas fa-user-cog"></i><p>Mi Perfil</p></a></li>
                </ul>
            </nav>
        </div>
    </aside>

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark"><?= htmlspecialchars($tituloPagina) ?></h1>
                    </div>
                </div>
                <?php if (!empty($_SESSION['flash_error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($_SESSION['flash_error']) ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php unset($_SESSION['flash_error']); endif; ?>

                <?php if (!empty($_SESSION['flash_exito'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($_SESSION['flash_exito']) ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php unset($_SESSION['flash_exito']); endif; ?>

                <?php if (!empty($_SESSION['flash_info'])): ?>
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($_SESSION['flash_info']) ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php unset($_SESSION['flash_info']); endif; ?>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <?= $contenido ?? '' ?>
            </div>
        </section>
    </div>

    <footer class="main-footer text-sm text-center">
        <strong>&copy; <?= date('Y') ?> Gestión de Cuentas de Cobro y Cotizaciones.</strong>
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jszip@3.10.1/dist/jszip.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/pdfmake@0.2.7/build/pdfmake.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/pdfmake@0.2.7/build/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="/adminlte/dist/js/adminlte.min.js"></script>
<script>
    $(function () {
        $('.tabla-datatable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
            },
            responsive: true,
            autoWidth: false,
            pageLength: 10
        });
    });
</script>
</body>
</html>
