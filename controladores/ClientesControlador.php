<?php
namespace Controladores;

use Modelos\Cliente;

class ClientesControlador
{
    public function index(): void
    {
        $usuarioId = $this->obtenerUsuarioId();
        $clientes = Cliente::obtenerTodosPorUsuario($usuarioId);

        $this->render('clientes/index', [
            'titulo' => 'Clientes',
            'clientes' => $clientes,
        ]);
    }

    public function crearFormulario(): void
    {
        $this->obtenerUsuarioId();
        $this->render('clientes/formulario', [
            'titulo' => 'Nuevo cliente',
            'cliente' => null,
            'accion' => '/clientes/crear',
        ]);
    }

    public function crear(): void
    {
        $usuarioId = $this->obtenerUsuarioId();
        $datos = $this->filtrarDatos($_POST, $usuarioId);
        Cliente::crear($datos);
        $_SESSION['flash_exito'] = 'Cliente creado con éxito.';
        header('Location: /clientes');
        exit;
    }

    public function editarFormulario(int $id): void
    {
        $usuarioId = $this->obtenerUsuarioId();
        $cliente = Cliente::obtenerPorId((int) $id, $usuarioId);

        if (!$cliente) {
            $_SESSION['flash_error'] = 'Cliente no encontrado.';
            header('Location: /clientes');
            exit;
        }

        $this->render('clientes/formulario', [
            'titulo' => 'Editar cliente',
            'cliente' => $cliente,
            'accion' => '/clientes/editar/' . $id,
        ]);
    }

    public function editar(int $id): void
    {
        $usuarioId = $this->obtenerUsuarioId();
        $cliente = Cliente::obtenerPorId((int) $id, $usuarioId);

        if (!$cliente) {
            $_SESSION['flash_error'] = 'Cliente no encontrado.';
            header('Location: /clientes');
            return;
        }

        $datos = $this->filtrarDatos($_POST, $usuarioId);
        Cliente::actualizar((int) $id, $usuarioId, $datos);
        $_SESSION['flash_exito'] = 'Cliente actualizado correctamente.';
        header('Location: /clientes');
        exit;
    }

    public function borrar(): void
    {
        $usuarioId = $this->obtenerUsuarioId();
        $id = (int) ($_POST['id'] ?? 0);

        if ($id) {
            Cliente::borrar($id, $usuarioId);
            $_SESSION['flash_exito'] = 'Cliente eliminado.';
        }

        header('Location: /clientes');
        exit;
    }

    private function filtrarDatos(array $post, int $usuarioId): array
    {
        return [
            'UsuarioID' => $usuarioId,
            'NombreCliente' => trim($post['NombreCliente'] ?? ''),
            'NIT_CC' => trim($post['NIT_CC'] ?? ''),
            'Direccion' => trim($post['Direccion'] ?? ''),
            'Email' => trim($post['Email'] ?? ''),
            'Telefono' => trim($post['Telefono'] ?? ''),
            'Ciudad' => trim($post['Ciudad'] ?? ''),
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
