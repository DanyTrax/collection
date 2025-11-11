<?php
namespace Config;

use PDO;
use PDOException;

/**
 * Clase encargada de gestionar la conexión PDO a la base de datos.
 */
class BaseDeDatos
{
    private const DB_HOST = 'localhost';
    private const DB_NOMBRE = 'dowgroupcol_dbcollection';
    private const DB_USUARIO = 'dowgroupcol_dbcollection_user';
    private const DB_PASSWORD = '8W]Hr~7.~S,XpKm';
    private const DB_CHARSET = 'utf8mb4';

    private static ?PDO $instancia = null;

    /**
     * Obtiene una instancia única de PDO configurada.
     */
    public static function obtenerConexion(): PDO
    {
        if (self::$instancia === null) {
            $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', self::DB_HOST, self::DB_NOMBRE, self::DB_CHARSET);

            $opciones = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            try {
                self::$instancia = new PDO($dsn, self::DB_USUARIO, self::DB_PASSWORD, $opciones);
            } catch (PDOException $e) {
                throw new PDOException('Error de conexión a la base de datos: ' . $e->getMessage(), (int) $e->getCode());
            }
        }

        return self::$instancia;
    }
}
