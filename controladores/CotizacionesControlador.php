<?php
namespace Controladores;

use Modelos\Cliente;
use Modelos\Cotizacion;
use Modelos\Emisor;
use Modelos\CuentaCobro;

class CotizacionesControlador
{
    public function index(): void
    {
        $usuarioId = $this->obtenerUsuarioId();
        $cotizaciones = Cotizacion::obtenerTodosPorUsuario($usuarioId);

        $this->render('cotizaciones/index', [
            'titulo' => 'Cotizaciones',
            'cotizaciones' => $cotizaciones,
        ]);
    }

    public function crearFormulario(): void
    {
        $usuarioId = $this->obtenerUsuarioId();
        $clientes = Cliente::obtenerTodosPorUsuario($usuarioId);
        $emisor = Emisor::obtenerPorUsuarioId($usuarioId);

        $this->render('cotizaciones/formulario', [
            'titulo' => 'Nueva cotización',
            'cotizacion' => null,
            'clientes' => $clientes,
            'emisor' => $emisor,
            'accion' => '/cotizaciones/crear',
        ]);
    }

    public function crear(): void
    {
        $usuarioId = $this->obtenerUsuarioId();
        $clienteId = (int) ($_POST['ClienteID'] ?? 0);
        $emisor = Emisor::obtenerPorUsuarioId($usuarioId);
        $cliente = Cliente::obtenerPorId($clienteId, $usuarioId);

        if (!$cliente || !$emisor) {
            $_SESSION['flash_error'] = 'Debe seleccionar un cliente válido y completar su perfil de emisor.';
            header('Location: /cotizaciones/crear');
            exit;
        }

        $datos = $this->mapearDatos($_POST, $usuarioId, $clienteId, $emisor->emisorID, $cliente, $emisor);
        Cotizacion::crear($datos);

        $_SESSION['flash_exito'] = 'Cotización creada con éxito.';
        header('Location: /cotizaciones');
        exit;
    }

    public function editarFormulario(int $id): void
    {
        $usuarioId = $this->obtenerUsuarioId();
        $cotizacion = Cotizacion::obtenerPorId((int) $id, $usuarioId);

        if (!$cotizacion) {
            $_SESSION['flash_error'] = 'Cotización no encontrada.';
            header('Location: /cotizaciones');
            exit;
        }

        $clientes = Cliente::obtenerTodosPorUsuario($usuarioId);
        $emisor = Emisor::obtenerPorUsuarioId($usuarioId);

        $this->render('cotizaciones/formulario', [
            'titulo' => 'Editar cotización',
            'cotizacion' => $cotizacion,
            'clientes' => $clientes,
            'emisor' => $emisor,
            'accion' => '/cotizaciones/editar/' . $id,
        ]);
    }

    public function editar(int $id): void
    {
        $usuarioId = $this->obtenerUsuarioId();
        $cotizacion = Cotizacion::obtenerPorId((int) $id, $usuarioId);

        if (!$cotizacion) {
            $_SESSION['flash_error'] = 'Cotización no encontrada.';
            header('Location: /cotizaciones');
            exit;
        }

        $clienteId = (int) ($_POST['ClienteID'] ?? 0);
        $emisor = Emisor::obtenerPorUsuarioId($usuarioId);
        $cliente = Cliente::obtenerPorId($clienteId, $usuarioId);

        if (!$cliente || !$emisor) {
            $_SESSION['flash_error'] = 'Debe seleccionar un cliente válido y completar su perfil de emisor.';
            header('Location: /cotizaciones/editar/' . $id);
            exit;
        }

        $datos = $this->mapearDatos($_POST, $usuarioId, $clienteId, $emisor->emisorID, $cliente, $emisor);
        Cotizacion::actualizar((int) $id, $usuarioId, $datos);

        $_SESSION['flash_exito'] = 'Cotización actualizada.';
        header('Location: /cotizaciones');
        exit;
    }

    public function borrar(): void
    {
        $usuarioId = $this->obtenerUsuarioId();
        $id = (int) ($_POST['id'] ?? 0);

        if ($id) {
            Cotizacion::borrar($id, $usuarioId);
            $_SESSION['flash_exito'] = 'Cotización eliminada.';
        }

        header('Location: /cotizaciones');
        exit;
    }

    public function imprimir(int $id): void
    {
        $usuarioId = $this->obtenerUsuarioId();
        $cotizacion = Cotizacion::obtenerPorId((int) $id, $usuarioId);

        if (!$cotizacion) {
            http_response_code(404);
            echo 'Cotización no encontrada';
            return;
        }

        $datosEmisor = $cotizacion->datosEmisor ?: $this->snapshotEmisor(Emisor::obtenerPorUsuarioId($usuarioId));
        $datosCliente = $cotizacion->datosCliente ?: $this->snapshotCliente(Cliente::obtenerPorId($cotizacion->clienteID, $usuarioId));

        $this->render('cotizaciones/plantilla', [
            'cotizacion' => $cotizacion,
            'datosEmisor' => $datosEmisor,
            'datosCliente' => $datosCliente,
            'titulo' => 'Cotización ' . $cotizacion->numeroCotizacion,
        ], 'invitado');
    }

