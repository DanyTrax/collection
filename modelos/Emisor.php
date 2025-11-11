<?php
namespace Modelos;

use Config\BaseDeDatos;
use PDO;

class Emisor
{
    public int $emisorID;
    public int $usuarioID;
    public string $nombreCompleto;
    public ?string $documentoIdentidad;
    public ?string $email;
    public ?string $telefono;
    public ?string $direccion;
    public ?string $ciudad;
    public ?string $informacionBancaria;
    public ?string $notaLegal;

    public static function obtenerPorUsuarioId(int $usuarioId): ?self
    {
        $pdo = BaseDeDatos::obtenerConexion();
        $consulta = $pdo->prepare('SELECT * FROM Emisores WHERE UsuarioID = :usuario LIMIT 1');
        $consulta->execute(['usuario' => $usuarioId]);
        $fila = $consulta->fetch(PDO::FETCH_ASSOC);

        return $fila ? self::desdeFila($fila) : null;
    }

    public static function crearPorDefecto(int $usuarioId): int
    {
        $pdo = BaseDeDatos::obtenerConexion();
        $consulta = $pdo->prepare('INSERT INTO Emisores (UsuarioID, NombreCompleto) VALUES (:usuario, :nombre)');
        $consulta->execute([
            'usuario' => $usuarioId,
            'nombre' => 'Nuevo Emisor',
        ]);
        return (int) $pdo->lastInsertId();
    }

    public static function actualizar(int $usuarioId, array $datos): bool
    {
        $pdo = BaseDeDatos::obtenerConexion();

        $emisorActual = self::obtenerPorUsuarioId($usuarioId);
        if (!$emisorActual) {
            self::crearPorDefecto($usuarioId);
        }

        $consulta = $pdo->prepare('UPDATE Emisores SET NombreCompleto = :nombre, DocumentoIdentidad = :documento, Email = :email, Telefono = :telefono, Direccion = :direccion, Ciudad = :ciudad, InformacionBancaria = :infoBancaria, NotaLegal = :notaLegal WHERE UsuarioID = :usuario');

        return $consulta->execute([
            'nombre' => $datos['NombreCompleto'],
            'documento' => $datos['DocumentoIdentidad'] ?? null,
            'email' => $datos['Email'] ?? null,
            'telefono' => $datos['Telefono'] ?? null,
            'direccion' => $datos['Direccion'] ?? null,
            'ciudad' => $datos['Ciudad'] ?? null,
            'infoBancaria' => $datos['InformacionBancaria'] ?? null,
            'notaLegal' => $datos['NotaLegal'] ?? null,
            'usuario' => $usuarioId,
        ]);
    }

    private static function desdeFila(array $fila): self
    {
        $emisor = new self();
        $emisor->emisorID = (int) $fila['EmisorID'];
        $emisor->usuarioID = (int) $fila['UsuarioID'];
        $emisor->nombreCompleto = $fila['NombreCompleto'];
        $emisor->documentoIdentidad = $fila['DocumentoIdentidad'] ?? null;
        $emisor->email = $fila['Email'] ?? null;
        $emisor->telefono = $fila['Telefono'] ?? null;
        $emisor->direccion = $fila['Direccion'] ?? null;
        $emisor->ciudad = $fila['Ciudad'] ?? null;
        $emisor->informacionBancaria = $fila['InformacionBancaria'] ?? null;
        $emisor->notaLegal = $fila['NotaLegal'] ?? null;
        return $emisor;
    }
}
