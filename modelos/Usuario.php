<?php
namespace Modelos;

use Config\BaseDeDatos;
use PDO;

class Usuario
{
    public int $usuarioID;
    public string $email;
    public string $passwordHash;
    public ?string $nombre;

    public static function buscarPorEmail(string $email): ?self
    {
        $pdo = BaseDeDatos::obtenerConexion();
        $consulta = $pdo->prepare('SELECT * FROM Usuarios WHERE Email = :email LIMIT 1');
        $consulta->execute(['email' => $email]);
        $datos = $consulta->fetch(PDO::FETCH_ASSOC);

        if (!$datos) {
            return null;
        }

        return self::desdeFila($datos);
    }

    public static function obtenerPorId(int $usuarioId): ?self
    {
        $pdo = BaseDeDatos::obtenerConexion();
        $consulta = $pdo->prepare('SELECT * FROM Usuarios WHERE UsuarioID = :id LIMIT 1');
        $consulta->execute(['id' => $usuarioId]);
        $datos = $consulta->fetch(PDO::FETCH_ASSOC);

        if (!$datos) {
            return null;
        }

        return self::desdeFila($datos);
    }

    private static function desdeFila(array $fila): self
    {
        $usuario = new self();
        $usuario->usuarioID = (int) $fila['UsuarioID'];
        $usuario->email = $fila['Email'];
        $usuario->passwordHash = $fila['PasswordHash'];
        $usuario->nombre = $fila['Nombre'] ?? null;
        return $usuario;
    }
}
