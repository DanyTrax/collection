<?php
namespace Controladores;

use Modelos\Cliente;
use Modelos\CuentaCobro;
use Modelos\Emisor;

class CuentasControlador
{
    public function index(): void
    {
        $usuarioId = $this->obtenerUsuarioId();
        $cuentas = CuentaCobro::obtenerTodosPorUsuario($usuarioId);

        $this->render('cuentas/index', [
            'titulo' => 'Cuentas de cobro',
            'cuentas' => $cuentas,
        ]);
    }

    public function crearFormulario(): void
    {
        $usuarioId = $this->obtenerUsuarioId();
        $clientes = Cliente::obtenerTodosPorUsuario($usuarioId);
        $emisor = Emisor::obtenerPorUsuarioId($usuarioId);

        $this->render('cuentas/formulario', [
            'titulo' => 'Nueva cuenta de cobro',
            'cuenta' => null,
            'clientes' => $clientes,
            'emisor' => $emisor,
            'accion' => '/cuentas/crear',
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
            header('Location: /cuentas/crear');
            exit;
        }

        $datos = $this->mapearDatos($_POST, $usuarioId, $clienteId, $emisor->emisorID, $cliente, $emisor);
        CuentaCobro::crear($datos);

        $_SESSION['flash_exito'] = 'Cuenta de cobro registrada.';
        header('Location: /cuentas');
        exit;
    }

    public function editarFormulario(int $id): void
    {
        $usuarioId = $this->obtenerUsuarioId();
        $cuenta = CuentaCobro::obtenerPorId((int) $id, $usuarioId);

        if (!$cuenta) {
            $_SESSION['flash_error'] = 'Cuenta de cobro no encontrada.';
            header('Location: /cuentas');
            exit;
        }

        $clientes = Cliente::obtenerTodosPorUsuario($usuarioId);
        $emisor = Emisor::obtenerPorUsuarioId($usuarioId);

        $this->render('cuentas/formulario', [
            'titulo' => 'Editar cuenta de cobro',
            'cuenta' => $cuenta,
            'clientes' => $clientes,
            'emisor' => $emisor,
            'accion' => '/cuentas/editar/' . $id,
        ]);
    }

    public function editar(int $id): void
    {
        $usuarioId = $this->obtenerUsuarioId();
        $cuenta = CuentaCobro::obtenerPorId((int) $id, $usuarioId);

        if (!$cuenta) {
            $_SESSION['flash_error'] = 'Cuenta de cobro no encontrada.';
            header('Location: /cuentas');
            exit;
        }

        $clienteId = (int) ($_POST['ClienteID'] ?? 0);
        $emisor = Emisor::obtenerPorUsuarioId($usuarioId);
        $cliente = Cliente::obtenerPorId($clienteId, $usuarioId);

        if (!$cliente || !$emisor) {
            $_SESSION['flash_error'] = 'Debe seleccionar un cliente válido y completar su perfil de emisor.';
            header('Location: /cuentas/editar/' . $id);
            exit;
        }

        $datos = $this->mapearDatos($_POST, $usuarioId, $clienteId, $emisor->emisorID, $cliente, $emisor);
        CuentaCobro::actualizar((int) $id, $usuarioId, $datos);

        $_SESSION['flash_exito'] = 'Cuenta de cobro actualizada.';
        header('Location: /cuentas');
        exit;
    }

    public function borrar(): void
    {
        $usuarioId = $this->obtenerUsuarioId();
        $id = (int) ($_POST['id'] ?? 0);

        if ($id) {
            CuentaCobro::borrar($id, $usuarioId);
            $_SESSION['flash_exito'] = 'Cuenta de cobro eliminada.';
        }

        header('Location: /cuentas');
        exit;
    }

    public function imprimir(int $id): void
    {
        $usuarioId = $this->obtenerUsuarioId();
        $cuenta = CuentaCobro::obtenerPorId((int) $id, $usuarioId);

        if (!$cuenta) {
            http_response_code(404);
            echo 'Cuenta de cobro no encontrada';
            return;
        }

        $datosEmisor = $cuenta->datosEmisor ?: $this->snapshotEmisor(Emisor::obtenerPorUsuarioId($usuarioId));
        $datosCliente = $cuenta->datosCliente ?: $this->snapshotCliente(Cliente::obtenerPorId($cuenta->clienteID, $usuarioId));

        $this->render('cuentas/plantilla', [
            'cuenta' => $cuenta,
            'datosEmisor' => $datosEmisor,
            'datosCliente' => $datosCliente,
            'titulo' => 'Cuenta de cobro ' . $cuenta->numeroCuenta,
        ], 'invitado');
    }

    private function mapearDatos(array $post, int $usuarioId, int $clienteId, int $emisorId, Cliente $cliente, Emisor $emisor): array
    {
        $cotizacionId = trim($post['CotizacionID'] ?? '');
        $fechaVencimiento = trim($post['FechaVencimiento'] ?? '');
        return [
            'UsuarioID' => $usuarioId,
            'ClienteID' => $clienteId,
            'EmisorID' => $emisorId,
            'NumeroCuenta' => trim($post['NumeroCuenta'] ?? ''),
            'FechaEmision' => $post['FechaEmision'] ?? date('Y-m-d'),
            'FechaVencimiento' => $fechaVencimiento !== '' ? $fechaVencimiento : null,
            'Estado' => $post['Estado'] ?? 'Pendiente',
            'Concepto' => trim($post['Concepto'] ?? ''),
            'ValorTotal' => $this->normalizarMonto($post['ValorTotal'] ?? 0),
            'DatosEmisor' => $this->snapshotEmisor($emisor),
            'DatosCliente' => $this->snapshotCliente($cliente),
            'CotizacionID' => $cotizacionId !== '' ? (int) $cotizacionId : null,
        ];
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

    private function normalizarMonto($valor): float
    {
        if (is_numeric($valor)) {
            return (float) $valor;
        }

        if (is_string($valor)) {
            $limpio = str_replace(['$', ' '], '', $valor);
            $limpio = str_replace('.', '', $limpio);
            $limpio = str_replace(',', '.', $limpio);
            return (float) $limpio;
        }

        return 0.0;
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
