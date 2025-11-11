<?php
namespace Controladores;

use Modelos\Emisor;

class EmisorControlador
{
    public function perfil(): void
    {
        $usuarioId = $this->obtenerUsuarioId();
        $emisor = Emisor::obtenerPorUsuarioId($usuarioId);

        if (!$emisor) {
            $idGenerado = Emisor::crearPorDefecto($usuarioId);
            $emisor = Emisor::obtenerPorUsuarioId($usuarioId);
            $_SESSION['flash_info'] = 'Hemos creado un perfil de emisor por defecto. Actualice la información.';
        }

        $this->render('emisor/perfil', [
            'titulo' => 'Mi perfil de emisor',
            'emisor' => $emisor,
        ]);
    }

    public function actualizar(): void
    {
        $usuarioId = $this->obtenerUsuarioId();
        $datos = [
            'NombreCompleto' => trim($_POST['NombreCompleto'] ?? ''),
            'DocumentoIdentidad' => trim($_POST['DocumentoIdentidad'] ?? ''),
            'Email' => trim($_POST['Email'] ?? ''),
            'Telefono' => trim($_POST['Telefono'] ?? ''),
            'Direccion' => trim($_POST['Direccion'] ?? ''),
            'Ciudad' => trim($_POST['Ciudad'] ?? ''),
            'InformacionBancaria' => trim($_POST['InformacionBancaria'] ?? ''),
            'NotaLegal' => trim($_POST['NotaLegal'] ?? ''),
            'FirmaImagenURL' => trim($_POST['FirmaImagenURL'] ?? ''),
        ];

        Emisor::actualizar($usuarioId, $datos);
        $_SESSION['flash_exito'] = 'Información del emisor guardada correctamente.';
        header('Location: /perfil');
        exit;
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
