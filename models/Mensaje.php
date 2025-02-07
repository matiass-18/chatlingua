<?php
class Mensaje {
    private $db;
    
    public function __construct() {
        $this->db = Database::obtenerInstancia()->obtenerConexion();
    }
    
    // Guardar nuevo mensaje
    public function guardar($emisor_id, $receptor_id, $contenido) {
        try {
            $sql = "INSERT INTO mensajes (emisor_id, receptor_id, contenido, fecha, leido) 
                    VALUES (:emisor_id, :receptor_id, :contenido, NOW(), false)";
            
            $stmt = $this->db->prepare($sql);
            $resultado = $stmt->execute([
                ':emisor_id' => $emisor_id,
                ':receptor_id' => $receptor_id,
                ':contenido' => htmlspecialchars($contenido)
            ]);
            
            if ($resultado) {
                return ['success' => true, 'id' => $this->db->lastInsertId()];
            }
            return ['error' => 'Error al guardar el mensaje'];
            
        } catch (PDOException $e) {
            error_log("Error al guardar mensaje: " . $e->getMessage());
            return ['error' => 'Error al guardar el mensaje'];
        }
    }
    
    // Obtener conversación entre dos usuarios
    public function obtenerConversacion($usuario1_id, $usuario2_id) {
        try {
            $sql = "SELECT m.*, 
                           u1.nombre as emisor_nombre, 
                           u1.foto_perfil as emisor_foto,
                           u1.estado as emisor_estado
                    FROM mensajes m 
                    JOIN usuarios u1 ON m.emisor_id = u1.id 
                    WHERE (m.emisor_id = :usuario1_id AND m.receptor_id = :usuario2_id)
                       OR (m.emisor_id = :usuario2_id AND m.receptor_id = :usuario1_id)
                    ORDER BY m.fecha ASC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':usuario1_id' => $usuario1_id,
                ':usuario2_id' => $usuario2_id
            ]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Error al obtener conversación: " . $e->getMessage());
            return [];
        }
    }

    // Marcar mensajes como leídos
    public function marcarComoLeidos($emisor_id, $receptor_id) {
        try {
            $sql = "UPDATE mensajes 
                    SET leido = true 
                    WHERE emisor_id = :emisor_id 
                    AND receptor_id = :receptor_id 
                    AND leido = false";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':emisor_id' => $emisor_id,
                ':receptor_id' => $receptor_id
            ]);
        } catch (PDOException $e) {
            error_log("Error al marcar mensajes como leídos: " . $e->getMessage());
            return false;
        }
    }

    // Obtener mensajes no leídos
    public function obtenerMensajesNoLeidos($usuario_id) {
        try {
            $sql = "SELECT COUNT(*) 
                    FROM mensajes 
                    WHERE receptor_id = :usuario_id 
                    AND leido = false";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':usuario_id' => $usuario_id]);
            return $stmt->fetchColumn();
            
        } catch (PDOException $e) {
            error_log("Error al obtener mensajes no leídos: " . $e->getMessage());
            return 0;
        }
    }

    // Obtener último mensaje entre dos usuarios
    public function obtenerUltimoMensaje($usuario1_id, $usuario2_id) {
        try {
            $sql = "SELECT m.*, 
                           u1.nombre as emisor_nombre
                    FROM mensajes m 
                    JOIN usuarios u1 ON m.emisor_id = u1.id
                    WHERE (m.emisor_id = :usuario1_id AND m.receptor_id = :usuario2_id)
                       OR (m.emisor_id = :usuario2_id AND m.receptor_id = :usuario1_id)
                    ORDER BY m.fecha DESC 
                    LIMIT 1";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':usuario1_id' => $usuario1_id,
                ':usuario2_id' => $usuario2_id
            ]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Error al obtener último mensaje: " . $e->getMessage());
            return null;
        }
    }

    // Eliminar mensajes de una conversación
    public function eliminarConversacion($usuario1_id, $usuario2_id) {
        try {
            $sql = "DELETE FROM mensajes 
                    WHERE (emisor_id = :usuario1_id AND receptor_id = :usuario2_id)
                       OR (emisor_id = :usuario2_id AND receptor_id = :usuario1_id)";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':usuario1_id' => $usuario1_id,
                ':usuario2_id' => $usuario2_id
            ]);
        } catch (PDOException $e) {
            error_log("Error al eliminar conversación: " . $e->getMessage());
            return false;
        }
    }

    // Verificar si existe conversación
    public function existeConversacion($usuario1_id, $usuario2_id) {
        try {
            $sql = "SELECT COUNT(*) FROM mensajes 
                    WHERE (emisor_id = :usuario1_id AND receptor_id = :usuario2_id)
                       OR (emisor_id = :usuario2_id AND receptor_id = :usuario1_id)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':usuario1_id' => $usuario1_id,
                ':usuario2_id' => $usuario2_id
            ]);
            
            return $stmt->fetchColumn() > 0;
            
        } catch (PDOException $e) {
            error_log("Error al verificar conversación: " . $e->getMessage());
            return false;
        }
    }
}