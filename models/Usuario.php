<?php
class Usuario {
    private $db;
    
    public function __construct() {
        $this->db = Database::obtenerInstancia()->obtenerConexion();
    }
    
    public function registrar($nombre, $apellido, $email, $password, $idioma_aprender, $foto_perfil) {
        try {
            if ($this->emailExiste($email)) {
                return ['error' => 'El email ya está registrado'];
            }

            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            
            $sql = "INSERT INTO usuarios (nombre, apellido, email, password, idioma_aprender, foto_perfil, estado) 
                    VALUES (:nombre, :apellido, :email, :password, :idioma_aprender, :foto_perfil, 'desconectado')";
            
            $stmt = $this->db->prepare($sql);
            $resultado = $stmt->execute([
                ':nombre' => $nombre,
                ':apellido' => $apellido,
                ':email' => $email,
                ':password' => $password_hash,
                ':idioma_aprender' => $idioma_aprender,
                ':foto_perfil' => $foto_perfil
            ]);
            
            return $resultado ? ['success' => true] : ['error' => 'Error al registrar'];
        } catch (PDOException $e) {
            error_log("Error en registro: " . $e->getMessage());
            return ['error' => 'Error en el registro'];
        }
    }
    
    private function emailExiste($email) {
        $sql = "SELECT COUNT(*) FROM usuarios WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetchColumn() > 0;
    }
    
    public function iniciarSesion($email, $password) {
        try {
            $sql = "SELECT * FROM usuarios WHERE email = :email";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':email' => $email]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($usuario && password_verify($password, $usuario['password'])) {
                $this->actualizarEstado($usuario['id'], 'conectado');
                unset($usuario['password']);
                return ['success' => true, 'usuario' => $usuario];
            }
            
            return ['error' => 'Email o contraseña incorrectos'];
        } catch (PDOException $e) {
            error_log("Error en login: " . $e->getMessage());
            return ['error' => 'Error al iniciar sesión'];
        }
    }
    
    public function actualizarEstado($id_usuario, $estado) {
        try {
            $sql = "UPDATE usuarios SET estado = :estado WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':estado' => $estado,
                ':id' => $id_usuario
            ]);
        } catch (PDOException $e) {
            error_log("Error al actualizar estado: " . $e->getMessage());
            return false;
        }
    }
    
    public function obtenerUsuariosActivos($usuario_actual_id) {
        try {
            // Consulta simplificada para obtener todos los usuarios excepto el actual
            $sql = "SELECT 
                        u.id, 
                        u.nombre, 
                        u.apellido, 
                        u.foto_perfil, 
                        u.estado, 
                        u.idioma_aprender,
                        (SELECT COUNT(*) 
                         FROM mensajes m 
                         WHERE m.emisor_id = u.id 
                         AND m.receptor_id = :usuario_actual_id 
                         AND m.leido = 0) as mensajes_no_leidos
                    FROM usuarios u 
                    WHERE u.id != :usuario_actual_id 
                    ORDER BY u.estado = 'conectado' DESC, 
                             u.nombre ASC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':usuario_actual_id' => $usuario_actual_id]);
            
            $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $usuarios ? $usuarios : [];
            
        } catch (PDOException $e) {
            error_log("Error al obtener usuarios: " . $e->getMessage());
            return [];
        }
    }
    
    public function obtenerUsuarioPorId($id) {
        try {
            $sql = "SELECT id, nombre, apellido, email, foto_perfil, estado, idioma_aprender 
                    FROM usuarios 
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener usuario: " . $e->getMessage());
            return null;
        }
    }
    
    public function actualizarFotoPerfil($id_usuario, $nueva_foto) {
        try {
            $sql = "UPDATE usuarios SET foto_perfil = :foto WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':foto' => $nueva_foto,
                ':id' => $id_usuario
            ]);
        } catch (PDOException $e) {
            error_log("Error al actualizar foto: " . $e->getMessage());
            return false;
        }
    }

    public function generarTokenRecuperacion($email) {
        try {
            if (!$this->emailExiste($email)) {
                return ['error' => 'No existe una cuenta con este email'];
            }

            $token = bin2hex(random_bytes(32));
            $expira = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $sql = "UPDATE usuarios 
                    SET reset_token = :token, 
                        reset_expira = :expira 
                    WHERE email = :email";

            $stmt = $this->db->prepare($sql);
            $resultado = $stmt->execute([
                ':token' => $token,
                ':expira' => $expira,
                ':email' => $email
            ]);

            if ($resultado) {
                return ['success' => true, 'token' => $token];
            }
            return ['error' => 'Error al generar token'];
            
        } catch (PDOException $e) {
            error_log("Error en generarTokenRecuperacion: " . $e->getMessage());
            return ['error' => 'Error al procesar la solicitud'];
        }
    }

    public function verificarToken($token) {
        try {
            $sql = "SELECT id, email, reset_expira 
                    FROM usuarios 
                    WHERE reset_token = :token";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':token' => $token]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$usuario) {
                return ['error' => 'Token inválido'];
            }

            if (strtotime($usuario['reset_expira']) < time()) {
                return ['error' => 'El token ha expirado'];
            }

            return ['success' => true, 'usuario' => $usuario];
            
        } catch (PDOException $e) {
            error_log("Error en verificarToken: " . $e->getMessage());
            return ['error' => 'Error al verificar token'];
        }
    }

    public function actualizarPassword($token, $password) {
        try {
            $resultado = $this->verificarToken($token);
            if (!isset($resultado['success'])) {
                return $resultado;
            }

            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            
            $sql = "UPDATE usuarios 
                    SET password = :password, 
                        reset_token = NULL, 
                        reset_expira = NULL 
                    WHERE reset_token = :token";

            $stmt = $this->db->prepare($sql);
            $actualizado = $stmt->execute([
                ':password' => $password_hash,
                ':token' => $token
            ]);

            if ($actualizado) {
                return ['success' => true];
            }
            return ['error' => 'Error al actualizar contraseña'];
            
        } catch (PDOException $e) {
            error_log("Error en actualizarPassword: " . $e->getMessage());
            return ['error' => 'Error al actualizar contraseña'];
        }
    }
}