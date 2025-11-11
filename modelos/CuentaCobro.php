<?php
namespace Modelos;

use Config\BaseDeDatos;
use PDO;

class CuentaCobro
{
    public int $cuentaID;
    public int $usuarioID;
    public int $clienteID;
    public int $emisorID;
    public string $numeroCuenta;
    public string $fechaEmision;
    public ?string $fechaVencimiento;
    public string $estado;
    public string $concepto;
    public float $valorTotal;
    public ?int $cotizacionID;
    public array $datosEmisor = [];
    public array $datosCliente = [];

    public static function obtenerTodosPorUsuario(int $usuarioId): array
    {
        $pdo = BaseDeDatos::obtenerConexion();
        $consulta = $pdo->prepare('SELECT c.*, cl.NombreCliente FROM CuentasDeCobro c INNER JOIN Clientes cl ON cl.ClienteID = c.ClienteID WHERE c.UsuarioID = :usuario ORDER BY c.FechaEmision DESC');
        $consulta->execute(['usuario' => $usuarioId]);
        $filas = $consulta->fetchAll(PDO::FETCH_ASSOC);

        return array_map([self::class, 'desdeFila'], $filas);
    }

    public static function obtenerPorId(int $cuentaId, int $usuarioId): ?self
    {
        $pdo = BaseDeDatos::obtenerConexion();
        $consulta = $pdo->prepare('SELECT * FROM CuentasDeCobro WHERE CuentaID = :id AND UsuarioID = :usuario LIMIT 1');
        $consulta->execute([
            'id' => $cuentaId,
            'usuario' => $usuarioId,
        ]);
        $fila = $consulta->fetch(PDO::FETCH_ASSOC);

        return $fila ? self::desdeFila($fila) : null;
    }

    public static function crear(array $datos): int
    {
        $pdo = BaseDeDatos::obtenerConexion();
        $consulta = $pdo->prepare('INSERT INTO CuentasDeCobro (UsuarioID, ClienteID, EmisorID, NumeroCuenta, FechaEmision, FechaVencimiento, Estado, Concepto, ValorTotal, DatosEmisorJSON, DatosClienteJSON, CotizacionID) VALUES (:usuario, :cliente, :emisor, :numero, :fechaEmision, :fechaVencimiento, :estado, :concepto, :valor, :datosEmisor, :datosCliente, :cotizacionId)');
        $consulta->execute([
            'usuario' => $datos['UsuarioID'],
            'cliente' => $datos['ClienteID'],
            'emisor' => $datos['EmisorID'],
            'numero' => $datos['NumeroCuenta'],
            'fechaEmision' => $datos['FechaEmision'],
            'fechaVencimiento' => $datos['FechaVencimiento'] ?? null,
            'estado' => $datos['Estado'] ?? 'Pendiente',
            'concepto' => $datos['Concepto'],
            'valor' => $datos['ValorTotal'],
            'datosEmisor' => json_encode($datos['DatosEmisor'], JSON_UNESCAPED_UNICODE),
            'datosCliente' => json_encode($datos['DatosCliente'], JSON_UNESCAPED_UNICODE),
            'cotizacionId' => $datos['CotizacionID'] ?? null,
        ]);

        return (int) $pdo->lastInsertId();
    }

    public static function actualizar(int $cuentaId, int $usuarioId, array $datos): bool
    {
        $pdo = BaseDeDatos::obtenerConexion();
        $consulta = $pdo->prepare('UPDATE CuentasDeCobro SET ClienteID = :cliente, EmisorID = :emisor, NumeroCuenta = :numero, FechaEmision = :fechaEmision, FechaVencimiento = :fechaVencimiento, Estado = :estado, Concepto = :concepto, ValorTotal = :valor, DatosEmisorJSON = :datosEmisor, DatosClienteJSON = :datosCliente, CotizacionID = :cotizacionId WHERE CuentaID = :id AND UsuarioID = :usuario');

        return $consulta->execute([
            'cliente' => $datos['ClienteID'],
            'emisor' => $datos['EmisorID'],
            'numero' => $datos['NumeroCuenta'],
            'fechaEmision' => $datos['FechaEmision'],
            'fechaVencimiento' => $datos['FechaVencimiento'] ?? null,
            'estado' => $datos['Estado'],
            'concepto' => $datos['Concepto'],
            'valor' => $datos['ValorTotal'],
            'datosEmisor' => json_encode($datos['DatosEmisor'], JSON_UNESCAPED_UNICODE),
            'datosCliente' => json_encode($datos['DatosCliente'], JSON_UNESCAPED_UNICODE),
            'cotizacionId' => $datos['CotizacionID'] ?? null,
            'id' => $cuentaId,
            'usuario' => $usuarioId,
        ]);
    }

    public static function borrar(int $cuentaId, int $usuarioId): bool
    {
        $pdo = BaseDeDatos::obtenerConexion();
        $consulta = $pdo->prepare('DELETE FROM CuentasDeCobro WHERE CuentaID = :id AND UsuarioID = :usuario');
        return $consulta->execute([
            'id' => $cuentaId,
            'usuario' => $usuarioId,
        ]);
    }

    public static function desdeCotizacion(Cotizacion $cotizacion, array $datos): int
    {
        $datos['DatosEmisor'] = $cotizacion->datosEmisor;
        $datos['DatosCliente'] = $cotizacion->datosCliente;
        $datos['CotizacionID'] = $cotizacion->cotizacionID;
        return self::crear($datos);
    }

    public function valorFormateado(): string
    {
        return number_format($this->valorTotal, 2, ',', '.');
    }

    public static function desdeFila(array $fila): self
    {
        $cuenta = new self();
        $cuenta->cuentaID = (int) $fila['CuentaID'];
        $cuenta->usuarioID = (int) $fila['UsuarioID'];
        $cuenta->clienteID = (int) $fila['ClienteID'];
        $cuenta->emisorID = (int) $fila['EmisorID'];
        $cuenta->numeroCuenta = $fila['NumeroCuenta'];
        $cuenta->fechaEmision = $fila['FechaEmision'];
        $cuenta->fechaVencimiento = $fila['FechaVencimiento'] ?? null;
        $cuenta->estado = $fila['Estado'];
        $cuenta->concepto = $fila['Concepto'];
        $cuenta->valorTotal = (float) $fila['ValorTotal'];
        $cuenta->cotizacionID = isset($fila['CotizacionID']) ? (int) $fila['CotizacionID'] : null;
        $datosEmisor = $fila['DatosEmisorJSON'] ? json_decode($fila['DatosEmisorJSON'], true) : [];
        $datosCliente = $fila['DatosClienteJSON'] ? json_decode($fila['DatosClienteJSON'], true) : [];
        $cuenta->datosEmisor = is_array($datosEmisor) ? $datosEmisor : [];
        $cuenta->datosCliente = is_array($datosCliente) ? $datosCliente : [];
        return $cuenta;
    }
}
