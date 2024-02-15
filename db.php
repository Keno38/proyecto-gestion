<?php


class Database
{
    private $host = 'localhost';
    private $dbname = 'gestion';
    private $usuario = 'root';
    private $clave = '';
    private $conexion;

    public function connect()
    {
        try {
            $this->conexion = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->usuario, $this->clave);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->conexion;
        } catch (PDOException $e) {
            // Lanza una excepción personalizada en lugar de imprimir el mensaje de error directamente
            throw new Exception('Error de conexión a la base de datos:' . $e->getMessage());
        }
    }
}
