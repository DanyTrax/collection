<?php
$tituloPagina = $titulo ?? 'Autenticación';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($tituloPagina) ?> | Gestión de Cuentas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KYhE8Zt6Y4Z4Yk0s5tF5c5JcO0l6+h7xX6c6+7k4TqX9W0E6V0F4jZ6w==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="/adminlte/dist/css/adminlte.min.css">
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