    public function convertir(int $id): void
    {
        $usuarioId = $this->obtenerUsuarioId();
        $cotizacion = Cotizacion::obtenerPorId((int) $id, $usuarioId);

        if (!$cotizacion) {
            $_SESSION['flash_error'] = 'Cotización no encontrada.';
            header('Location: /cotizaciones');
            exit;
        }

        $numeroCuenta = trim($_POST['NumeroCuenta'] ?? '');
        if ($numeroCuenta === '') {
            $_SESSION['flash_error'] = 'Debe indicar un número para la cuenta de cobro.';
            header('Location: /cotizaciones');
            exit;
        }

        $datosCuenta = [
            'UsuarioID' => $usuarioId,
            'ClienteID' => $cotizacion->clienteID,
            'EmisorID' => $cotizacion->emisorID,
            'NumeroCuenta' => $numeroCuenta,
            'FechaEmision' => $_POST['FechaEmision'] ?? date('Y-m-d'),
            'FechaVencimiento' => $_POST['FechaVencimiento'] ?? null,
            'Estado' => 'Pendiente',
            'Concepto' => $cotizacion->concepto,
            'ValorTotal' => $cotizacion->valorTotal,
        ];

        CuentaCobro::desdeCotizacion($cotizacion, $datosCuenta);
        Cotizacion::actualizarEstado($cotizacion->cotizacionID, $usuarioId, 'Aprobada');

        $_SESSION['flash_exito'] = 'Cotización convertida a cuenta de cobro.';
        header('Location: /cuentas');
        exit;
    }

    private function mapearDatos(array $post, int $usuarioId, int $clienteId, int $emisorId, Cliente $cliente, Emisor $emisor): array
    {
        $fechaVencimiento = trim($post['FechaVencimiento'] ?? '');
        return [
            'UsuarioID' => $usuarioId,
            'ClienteID' => $clienteId,
            'EmisorID' => $emisorId,
            'NumeroCotizacion' => trim($post['NumeroCotizacion'] ?? ''),
            'FechaEmision' => $post['FechaEmision'] ?? date('Y-m-d'),
            'FechaVencimiento' => $fechaVencimiento !== '' ? $fechaVencimiento : null,
            'Estado' => $post['Estado'] ?? 'Pendiente',
            'Concepto' => trim($post['Concepto'] ?? ''),
            'ValorTotal' => $this->normalizarMonto($post['ValorTotal'] ?? 0),
            'Terminos' => trim($post['Terminos'] ?? ''),
            'DatosEmisor' => $this->snapshotEmisor($emisor),
            'DatosCliente' => $this->snapshotCliente($cliente),
        ];
    }

    private function normalizarMonto($valor): float
    {
        if (is_numeric($valor)) {
            return (float) $valor;
        }

        if (is_string($valor)) {
            $limpio = str_replace(['$', ' '], '', $valor);
            // Remover separadores de miles y estandarizar decimales
            $limpio = str_replace('.', '', $limpio);
            $limpio = str_replace(',', '.', $limpio);
            return (float) $limpio;
        }

        return 0.0;
    }

    private function snapshotEmisor(?Emisor $emisor): array
    {
        if (!$emisor) {
            return [];
        }

        return [
            'NombreCompleto' => $emisor->nombreCompleto,
            'DocumentoIdentidad' => $emisor->documentoIdentidad,
            'Email' => $emisor->email,
            'Telefono' => $emisor->telefono,
            'Direccion' => $emisor->direccion,
            'Ciudad' => $emisor->ciudad,
            'InformacionBancaria' => $emisor->informacionBancaria,
            'NotaLegal' => $emisor->notaLegal,
        ];
    }

    private function snapshotCliente(?Cliente $cliente): array
    {
        if (!$cliente) {
            return [];
        }

        return [
            'NombreCliente' => $cliente->nombreCliente,
            'NIT_CC' => $cliente->nitCc,
            'Direccion' => $cliente->direccion,
            'Email' => $cliente->email,
            'Telefono' => $cliente->telefono,
            'Ciudad' => $cliente->ciudad,
        ];
    }

    private function obtenerUsuarioId(): int
    {
        if (empty($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }
        return (int) $_SESSION['usuario_id'];
    }

    private function render(string $vista, array $datos = [], string $layout = 'principal'): void
    {
        extract($datos);
        $rutaVista = __DIR__ . '/../vistas/' . $vista . '.php';
        $rutaLayout = __DIR__ . '/../vistas/layouts/' . $layout . '.php';

        if (!file_exists($rutaVista)) {
            throw new \RuntimeException('Vista no encontrada: ' . $vista);
        }

        ob_start();
        include $rutaVista;
        $contenido = ob_get_clean();

        include $rutaLayout;
    }
}
