<?php
class UsuarioController {
    private $usuarioModel;
    
    public function __construct() {
        $this->usuarioModel = new Usuario();
    }
    // registroo
    public function registro() {
        $errores = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errores = $this->validarRegistro($_POST, $_FILES);
            
            if (empty($errores)) {
                // Procesar la foto de perfil
                $foto_perfil = $this->procesarFotoPerfil($_FILES['foto_perfil']);
                
                // registrar al usuario
                $resultado = $this->usuarioModel->registrar(
                    $_POST['nombre'],
                    $_POST['apellido'],
                    $_POST['email'],
                    $_POST['password'],
                    $_POST['idioma_aprender'],
                    $foto_perfil
                );
                
                if (isset($resultado['success'])) {
                    $_SESSION['mensaje'] = "Registro exitoso. Por favor, inicia sesión.";
                    header('Location: index.php?action=login');
                    exit();
                } else {
                    $errores[] = $resultado['error'] ?? "Error al registrar el usuario.";
                }
            }
        }
        
        require_once 'views/registro.php';
    }
    
    public function login() {
        $error = null;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            $resultado = $this->usuarioModel->iniciarSesion($email, $password);
            
            if (isset($resultado['success'])) {
                $_SESSION['usuario'] = $resultado['usuario'];
                $this->usuarioModel->actualizarEstado($resultado['usuario']['id'], 'conectado');
                header('Location: index.php?action=chat');
                exit();
            } else {
                $error = $resultado['error'] ?? "Error al iniciar sesión.";
            }
        }
        
        require_once 'views/login.php';
    }
    
    public function logout() {
        try {
            if (isset($_SESSION['usuario']) && isset($_SESSION['usuario']['id'])) {
                // Actualizar estado a desconectado
                $resultado = $this->usuarioModel->actualizarEstado($_SESSION['usuario']['id'], 'desconectado');
                
                if (!$resultado) {
                    error_log("Error al actualizar estado del usuario " . $_SESSION['usuario']['id']);
                }
            }
            
            // Limpiar la sesión
            session_destroy();
            
            // Redirigir al login
            header('Location: index.php?action=login');
            exit();
            
        } catch (Exception $e) {
            error_log("Error en logout: " . $e->getMessage());
            header('Location: index.php?action=login');
            exit();
        }
    }

    public function actualizarEstado($estado) {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['usuario'])) {
            echo json_encode(['error' => 'No autorizado']);
            return;
        }
        
        $resultado = $this->usuarioModel->actualizarEstado(
            $_SESSION['usuario']['id'],
            $estado
        );
        
        echo json_encode(['success' => $resultado]);
    }
    
    private function validarRegistro($post, $files) {
        $errores = [];
        
        if (empty($post['nombre']) || strlen($post['nombre']) < 2) {
            $errores[] = "El nombre debe tener al menos 2 caracteres.";
        }
        
        if (empty($post['apellido']) || strlen($post['apellido']) < 2) {
            $errores[] = "El apellido debe tener al menos 2 caracteres.";
        }
        
        if (empty($post['email']) || !filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
            $errores[] = "Email inválido.";
        }
        
        if (empty($post['password']) || strlen($post['password']) < 6) {
            $errores[] = "La contraseña debe tener al menos 6 caracteres.";
        }
        
        if ($post['password'] !== $post['password_confirm']) {
            $errores[] = "Las contraseñas no coinciden.";
        }
        
        if (empty($post['idioma_aprender'])) {
            $errores[] = "Debes seleccionar un idioma para aprender.";
        }
        
        if (isset($files['foto_perfil']) && $files['foto_perfil']['error'] !== 4) {
            $permitidos = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($files['foto_perfil']['type'], $permitidos)) {
                $errores[] = "El tipo de archivo no está permitido. Solo se permiten JPG, PNG y GIF.";
            }
            if ($files['foto_perfil']['size'] > 5242880) { // 5MB
                $errores[] = "La imagen no debe superar los 5MB.";
            }
        }
        
        return $errores;
    }
    
    private function procesarFotoPerfil($archivo) {
        if ($archivo['error'] === 4) {
            return 'default.jpg';
        }
        
        $directorio = 'uploads/';
        if (!file_exists($directorio)) {
            mkdir($directorio, 0777, true);
        }
        
        $nombre_archivo = uniqid() . '_' . basename($archivo['name']);
        $ruta_destino = $directorio . $nombre_archivo;
        
        if (move_uploaded_file($archivo['tmp_name'], $ruta_destino)) {
            return $nombre_archivo;
        }
        
        return 'default.jpg';
    }

    public function recuperar() {
        $error = null;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            
            if (empty($email)) {
                $error = "Por favor, ingresa tu correo electrónico.";
            } else {
                $resultado = $this->usuarioModel->generarTokenRecuperacion($email);
                
                if (isset($resultado['success'])) {
                    // Enviar correo con el token
                    $mailer = new Mailer();
                    if ($mailer->enviarRecuperacion($email, $resultado['token'])) {
                        $_SESSION['mensaje'] = "Se ha enviado un enlace de recuperación a tu correo electrónico.";
                        header('Location: index.php?action=login');
                        exit();
                    } else {
                        $error = "Error al enviar el correo de recuperación.";
                    }
                } else {
                    $error = $resultado['error'] ?? "Error al procesar la solicitud.";
                }
            }
        }
        
        require_once 'views/recuperar.php';
    }
    
    public function restablecer() {
        $error = null;
        $token = $_GET['token'] ?? '';
        
        if (empty($token)) {
            header('Location: index.php?action=login');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = $_POST['password'] ?? '';
            $password_confirm = $_POST['password_confirm'] ?? '';
            
            if ($password !== $password_confirm) {
                $error = "Las contraseñas no coinciden.";
            } elseif (strlen($password) < 6) {
                $error = "La contraseña debe tener al menos 6 caracteres.";
            } else {
                $resultado = $this->usuarioModel->actualizarPassword($token, $password);
                
                if (isset($resultado['success'])) {
                    $_SESSION['mensaje'] = "Contraseña actualizada con éxito. Por favor, inicia sesión.";
                    header('Location: index.php?action=login');
                    exit();
                } else {
                    $error = $resultado['error'] ?? "Error al actualizar la contraseña.";
                }
            }
        }
        
        // Verificar token
        $resultado = $this->usuarioModel->verificarToken($token);
        if (!isset($resultado['success'])) {
            $_SESSION['mensaje'] = $resultado['error'] ?? "Enlace inválido o expirado.";
            header('Location: index.php?action=login');
            exit();
        }
        
        require_once 'views/restablecer.php';
    }
}