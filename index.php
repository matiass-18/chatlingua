<?php
// Iniciamos la sesión
session_start();

// Incluimos los archivos necesarios
require_once 'config/Database.php';
require_once 'config/Mailer.php';  // Añadir esta línea
require_once 'models/Usuario.php';
require_once 'models/Mensaje.php';
require_once 'controllers/UsuarioController.php';
require_once 'controllers/ChatController.php';

// Manejo básico de rutas
$action = isset($_GET['action']) ? $_GET['action'] : 'login';

// Router básico
switch ($action) {
    case 'registro':
        $controller = new UsuarioController();
        $controller->registro();
        break;
        
    case 'login':
        $controller = new UsuarioController();
        $controller->login();
        break;
        
    case 'recuperar':
        $controller = new UsuarioController();
        $controller->recuperar();
        break;
        
    case 'restablecer':
        $controller = new UsuarioController();
        $controller->restablecer();
        break;
        
    case 'chat':
        if (!isset($_SESSION['usuario'])) {
            header('Location: index.php?action=login');
            exit();
        }
        $controller = new ChatController();
        $controller->index();
        break;

    case 'obtener-mensajes':
        $controller = new ChatController();
        $controller->obtenerMensajes();
        break;
        
    case 'enviar-mensaje':
        $controller = new ChatController();
        $controller->enviarMensaje();
        break;    
        
    case 'actualizar-estado':
        if (isset($_SESSION['usuario'])) {
            $controller = new UsuarioController();
            $data = json_decode(file_get_contents('php://input'), true);
            $estado = $data['estado'] ?? 'desconectado';
            $controller->actualizarEstado($estado);
        }
        break;

    case 'logout':
        $controller = new UsuarioController();
        $controller->logout();
        break;
        
    default:
        header('Location: index.php?action=login');
        break;
}