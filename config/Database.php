<?php
class Database {
    private static $instancia = null;
    private $conexion;
    
    private function __construct() {
        try {
            $this->conexion = new PDO(
                "mysql:host=localhost;dbname=chatlingua",
                "root",  
                "[Nomeacuerd0]",     
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
            );
            $this->conexion->exec("SET NAMES 'utf8'");
        } catch(PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
            die();
        }
    }
    
    public static function obtenerInstancia() {
        if (!self::$instancia) {
            self::$instancia = new Database();
        }
        return self::$instancia;
    }
    
    public function obtenerConexion() {
        return $this->conexion;
    }
    

}