<?php declare(strict_types=1);

use Controladores\AuthControlador;
use Controladores\ClientesControlador;
use Controladores\CotizacionesControlador;
use Controladores\CuentasControlador;
use Controladores\DashboardControlador;
use Controladores\EmisorControlador;
use Nucleo\Router;

session_start();

define('BASE_PATH', realpath(__DIR__));

require_once BASE_PATH . '/config/BaseDeDatos.php';
require_once BASE_PATH . '/nucleo/Router.php';

spl_autoload_register(function (string $clase): void {
    $mapaBases = [
        'Controladores\\' => BASE_PATH . '/controladores/',
        'Modelos\\' => BASE_PATH . '/modelos/',
        'Config\\' => BASE_PATH . '/config/',
        'Nucleo\\' => BASE_PATH . '/nucleo/',
    ];

    foreach ($mapaBases as $prefijo => $directorio) {
        if (str_starts_with($clase, $prefijo)) {
            $rutaRelativa = substr($clase, strlen($prefijo));
            $ruta = $directorio . str_replace('\\', '/', $rutaRelativa) . '.php';
            if (file_exists($ruta)) {
                require_once $ruta;
            }
            return;
        }
    }
});

$router = new Router();

$authControlador = new AuthControlador();
$dashboardControlador = new DashboardControlador();
$clientesControlador = new ClientesControlador();
$cotizacionesControlador = new CotizacionesControlador();
$cuentasControlador = new CuentasControlador();
$emisorControlador = new EmisorControlador();

$router->get('/', function () {
    if (!empty($_SESSION['usuario_id'])) {
        header('Location: /panel');
    } else {
        header('Location: /login');
    }
    exit;
});

$router->get('/login', [$authControlador, 'mostrarLogin']);
$router->post('/login', [$authControlador, 'procesarLogin']);
$router->get('/logout', [$authControlador, 'cerrarSesion']);

$router->get('/panel', [$dashboardControlador, 'index']);

$router->get('/clientes', [$clientesControlador, 'index']);
$router->get('/clientes/crear', [$clientesControlador, 'crearFormulario']);
$router->post('/clientes/crear', [$clientesControlador, 'crear']);
$router->get('/clientes/editar/{id}', function ($id) use ($clientesControlador) {
    $clientesControlador->editarFormulario((int) $id);
});
$router->post('/clientes/editar/{id}', function ($id) use ($clientesControlador) {
    $clientesControlador->editar((int) $id);
});
$router->post('/clientes/borrar', [$clientesControlador, 'borrar']);

$router->get('/perfil', [$emisorControlador, 'perfil']);
$router->post('/perfil', [$emisorControlador, 'actualizar']);

$router->get('/cotizaciones', [$cotizacionesControlador, 'index']);
$router->get('/cotizaciones/crear', [$cotizacionesControlador, 'crearFormulario']);
$router->post('/cotizaciones/crear', [$cotizacionesControlador, 'crear']);
$router->get('/cotizaciones/editar/{id}', function ($id) use ($cotizacionesControlador) {
    $cotizacionesControlador->editarFormulario((int) $id);
});
$router->post('/cotizaciones/editar/{id}', function ($id) use ($cotizacionesControlador) {
    $cotizacionesControlador->editar((int) $id);
});
$router->post('/cotizaciones/borrar', [$cotizacionesControlador, 'borrar']);
$router->get('/cotizaciones/imprimir/{id}', function ($id) use ($cotizacionesControlador) {
    $cotizacionesControlador->imprimir((int) $id);
});
$router->get('/cotizaciones/pdf/{id}', function ($id) use ($cotizacionesControlador) {
    $cotizacionesControlador->pdf((int) $id);
});
$router->post('/cotizaciones/convertir/{id}', function ($id) use ($cotizacionesControlador) {
    $cotizacionesControlador->convertir((int) $id);
});

$router->get('/cuentas', [$cuentasControlador, 'index']);
$router->get('/cuentas/crear', [$cuentasControlador, 'crearFormulario']);
$router->post('/cuentas/crear', [$cuentasControlador, 'crear']);
$router->get('/cuentas/editar/{id}', function ($id) use ($cuentasControlador) {
    $cuentasControlador->editarFormulario((int) $id);
});
$router->post('/cuentas/editar/{id}', function ($id) use ($cuentasControlador) {
    $cuentasControlador->editar((int) $id);
});
$router->post('/cuentas/borrar', [$cuentasControlador, 'borrar']);
$router->get('/cuentas/imprimir/{id}', function ($id) use ($cuentasControlador) {
    $cuentasControlador->imprimir((int) $id);
});
$router->get('/cuentas/pdf/{id}', function ($id) use ($cuentasControlador) {
    $cuentasControlador->pdf((int) $id);
});

$router->resolver($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
