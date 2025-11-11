<?php
namespace Modelos;

use Config\BaseDeDatos;
use PDO;

class Cotizacion
{
    public int $cotizacionID;
    public int $usuarioID;
    public int $clienteID;
    public int $emisorID;
    public string $numeroCotizacion;
    public string $fechaEmision;
    public ?string $fechaVencimiento;
    public string $estado;
    public string $concepto;
    public float $valorTotal;
    public ?string $terminos;
    public array $datosEmisor = [];
    public array $datosCliente = [];

    public static function obtenerTodosPorUsuario(int $usuarioId): array
    {
        $pdo = BaseDeDatos::obtenerConexion();
        $consulta = $pdo->prepare('SELECT c.*, cl.NombreCliente FROM Cotizaciones c INNER JOIN Clientes cl ON cl.ClienteID = c.ClienteID WHERE c.UsuarioID = :usuario ORDER BY c.FechaEmision DESC');
        $consulta->execute(['usuario' => $usuarioId]);
        $filas = $consulta->fetchAll(PDO::FETCH_ASSOC);

        return array_map([self::class, 'desdeFila'], $filas);
    }

    public static function obtenerPorId(int $cotizacionId, int $usuarioId): ?self
    {
        $pdo = BaseDeDatos::obtenerConexion();
        $consulta = $pdo->prepare('SELECT * FROM Cotizaciones WHERE CotizacionID = :id AND UsuarioID = :usuario LIMIT 1');
        $consulta->execute([
            'id' => $cotizacionId,
            'usuario' => $usuarioId,
        ]);
        $fila = $consulta->fetch(PDO::FETCH_ASSOC);

        return $fila ? self::desdeFila($fila) : null;
    }

    public static function crear(array $datos): int
    {
        $pdo = BaseDeDatos::obtenerConexion();
        $consulta = $pdo->prepare('INSERT INTO Cotizaciones (UsuarioID, ClienteID, EmisorID, NumeroCotizacion, FechaEmision, FechaVencimiento, Estado, Concepto, ValorTotal, Terminos, DatosEmisorJSON, DatosClienteJSON) VALUES (:usuario, :cliente, :emisor, :numero, :fechaEmision, :fechaVencimiento, :estado, :concepto, :valor, :terminos, :datosEmisor, :datosCliente)');
        $consulta->execute([
            'usuario' => $datos['UsuarioID'],
            'cliente' => $datos['ClienteID'],
            'emisor' => $datos['EmisorID'],
            'numero' => $datos['NumeroCotizacion'],
            'fechaEmision' => $datos['FechaEmision'],
            'fechaVencimiento' => $datos['FechaVencimiento'] ?? null,
            'estado' => $datos['Estado'] ?? 'Pendiente',
            'concepto' => $datos['Concepto'],
            'valor' => $datos['ValorTotal'],
            'terminos' => $datos['Terminos'] ?? null,
            'datosEmisor' => json_encode($datos['DatosEmisor'], JSON_UNESCAPED_UNICODE),
            'datosCliente' => json_encode($datos['DatosCliente'], JSON_UNESCAPED_UNICODE),
        ]);

        return (int) $pdo->lastInsertId();
    }

    public static function actualizar(int $cotizacionId, int $usuarioId, array $datos): bool
    {
        $pdo = BaseDeDatos::obtenerConexion();
        $consulta = $pdo->prepare('UPDATE Cotizaciones SET ClienteID = :cliente, EmisorID = :emisor, NumeroCotizacion = :numero, FechaEmision = :fechaEmision, FechaVencimiento = :fechaVencimiento, Estado = :estado, Concepto = :concepto, ValorTotal = :valor, Terminos = :terminos, DatosEmisorJSON = :datosEmisor, DatosClienteJSON = :datosCliente WHERE CotizacionID = :id AND UsuarioID = :usuario');

        return $consulta->execute([
            'cliente' => $datos['ClienteID'],
            'emisor' => $datos['EmisorID'],
            'numero' => $datos['NumeroCotizacion'],
            'fechaEmision' => $datos['FechaEmision'],
            'fechaVencimiento' => $datos['FechaVencimiento'] ?? null,
            'estado' => $datos['Estado'],
            'concepto' => $datos['Concepto'],
            'valor' => $datos['ValorTotal'],
            'terminos' => $datos['Terminos'] ?? null,
            'datosEmisor' => json_encode($datos['DatosEmisor'], JSON_UNESCAPED_UNICODE),
            'datosCliente' => json_encode($datos['DatosCliente'], JSON_UNESCAPED_UNICODE),
            'id' => $cotizacionId,
            'usuario' => $usuarioId,
        ]);
    }

    public static function borrar(int $cotizacionId, int $usuarioId): bool
    {
        $pdo = BaseDeDatos::obtenerConexion();
        $consulta = $pdo->prepare('DELETE FROM Cotizaciones WHERE CotizacionID = :id AND UsuarioID = :usuario');
        return $consulta->execute([
            'id' => $cotizacionId,
            'usuario' => $usuarioId,
        ]);
    }

    public static function actualizarEstado(int $cotizacionId, int $usuarioId, string $estado): bool
    {
        $pdo = BaseDeDatos::obtenerConexion();
        $consulta = $pdo->prepare('UPDATE Cotizaciones SET Estado = :estado WHERE CotizacionID = :id AND UsuarioID = :usuario');
        return $consulta->execute([
            'estado' => $estado,
            'id' => $cotizacionId,
            'usuario' => $usuarioId,
        ]);
    }

    public function valorFormateado(): string
    {
        return number_format($this->valorTotal, 2, ',', '.');
    }

    public static function desdeFila(array $fila): self
    {
        $cotizacion = new self();
        $cotizacion->cotizacionID = (int) $fila['CotizacionID'];
        $cotizacion->usuarioID = (int) $fila['UsuarioID'];
        $cotizacion->clienteID = (int) $fila['ClienteID'];
        $cotizacion->emisorID = (int) $fila['EmisorID'];
        $cotizacion->numeroCotizacion = $fila['NumeroCotizacion'];
        $cotizacion->fechaEmision = $fila['FechaEmision'];
        $cotizacion->fechaVencimiento = $fila['FechaVencimiento'] ?? null;
        $cotizacion->estado = $fila['Estado'];
        $cotizacion->concepto = $fila['Concepto'];
        $cotizacion->valorTotal = (float) $fila['ValorTotal'];
        $cotizacion->terminos = $fila['Terminos'] ?? null;
        $datosEmisor = $fila['DatosEmisorJSON'] ? json_decode($fila['DatosEmisorJSON'], true) : [];
        $datosCliente = $fila['DatosClienteJSON'] ? json_decode($fila['DatosClienteJSON'], true) : [];
        $cotizacion->datosEmisor = is_array($datosEmisor) ? $datosEmisor : [];
        $cotizacion->datosCliente = is_array($datosCliente) ? $datosCliente : [];
        return $cotizacion;
    }
}
