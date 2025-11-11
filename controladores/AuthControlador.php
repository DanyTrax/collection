<?php
namespace Controladores;

use Modelos\Usuario;
use Modelos\Emisor;

class AuthControlador
{
    public function mostrarLogin(): void
    {
        if (!empty($_SESSION['usuario_id'])) {
            header('Location: /panel');
            exit;
        }

        $this->render('auth/login', [
            'titulo' => 'Iniciar sesión',
            'error' => $_SESSION['flash_error'] ?? null,
        ], 'invitado');

        unset($_SESSION['flash_error']);
    }

    public function procesarLogin(): void
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (!$email || !$password) {
            $_SESSION['flash_error'] = 'Debe ingresar correo y contraseña.';
            header('Location: /login');
            exit;
        }

        $usuario = Usuario::buscarPorEmail($email);
        if (!$usuario || !password_verify($password, $usuario->passwordHash)) {
            $_SESSION['flash_error'] = 'Credenciales incorrectas.';
            header('Location: /login');
            exit;
        }

        $_SESSION['usuario_id'] = $usuario->usuarioID;
        $_SESSION['usuario_nombre'] = $usuario->nombre ?? $usuario->email;

        if (!Emisor::obtenerPorUsuarioId($usuario->usuarioID)) {
            Emisor::crearPorDefecto($usuario->usuarioID);
        }

        header('Location: /panel');
        exit;
    }

    public function cerrarSesion(): void
    {
        session_destroy();
        header('Location: /login');
        exit;
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
