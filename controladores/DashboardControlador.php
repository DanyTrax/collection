<?php
namespace Controladores;

use Config\BaseDeDatos;

class DashboardControlador
{
    public function index(): void
    {
        $usuarioId = $this->obtenerUsuarioId();
        $pdo = BaseDeDatos::obtenerConexion();

        $conteos = [
            'clientes' => $this->contar($pdo, 'SELECT COUNT(*) FROM Clientes WHERE UsuarioID = :usuario', $usuarioId),
            'cotizaciones' => $this->contar($pdo, 'SELECT COUNT(*) FROM Cotizaciones WHERE UsuarioID = :usuario', $usuarioId),
            'cuentas' => $this->contar($pdo, 'SELECT COUNT(*) FROM CuentasDeCobro WHERE UsuarioID = :usuario', $usuarioId),
        ];

        $this->render('dashboard/index', [
            'titulo' => 'Panel principal',
            'conteos' => $conteos,
        ]);
    }

    private function contar(\PDO $pdo, string $sql, int $usuarioId): int
    {
        $consulta = $pdo->prepare($sql);
        $consulta->execute(['usuario' => $usuarioId]);
        return (int) $consulta->fetchColumn();
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
