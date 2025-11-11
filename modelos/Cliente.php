<?php
namespace Modelos;

use Config\BaseDeDatos;
use PDO;

class Cliente
{
    public int $clienteID;
    public int $usuarioID;
    public string $nombreCliente;
    public ?string $nitCc;
    public ?string $direccion;
    public ?string $email;
    public ?string $telefono;
    public ?string $ciudad;

    public static function obtenerTodosPorUsuario(int $usuarioId): array
    {
        $pdo = BaseDeDatos::obtenerConexion();
        $consulta = $pdo->prepare('SELECT * FROM Clientes WHERE UsuarioID = :usuario ORDER BY NombreCliente ASC');
        $consulta->execute(['usuario' => $usuarioId]);
        $filas = $consulta->fetchAll(PDO::FETCH_ASSOC);

        return array_map([self::class, 'desdeFila'], $filas);
    }

    public static function obtenerPorId(int $clienteId, int $usuarioId): ?self
    {
        $pdo = BaseDeDatos::obtenerConexion();
        $consulta = $pdo->prepare('SELECT * FROM Clientes WHERE ClienteID = :id AND UsuarioID = :usuario LIMIT 1');
        $consulta->execute([
            'id' => $clienteId,
            'usuario' => $usuarioId,
        ]);
        $fila = $consulta->fetch(PDO::FETCH_ASSOC);

        return $fila ? self::desdeFila($fila) : null;
    }

    public static function crear(array $datos): int
    {
        $pdo = BaseDeDatos::obtenerConexion();
        $consulta = $pdo->prepare('INSERT INTO Clientes (UsuarioID, NombreCliente, NIT_CC, Direccion, Email, Telefono, Ciudad) VALUES (:usuario, :nombre, :nit, :direccion, :email, :telefono, :ciudad)');
        $consulta->execute([
            'usuario' => $datos['UsuarioID'],
            'nombre' => $datos['NombreCliente'],
            'nit' => $datos['NIT_CC'] ?? null,
            'direccion' => $datos['Direccion'] ?? null,
            'email' => $datos['Email'] ?? null,
            'telefono' => $datos['Telefono'] ?? null,
            'ciudad' => $datos['Ciudad'] ?? null,
        ]);

        return (int) $pdo->lastInsertId();
    }

    public static function actualizar(int $clienteId, int $usuarioId, array $datos): bool
    {
        $pdo = BaseDeDatos::obtenerConexion();
        $consulta = $pdo->prepare('UPDATE Clientes SET NombreCliente = :nombre, NIT_CC = :nit, Direccion = :direccion, Email = :email, Telefono = :telefono, Ciudad = :ciudad WHERE ClienteID = :id AND UsuarioID = :usuario');

        return $consulta->execute([
            'nombre' => $datos['NombreCliente'],
            'nit' => $datos['NIT_CC'] ?? null,
            'direccion' => $datos['Direccion'] ?? null,
            'email' => $datos['Email'] ?? null,
            'telefono' => $datos['Telefono'] ?? null,
            'ciudad' => $datos['Ciudad'] ?? null,
            'id' => $clienteId,
            'usuario' => $usuarioId,
        ]);
    }

    public static function borrar(int $clienteId, int $usuarioId): bool
    {
        $pdo = BaseDeDatos::obtenerConexion();
        $consulta = $pdo->prepare('DELETE FROM Clientes WHERE ClienteID = :id AND UsuarioID = :usuario');
        return $consulta->execute([
            'id' => $clienteId,
            'usuario' => $usuarioId,
        ]);
    }

    private static function desdeFila(array $fila): self
    {
        $cliente = new self();
        $cliente->clienteID = (int) $fila['ClienteID'];
        $cliente->usuarioID = (int) $fila['UsuarioID'];
        $cliente->nombreCliente = $fila['NombreCliente'];
        $cliente->nitCc = $fila['NIT_CC'] ?? null;
        $cliente->direccion = $fila['Direccion'] ?? null;
        $cliente->email = $fila['Email'] ?? null;
        $cliente->telefono = $fila['Telefono'] ?? null;
        $cliente->ciudad = $fila['Ciudad'] ?? null;
        return $cliente;
    }
}
