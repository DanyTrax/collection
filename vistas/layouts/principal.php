<?php
$tituloPagina = $titulo ?? 'Panel';
$usuarioNombre = $_SESSION['usuario_nombre'] ?? 'Usuario';
$uriActual = strtok($_SERVER['REQUEST_URI'] ?? '/', '?');

$esRuta = static function (string $ruta) use ($uriActual): bool {
    return $uriActual === $ruta || str_starts_with($uriActual, $ruta . '/');
};
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($tituloPagina) ?> | Gestión de Cuentas</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet" href="/adminlte/dist/css/adminlte.min.css">
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
<div class="app-wrapper">
    <nav class="app-header navbar navbar-expand bg-body shadow-sm border-bottom">
        <div class="container-fluid">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                        <i class="bi bi-list"></i>
                    </a>
                </li>
                <li class="nav-item d-none d-md-flex align-items-center">
                    <span class="nav-link text-secondary">
                        <i class="bi bi-person-circle me-1"></i><?= htmlspecialchars($usuarioNombre) ?>
                    </span>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/perfil">
                        <i class="bi bi-gear me-1"></i>Perfil
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="/logout">
                        <i class="bi bi-box-arrow-right me-1"></i>Salir
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <div class="sidebar-brand">
            <a href="/panel" class="brand-link">
                <span class="brand-text fw-light">Cuentas &amp; Cotizaciones</span>
            </a>
        </div>
        <div class="sidebar-wrapper">
            <nav class="mt-2">
                <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" id="navigation">
                    <li class="nav-item">
                        <a href="/panel" class="nav-link<?= $esRuta('/panel') ? ' active' : '' ?>">
                            <i class="nav-icon bi bi-speedometer2"></i>
                            <p>Panel Principal</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/clientes" class="nav-link<?= $esRuta('/clientes') ? ' active' : '' ?>">
                            <i class="nav-icon bi bi-people-fill"></i>
                            <p>Clientes</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/cotizaciones" class="nav-link<?= $esRuta('/cotizaciones') ? ' active' : '' ?>">
                            <i class="nav-icon bi bi-file-earmark-text"></i>
                            <p>Cotizaciones</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/cuentas" class="nav-link<?= $esRuta('/cuentas') ? ' active' : '' ?>">
                            <i class="nav-icon bi bi-receipt"></i>
                            <p>Cuentas de Cobro</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/perfil" class="nav-link<?= $esRuta('/perfil') ? ' active' : '' ?>">
                            <i class="nav-icon bi bi-person-gear"></i>
                            <p>Mi Perfil</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0"><?= htmlspecialchars($tituloPagina) ?></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="app-content">
            <div class="container-fluid">
                <?php if (!empty($_SESSION['flash_error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($_SESSION['flash_error']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>
                <?php unset($_SESSION['flash_error']); endif; ?>

                <?php if (!empty($_SESSION['flash_exito'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($_SESSION['flash_exito']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>
                <?php unset($_SESSION['flash_exito']); endif; ?>

                <?php if (!empty($_SESSION['flash_info'])): ?>
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($_SESSION['flash_info']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>
                <?php unset($_SESSION['flash_info']); endif; ?>

                <?= $contenido ?? '' ?>
            </div>
        </div>
    </main>

    <footer class="app-footer">
        <div class="float-end d-none d-sm-inline">Cuentas &amp; Cotizaciones</div>
        <strong>&copy; <?= date('Y') ?> Gestión de Cuentas de Cobro y Cotizaciones.</strong>
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"></script>
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
    window.addEventListener('load', () => {
        const sidebarWrapper = document.querySelector('.sidebar-wrapper');
        if (sidebarWrapper && window.OverlayScrollbars) {
            window.OverlayScrollbars(sidebarWrapper, {
                scrollbars: {
                    theme: 'os-theme-light',
                    autoHide: 'leave',
                }
            });
        }
    });

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
