<?php
class ChatController {
    private $mensajeModel;
    private $usuarioModel;
    
    public function __construct() {
        $this->mensajeModel = new Mensaje();
        $this->usuarioModel = new Usuario();
    }
    
    public function index() {
        if (!isset($_SESSION['usuario'])) {
            header('Location: index.php?action=login');
            exit();
        }
        
        // Obtener usuarios para la lista del chat
        $usuarios_activos = $this->usuarioModel->obtenerUsuariosActivos($_SESSION['usuario']['id']);
        
        // Verificar si hay mensajes no leídos totales
        $mensajes_no_leidos = $this->mensajeModel->obtenerMensajesNoLeidos($_SESSION['usuario']['id']);
        
        require_once 'views/chat.php';
    }

    public function obtenerMensajes() {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['usuario']) || !isset($_GET['receptor_id'])) {
            echo json_encode(['error' => 'No autorizado']);
            return;
        }
    
        try {
            // marcar los mensajes como leídos
            $this->mensajeModel->marcarComoLeidos(
                $_GET['receptor_id'],
                $_SESSION['usuario']['id']
            );
    
            // obtener los mensajes
            $mensajes = $this->mensajeModel->obtenerConversacion(
                $_SESSION['usuario']['id'],
                $_GET['receptor_id']
            );
            
            echo json_encode($mensajes);
            
        } catch (Exception $e) {
            error_log("Error en obtenerMensajes: " . $e->getMessage());
            echo json_encode(['error' => 'Error al obtener mensajes']);
        }
    }

    public function enviarMensaje() {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['usuario'])) {
            echo json_encode(['error' => 'No autorizado']);
            return;
        }

        if (!isset($_POST['receptor_id']) || !isset($_POST['mensaje'])) {
            echo json_encode(['error' => 'Datos incompletos']);
            return;
        }

        try {
            $resultado = $this->mensajeModel->guardar(
                $_SESSION['usuario']['id'],
                $_POST['receptor_id'],
                $_POST['mensaje']
            );
            
            if (isset($resultado['success'])) {
                // Obtener la conversación actualizada
                $mensajes = $this->mensajeModel->obtenerConversacion(
                    $_SESSION['usuario']['id'],
                    $_POST['receptor_id']
                );
                
                echo json_encode([
                    'success' => true,
                    'mensajes' => $mensajes
                ]);
            } else {
                echo json_encode(['error' => 'Error al enviar mensaje']);
            }
            
        } catch (Exception $e) {
            error_log("Error en enviarMensaje: " . $e->getMessage());
            echo json_encode(['error' => 'Error al enviar mensaje']);
        }
    }

    public function actualizarEstadoUsuario() {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['usuario'])) {
            echo json_encode(['error' => 'No autorizado']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $estado = $data['estado'] ?? 'desconectado';

        try {
            $resultado = $this->usuarioModel->actualizarEstado(
                $_SESSION['usuario']['id'],
                $estado
            );
            
            echo json_encode(['success' => $resultado]);
            
        } catch (Exception $e) {
            error_log("Error en actualizarEstadoUsuario: " . $e->getMessage());
            echo json_encode(['error' => 'Error al actualizar estado']);
        }
    }

    public function obtenerUsuariosActivos() {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['usuario'])) {
            echo json_encode(['error' => 'No autorizado']);
            return;
        }

        try {
            $usuarios = $this->usuarioModel->obtenerUsuariosActivos($_SESSION['usuario']['id']);
            echo json_encode($usuarios);
            
        } catch (Exception $e) {
            error_log("Error en obtenerUsuariosActivos: " . $e->getMessage());
            echo json_encode(['error' => 'Error al obtener usuarios']);
        }
    }

    public function marcarMensajesLeidos() {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['usuario']) || !isset($_POST['emisor_id'])) {
            echo json_encode(['error' => 'No autorizado']);
            return;
        }

        try {
            $resultado = $this->mensajeModel->marcarComoLeidos(
                $_POST['emisor_id'],
                $_SESSION['usuario']['id']
            );
            
            echo json_encode(['success' => $resultado]);
            
        } catch (Exception $e) {
            error_log("Error en marcarMensajesLeidos: " . $e->getMessage());
            echo json_encode(['error' => 'Error al marcar mensajes como leídos']);
        }
    }

    public function obtenerUltimoMensaje() {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['usuario']) || !isset($_GET['usuario_id'])) {
            echo json_encode(['error' => 'No autorizado']);
            return;
        }

        try {
            $mensaje = $this->mensajeModel->obtenerUltimoMensaje(
                $_SESSION['usuario']['id'],
                $_GET['usuario_id']
            );
            
            echo json_encode($mensaje);
            
        } catch (Exception $e) {
            error_log("Error en obtenerUltimoMensaje: " . $e->getMessage());
            echo json_encode(['error' => 'Error al obtener último mensaje']);
        }
    }
}