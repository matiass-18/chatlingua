<?php
// config/Mailer.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'vendor/autoload.php';

class Mailer {
    private $mail;
    
    public function __construct() {
        $this->mail = new PHPMailer(true);
        
        try {
            // Configuración del servidor
            $this->mail->isSMTP();
            $this->mail->Host = 'smtp.gmail.com';        // Servidor SMTP de Gmail
            $this->mail->SMTPAuth = true;                // Habilitar autenticación SMTP
            $this->mail->Username = 'matias.chahinto@amigo.edu.co'; // Tu correo de Gmail
            $this->mail->Password = 'M%8182%%4S'; //
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mail->Port = 587;                     // Puerto TCP
            
            // Configuración del remitente
            $this->mail->setFrom('juanpipecano08@gmail.com', 'ChatLingua');
            $this->mail->CharSet = 'UTF-8';             // Permitir caracteres especiales
            $this->mail->isHTML(true);                  // Habilitar HTML en el correo
            
        } catch (Exception $e) {
            error_log("Error al configurar mailer: " . $e->getMessage());
            throw new Exception("Error al configurar el sistema de correo");
        }
    }
    
    public function enviarRecuperacion($email, $token) {
        try {
            $this->mail->addAddress($email);            // Añadir destinatario
            $this->mail->Subject = 'Recuperación de Contraseña - ChatLingua';
            
            // URL para restablecer contraseña
            $url = "http://" . $_SERVER['HTTP_HOST'] . "/chatlingua/index.php?action=restablecer&token=" . $token;
            
            // Cuerpo del correo en HTML
            $this->mail->Body = "
                <html>
                <body style='font-family: Arial, sans-serif;'>
                    <h2 style='color: #3B82F6;'>Recuperación de Contraseña - ChatLingua</h2>
                    <p>Has solicitado restablecer tu contraseña. Haz clic en el siguiente enlace para crear una nueva contraseña:</p>
                    <p>
                        <a href='{$url}' 
                           style='background-color: #3B82F6; 
                                  color: white; 
                                  padding: 10px 20px; 
                                  text-decoration: none; 
                                  border-radius: 5px;
                                  display: inline-block;'>
                            Restablecer Contraseña
                        </a>
                    </p>
                    <p><small>Si no solicitaste este cambio, puedes ignorar este correo.</small></p>
                    <p><small>Este enlace expirará en 1 hora por seguridad.</small></p>
                    <hr>
                    <p><small>ChatLingua - Aprende idiomas chateando</small></p>
                </body>
                </html>
            ";
            
            $this->mail->send();
            return true;
            
        } catch (Exception $e) {
            error_log("Error al enviar correo: " . $e->getMessage());
            return false;
        }
    }
}