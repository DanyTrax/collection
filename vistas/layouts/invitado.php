<?php
$tituloPagina = $titulo ?? 'Autenticación';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($tituloPagina) ?> | Gestión de Cuentas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="/adminlte/dist/css/adminlte.min.css">
    <style>
        .login-box {
            width: auto;
        }
    </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="/">Gestión <b>Cuentas</b></a>
    </div>
    <div class="card">
        <div class="card-body login-card-body">
            <?php if (!empty($_SESSION['flash_error'])): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($_SESSION['flash_error']) ?>
                </div>
            <?php unset($_SESSION['flash_error']); endif; ?>
            <?= $contenido ?? '' ?>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="/adminlte/dist/js/adminlte.min.js"></script>
</body>
</html>
